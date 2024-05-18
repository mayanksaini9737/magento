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
echo 7438;
echo get_class(Mage::getBlockSingleton('help/contact'));
Mage::dispatchEvent('event_practice',[]);