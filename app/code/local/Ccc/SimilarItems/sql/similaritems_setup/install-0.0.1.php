<?php

$installer = $this;
$installer->startSetup();


$table = $installer->getConnection()
    ->newTable($installer->getTable('ccc_similar_items'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true, 
        'nullable'  => false,
        'primary'   => true,
    ), 'Id')
    ->addColumn('main_product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true, 
        'nullable'  => false,
    ), 'Main Product Id')
    ->addColumn('similar_product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true, 
        'nullable'  => false,
    ), 'Similar Product Id')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => false,
        'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT,
    ), 'Created At')
    ->addColumn('is_deleted', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable' => false,
        'default' => '2',
    ), 'Is Deleted')
    ->addColumn('deleted_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => true, 
    ), 'Deleted At')
    ->setComment('Similar Items Table'); 

$installer->getConnection()->createTable($table);
$installer->endSetup();
