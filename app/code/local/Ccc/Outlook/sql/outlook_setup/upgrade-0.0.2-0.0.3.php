<?php
$installer = $this;
$installer->startSetup();

$connection = $installer->getConnection();

$tableName = $installer->getTable('ccc_configuration');

$connection->dropColumn($tableName, 'api_url');

$connection->changeColumn(
    $tableName,
    'username',
    'client_id',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length'   => 255,
        'nullable' => false,
        'comment'  => 'Client ID'
    )
);

$connection->changeColumn(
    $tableName,
    'password',
    'secret_value',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length'   => 255,
        'nullable' => false,
        'comment'  => 'Client Secret Value'
    )
);

$connection->changeColumn(
    $tableName,
    'api_key',
    'access_token',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable' => true,
        'comment'  => 'Access Token'
    )
);

$installer->endSetup();
