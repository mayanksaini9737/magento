<?php 
$installer = $this;
$installer->startSetup();

$tableName = $installer->getTable('ccc_repricer_matching');
if ($installer->getConnection()->isTableExists($tableName)) {
    Mage::log("Table $tableName already exists", null, 'ccc_repricer_matching.log');
    return;
}


$table = $installer->getConnection()
    ->newTable($installer->getTable('ccc_repricer_matching'))
    ->addColumn('repricer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'nullable' => false,
        'primary' => true,
    ), 'Repricer Id')
    ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
    ), 'Product Id')
    ->addColumn('competitor_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
    ), 'Competitor Id')
    ->addColumn('competitor_url', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => false,
    ), 'Competitor URL')
    ->addColumn('competitor_sku', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'=>false,
    ), 'Competitor SKU')
    ->addColumn('competitor_price', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(
        'nullable'=>false,
    ), 'Competitor Price')
    ->addColumn('reason', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
        'nullable'=>false,
        'default' => 0,
    ), 'Reason')
    ->addColumn('updated_date', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT_UPDATE,
    ), 'Competitor Updated Date')
    ->setComment('CCC Repricer Matching Table');
$installer->getConnection()->createTable($table);

$installer->endSetup();
