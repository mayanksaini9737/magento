<?php

$installer = $this;
$installer->startSetup();

$attributeCode1 = 'active_tag';
$attributeLabel1 = 'activeTag';

$connection = $installer->getConnection();
$select = $connection->select()
    ->from($installer->getTable('ccc_promotions'), array('id', 'tag_name'));

$options = $connection->fetchAll($select);

$optionArray = array();
foreach ($options as $option) {
    $optionArray['option' . $option['id']] = array(0 => $option['tag_name']);
}

$data1 = array(
    'attribute_code'  => $attributeCode1,
    'type'            => 'int', 
    'input'           => 'select',
    'label'           => $attributeLabel1,
    'source'          => 'eav/entity_attribute_source_table',
    'global'          => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'required'        => false,
    'configurable'    => false,
    'apply_to'        => '', 
    'visible_on_front'=> true,
    'user_defined'    => true,
    'searchable'      => false,
    'filterable'      => false,
    'comparable'      => false,
    'used_for_promo_rules' => false,
    'is_html_allowed_on_front' => true,
    'option' => array(
        'value' => $optionArray
    )
);

$installer->addAttribute('catalog_product', $attributeCode1, $data1);

$attributeCode2 = 'special_price';
$attributeLabel2 = 'Special Price';

$data2 = array(
    'attribute_code'  => $attributeCode2,
    'type'            => 'decimal', 
    'input'           => 'price', 
    'label'           => $attributeLabel2, 
    'global'          => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
    'required'        => false,
    'configurable'    => false,
    'apply_to'        => '', 
    'visible_on_front'=> true,
    'user_defined'    => true,
    'searchable'      => false,
    'filterable'      => false,
    'comparable'      => false,
    'used_for_promo_rules' => true,
    'is_html_allowed_on_front' => true,
);

$installer->addAttribute('catalog_product', $attributeCode2, $data2);

$installer->endSetup();
