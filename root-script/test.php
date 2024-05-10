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

echo Mage::getStoreConfig('repricer/general/enable_text');