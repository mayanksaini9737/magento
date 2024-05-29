<?php

$installer = $this;
$installer->startSetup();

$tableName = $installer->getTable('ccc_reportmanager');
$connection = $installer->getConnection();

$connection->addKey($tableName,
    'UNQ_FILTER_FOR_USER', 
    array('user_id', 'report_type'), 
    'unique'
);

$installer->endSetup();
