<?php 

class Ccc_Outlook_Model_Observer
{
    public function fetch()
    {
        $configCollection = Mage::getModel('outlook/configuration')->getCollection();

        foreach($configCollection as $_config){
            $_config->fetchEmails();
        }
    }

    public function checklogs(Varien_Event_Observer $observer)
    {
        Mage::log($observer->getEvent()->getModel()->getFrom(), null, 'sender.log', true);
    }
}
