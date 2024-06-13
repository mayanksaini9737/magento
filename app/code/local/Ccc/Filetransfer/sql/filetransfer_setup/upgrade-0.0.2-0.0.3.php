<?php

$installer = $this;

$installer->startSetup();

$installer->getConnection()
    ->addColumn(
        $installer->getTable('ccc_filetransfer_config'),
        'remote_path',
        array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'nullable' => true,
            'comment'  => 'Remote Path',
        )
    );
    
$installer->endSetup();