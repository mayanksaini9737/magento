<?php

$installer = $this;

$installer->startSetup();

$installer->getConnection()
    ->addColumn(
        $installer->getTable('ccc_outlook_email'),
        'outlook_id',
        array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'nullable' => true,
            'comment'  => 'Outlook ID',
        )
    );

$table = $installer->getConnection()
    ->newTable($installer->getTable('ccc_outlook_attachment'))
    ->addColumn('attachment_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
        'unsigned'  => true,
    ), 'Attachment ID')
    ->addColumn('email_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
        'nullable'  => false,
        'unsigned'  => true,
    ), 'Email ID')
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_TEXT, 255 , array(
        'nullable'  => false,
    ), 'File Name')
    ->setComment('Outlook Email Attachments Table');

$installer->getConnection()->createTable($table);

$installer->endSetup();
