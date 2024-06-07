<?php
$installer = $this;
$installer->startSetup();

$tableName = $installer->getTable('outlook/configuration');

$installer->getConnection()
->addColumn($tableName,'read_datetime', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
    'nullable' => true,
), 'Last Read Time');
    
$installer->endSetup();

?>