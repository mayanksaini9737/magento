<?php 

class Ccc_Outlook_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getBasePath()
    {
        return Mage::getBaseDir('var') . DS . 'outlook' . DS . 'config' . DS . 'email' . DS . 'attachments'. DS;
    }
}