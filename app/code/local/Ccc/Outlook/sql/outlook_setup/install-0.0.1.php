<?php 
    
$installer = $this;

$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('ccc_configuration'))
    ->addColumn('config_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Configuration Id')
    ->addColumn('username', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => false,
    ), 'Username')
    ->addColumn('password', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => false,
    ), 'Password')
    ->addColumn('api_key', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
    ), 'Api Key')
    ->addColumn('api_url', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
    ), 'Api URL')
    ->addColumn('is_active', Varien_Db_Ddl_Table::TYPE_SMALLINT, 6, array(
        'nullable' => false,    
        'default' => 1,
    ), 'Is active')
    ->setComment('Configuration Table');
$installer->getConnection()->createTable($table);
$installer->endSetup();