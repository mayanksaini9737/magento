<?php

$installer = $this;
$installer->startSetup();

$tableName = $installer->getTable('ccc_repricer_matching');
$connection = $installer->getConnection();

$connection->addKey($tableName,
    'UNQ_PRODUCT_COMPETITOR', 
    array('product_id', 'competitor_id'), 
    'unique'
);

$installer->endSetup();
