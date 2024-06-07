<?php

class Ccc_Outlook_Model_Api extends Mage_Core_Model_Abstract
{
    private $_scope = 'Mail.Read Mail.ReadBasic';
    private $_redirectUri = null;
    private $_configObject = null;
    protected function _construct()
    {
        $this->_redirectUri = Mage::getBaseUrl() . 'outlook/email/index';
    }

    public function setConfigObject($configObj)
    {
        $this->_configObject = $configObj;
        return $this;
    }

    public function getAuthorizationUrl($clientId, $configId)
    {
        $authUrl = "https://login.microsoftonline.com/common/oauth2/v2.0/authorize";
        $params = [
            'client_id' => $clientId,
            'response_type' => 'code',
            'redirect_uri' => $this->_redirectUri,
            'scope' => $this->_scope,
            'response_mode' => 'query',
            // 'state' => 1, // Generate a random string for state
            'state' => $configId,
        ];
        return $authUrl . '?' . http_build_query($params);
    }

    public function getAccessToken($configModel)
    {
        $request = Mage::app()->getRequest();
        $code = $request->getParam('code');
        $tokenUrl = "https://login.microsoftonline.com/consumers/oauth2/v2.0/token";
        $data = [
            'client_id' => $configModel->getClientId(),
            'scope' => $this->_scope,
            'code' => $code,
            // 'redirect_uri' => $this->_redirectUri,
            'redirect_uri' => 'http://localhost/magento/index.php/outlook/email/index',
            'grant_type' => 'authorization_code',
            'client_secret' => $configModel->getSecretValue(),
        ];

        $headers = [
            'Content-Type: application/x-www-form-urlencoded'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $tokenUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        if ($response === false) {
            throw new Exception('Error fetching access token: ' . curl_error($ch));
        }
        curl_close($ch);

        $result = json_decode($response, true);

        if (isset($result['access_token'])) {
            return $result['access_token'];
        } else {
            throw new Exception('Error fetching access token: ' . $response);
        }
    }


    public function getEmails()
    {

        $accessToken = $this->_configObject->getAccessToken();
        $url = 'https://graph.microsoft.com/v1.0/me/messages';

        $readDateTime = $this->_configObject->getReadDatetime();

        if (!is_null($readDateTime)) {
            $date = new DateTime($readDateTime, new DateTimeZone('UTC'));
            $date->modify('+1 second');
            $formatted_date = $date->format('Y-m-d\TH:i:s\Z');
            $url .= '?$filter=receivedDateTime+ge+'. $formatted_date;
            // $url .= '?$orderby=receivedDateTime';
            // $url .= '&$filter=ReceivedDateTime+ge+'. $formatted_date;
        }
        // echo $url;
        // die;

        $headers = [
            'Authorization: Bearer ' . $accessToken,
            'Accept: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new Exception('Error fetching emails: ' . curl_error($ch));
        }

        $emails = json_decode($response, true);
        $allEmails = $this->refineEmails($emails['value']);

        return $allEmails;
    }


    public function refineEmails($emails)
    {
        $refinedEmails = [];

        foreach ($emails as $email) {
            $body = strip_tags($email['body']['content']);
            $refinedArr = [
                'config_id' => $this->_configObject->getConfigId(),
                'from' => $email['from']['emailAddress']['address'],
                'to' => $email['toRecipients'][0]['emailAddress']['address'],
                'subject' => $email['subject'],
                'received_datetime' => $email['receivedDateTime'],
                'has_attachments' => $email['hasAttachments'],
                'body' => trim($body),
                'outlook_id' => $email['id'],
            ];
            $refinedEmails[] = $refinedArr;
        }
        return $refinedEmails;
    }


    public function fetchAttachment($email)
    {
        $accessToken =  $this->_configObject->getAccessToken();

        $url = 'https://graph.microsoft.com/v1.0/me/messages/' . 
            urlencode($email->getOutlookId()) . '/attachments';

        $headers = [
            'Authorization: Bearer ' . $accessToken,
            'Accept: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response, true);
        if (isset($response['error'])) {
            print_r($response['error_description']);
        }
        return $response['value'];
    }
}
