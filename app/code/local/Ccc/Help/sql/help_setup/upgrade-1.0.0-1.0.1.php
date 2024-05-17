<?php 

$installer = $this;
$installer->startSetup();

$tableName = $installer->getTable('ccc_help_contact');
$connection = $installer->getConnection();

$connection->addColumn($tableName, 'help', array(
    'type'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'nullable' => false,
    'default'  => 2,
    'comment'  => 'Add Column'
));

$installer->endSetup();