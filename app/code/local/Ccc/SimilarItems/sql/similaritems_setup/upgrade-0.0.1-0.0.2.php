<?php

$installer = $this;
$installer->startSetup();

$attributeCode = 'has_similar_item';
$attributeLabel = 'Has Similar Item';

$data = array(
    'attribute_code'  => $attributeCode,
    'type'            => 'int', 
    'input'           => 'select',
    'label'           => $attributeLabel,
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
        'value' => array(
            'similar_item_1' => array('Yes'),
            'similar_item_2' => array('No')
        )
    )
);

$installer->addAttribute('catalog_product', $attributeCode, $data);

$installer->endSetup();

