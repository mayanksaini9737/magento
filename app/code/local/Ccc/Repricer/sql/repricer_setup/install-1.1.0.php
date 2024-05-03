<?php

$installer = $this;
$installer->startSetup();

$tableName = $installer->getTable('ccc_repricer_competitor');
if ($installer->getConnection()->isTableExists($tableName)) {
    Mage::log("Table $tableName already exists", null, 'ccc_repricer_competitor.log');
    return;
}

$table = $installer->getConnection()
    ->newTable($installer->getTable('ccc_repricer_competitor'))
    ->addColumn('competitor_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'nullable' => false,
        'primary' => true,
    ), 'Competitor Id')
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => false,
    ), 'competitor Name')
    ->addColumn('url', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => false,
    ), 'competitor url')
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable' => false,
        'default' => '1',
    ), 'competitor Status')
    ->addColumn('filename', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
    ), 'Competitor File Name')
    ->addColumn('created_date', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => false,
        'default'  => Varien_Db_Ddl_Table::TIMESTAMP_INIT,
        'comment'  => 'Created At'
    ), 'Competitor Created Date')
    
    ->addColumn('updated_date', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => false,
        'default'  => Varien_Db_Ddl_Table::TIMESTAMP_INIT_UPDATE,
        'comment'  => 'Updated At'
    ), 'Competitor Updated Date ')
    ->setComment('CCC Repricer Competitor Table');
$installer->getConnection()->createTable($table);

$installer->endSetup();



