<?php 
    
$installer = $this;

$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('ccc_filetransfer_files'))
    ->addColumn('file_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'File Id')
    ->addColumn('config_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
        'nullable'  => false,
    ), 'Config Id')
    ->addColumn('host', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => false,
    ), 'Host')
    ->addColumn('filename', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => false,
    ), 'Filename')
    ->addColumn('modified_date', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
        'nullable' => true,
    ), 'Modified Date')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => false,
        'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT,
    ), 'Created At')
    ->addForeignKey(
        $installer->getFkName(
            'ccc_filetransfer_files',           
            'config_id',                        // Your column name
            'ccc_filetransfer_config',          // Referenced table name
            'config_id'                         // Referenced column name
        ),
        'config_id',
        $installer->getTable('ccc_filetransfer_config'),
        'config_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE   // What to do on deletion of the referenced row
    )
    ->setComment('Filetransfer Files Table');
$installer->getConnection()->createTable($table);
$installer->endSetup();