<?php
$installer = $this;
$installer->startSetup();

$tableName = $installer->getTable('ccc_demo');
if ($installer->getConnection()->isTableExists($tableName) !== true) {
    $table = $installer->getConnection()
        ->newTable($tableName)
        ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
        ), 'demo_id')
        ->addColumn('name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
            'nullable' => false,
        ), 'name')
        ->addColumn('text_column', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
            'nullable' => false,
            'default' => 'NA',
        ), 'Text Column')
        ->addColumn('int_column', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable' => false,
        ), 'Integer Column')
        ->addColumn('decimal_column', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(
            'nullable' => false,
        ), 'Decimal Column')
        ->addColumn('datetime_column', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
            'nullable' => false,
        ), 'Datetime Column')
        ->addColumn('timestamp_column', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
            'nullable' => false,
            'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT,
        ), 'Timestamp Column')
        ->addColumn('updated_date', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
            'nullable' => false,
            'default'  => Varien_Db_Ddl_Table::TIMESTAMP_INIT_UPDATE,
            'comment'  => 'Updated At'
        ), 'Updated Date')
        ->addColumn('status_column', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
            'nullable' => false,
            'default' => 2,
        ), 'Status Column')
        // ->addColumn('boolean_column', Varien_Db_Ddl_Table::TYPE_BOOLEAN, null, array(
        //     'nullable' => false,
        //     'default' => 0,
        // ), 'Boolean Column')
        // ->addColumn('blob_column', Varien_Db_Ddl_Table::TYPE_BLOB, null, array(
        //     'nullable' => false,
        // ), 'Blob Column')
        ->addColumn('longtext_column', Varien_Db_Ddl_Table::TYPE_LONGVARCHAR, null, array(
            'nullable' => false,
        ), 'Longtext Column')
        ->addColumn('date_column', Varien_Db_Ddl_Table::TYPE_DATE, null, array(
            'nullable' => false,
        ), 'Date Column')
        ->setComment('Demo Table');
    $installer->getConnection()->createTable($table);
}

$installer->endSetup();