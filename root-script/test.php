<?php
require_once ('../app/Mage.php');
Mage::app();
echo "<pre>";


// Mage::getModel('repricer/observer')->SaveRepricerMatching(); 
// Mage::getModel('repricer/observer')->uploadCsv(); 
// Mage::getModel('repricer/observer')->downloadCsv(); 



// $allCompetitors = Mage::getModel('repricer/competitors')
//     ->getCollection();
//     // ->getData();
// $result = [];
// foreach ($allCompetitors as $competitor) {
//     $result[$competitor->getId()] = $competitor->getName();
// }
// print_r($result);


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
// echo 3838;

// echo get_class(Mage::getModel('reportmanager/reportmanager')->getCollection());
// $collection = Mage::getModel('promotions/promotions')->getCollection();
// foreach ($collection as $data) {
//     print_r($data->getTagName());
// }


