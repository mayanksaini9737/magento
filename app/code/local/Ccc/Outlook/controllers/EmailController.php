<?php 

class Ccc_Outlook_EmailController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $configId = $this->getRequest()->getParam('state');
        // echo $configId;

        $configModel = Mage::getModel('outlook/configuration')->load($configId);
        
        $model = Mage::getModel('outlook/api');
        $accessToken = $model->getAccessToken($configModel);
        
        $configModel->setAccessToken($accessToken)->save();
        echo 'Access Token is saved';
        
    }
}