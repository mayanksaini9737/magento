<?php 

$installer = $this;

$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('ccc_promotions'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Id')
    ->addColumn('tag_name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => false,
    ), 'Tag Name')
    ->addColumn('percentage', Varien_Db_Ddl_Table::TYPE_DECIMAL, '10,2', array(
        'nullable'  => false,
    ), 'Percentage')
    ->addColumn('priority', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
        'nullable'=>false,
        'default' => 1,
    ), 'Priority')
    ->addColumn('is_active', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable' => false,
        'default' => '1',
    ), 'Is Active')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => false,
        'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT,
    ), 'Created At')
    ->addColumn('updated_date', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => false,
        'default'  => Varien_Db_Ddl_Table::TIMESTAMP_INIT_UPDATE,
    ), 'Updated Date');
$installer->getConnection()->createTable($table);
$installer->endSetup();