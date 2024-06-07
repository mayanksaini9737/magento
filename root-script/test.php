<?php
require_once ('../app/Mage.php');
Mage::app();
echo "<pre>";



// getConfigValue - System.xml
// echo Mage::getStoreConfig('repricer/general/enable_text');

// $product = Mage::getModel('catalog/product')->load(439);
// print_r($product->getAttributeText('test'));
// echo get_class(Mage::getBlockSingleton('help/contact'));
// Mage::dispatchEvent('event_practice',[]);
// Mage::dispatchEvent('event_practice2',[]);

// $pramotion = Mage::getModel('promotions/promotions')->load('Friday Sale', 'tag_name');
// print_r($pramotion->getPercentage());
// $pramotion = Mage::getModel('catalog/product')->getCollection()->addFieldToFilter('entity_id', 541)
//     ->addAttributeToSelect('sold_count');
// // print_r($pramotion->getData());
// foreach ($pramotion as $product) {
//     print_r($product->getSoldCount());
// }

// Mage::getModel('outlook/observer')->fetch(); 


// $eventModel = Mage::getModel('outlook/events');

// $eventCollection = $eventModel->getCollection()->addFieldToFilter('config_id', 3)->addFieldToSelect('group_id');
// $eventCollection->getSelect()->group('group_id');
// // $eventCollection->addFieldToFilter('group_id', '5');
// // echo $eventCollection->getSize();
// // print_r($eventCollection->getData());
// foreach ($eventCollection as $_event){
//     $newId = $_event->getGroupId();
//     $collection = $eventModel->getCollection()->addFieldToFilter('group_id', $newId);
//     print_r($collection->getData());
// }
// die;
// $groupIds = [];
// foreach ($eventCollection as $_event){
//     $newId = $_event->getGroupId();
//     if (!in_array($newId, $groupIds)){
//         $groupIds[] = $newId;
//     }
// }

// foreach ($groupIds as $_id) {
//     $eventModel->checkGroupCondition($_id);
// }


// Mage::getModel('outlook/observer')->fetch(); 
echo 212;