<?php

$installer = $this;

$installer->startSetup();

/**
 * Create table 'ccc/banner'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('banner'))
    ->addColumn('banner_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Banner Id')
    ->addColumn('banner_image', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => false,
    ), 'Banner Image')
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_TINYINT, null, array(
        'nullable'  => false,
        'default'   => '1',
    ), 'Banner Status')
    ->addColumn('show_on', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
    ), 'Show On')
    ->setComment('Banner Table');
$installer->getConnection()->createTable($table);
$installer->endSetup();
