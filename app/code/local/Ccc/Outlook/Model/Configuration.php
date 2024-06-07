<?php 

class Ccc_Outlook_Model_Configuration extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('outlook/configuration');
    }

    public function getIsActiveArray()
    {
        return [
            '1' => 'Yes',
            '2' => 'No'
        ];
    }

    public function fetchEmails()
    {
        $apiModel = Mage::getModel('outlook/api');
        
        $apiModel->setConfigObject($this);
        $allEmails = $apiModel->getEmails();
        // print_r($allEmails);
        // die;

        $emailModel = Mage::getModel('outlook/email');
        $emailModel->setConfigObject($this);
          
        $readDateTime = '';
        foreach ($allEmails as $_email) {
            $emailModel->setData($_email)->save();
            
            if($emailModel->getHasAttachments()){
                $allAttachment = $apiModel->fetchAttachment($emailModel);
                $emailModel->saveAttachments($allAttachment);
            }
            $readDateTime = date('Y-m-d h:i:s', strtotime($_email['received_datetime']));

            $emailModel->checkEvents();
        }
        if (!empty($readDateTime)){
            $this->setData('read_datetime',$readDateTime);
            $this->save();
        }
    }
    
}