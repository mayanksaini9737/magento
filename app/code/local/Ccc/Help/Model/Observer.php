<?php 

class Ccc_Help_Model_Observer 
{
    public function firstEvent(Varien_Event_Observer $observer)
    {
        $data = 50;
        $processedData = $data/2;

        // Add the processed data to the event observer
        $observer->getEvent()->setData('first_event_data', $processedData);

        echo "First event processed and data stored: " . $processedData;
    } 

    public function secondEvent(Varien_Event_Observer $observer)
    {
        // Retrieve the data from the event observer
        $dataFromFirstEvent = $observer->getEvent()->getData('first_event_data');

        // Use the data in the second event
        // echo "Handling second event with data: " . $dataFromFirstEvent;
        print_r($dataFromFirstEvent);
    }

    public function testEvent(Varien_Event_Observer $observer)
    {
        $url = 'http://127.0.0.1/magento/root-script/test.php';
        Mage::app()->getResponse()->setRedirect($url);
        Mage::app()->getResponse()->sendResponse();
    }
}