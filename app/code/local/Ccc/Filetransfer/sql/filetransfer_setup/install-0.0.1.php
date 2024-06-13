<?php 
    
$installer = $this;

$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('ccc_filetransfer_config'))
    ->addColumn('config_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Config Id')
    ->addColumn('user_id', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => false,
    ), 'User Id')
    ->addColumn('password', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => false,
    ), 'Password')
    ->addColumn('host', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
    ), 'Host')
    ->addColumn('port', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
    ), 'Port')
    ->setComment('Filetransfer Config Table');
$installer->getConnection()->createTable($table);
$installer->endSetup();