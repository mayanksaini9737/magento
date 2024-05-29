<?php

class Ccc_Promotions_Block_Adminhtml_Report_Table extends Mage_Adminhtml_Block_Template
{
    protected $html;
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->setTemplate('promotions/table.phtml');
        return $this;
    }

    public function getProductsData()
    {
        $Data = (Mage::helper('core')->jsonDecode($this->getRequest()->getPost('edited_data')));
        $selectedValue = $Data['selectedValue'];

        $attribute = Mage::getSingleton('eav/config')->getAttribute('catalog_product', 'active_tag');

        $attributeOptions = $attribute->getSource()->getAllOptions(false);

        $activeTagValue = null;

        foreach ($attributeOptions as $option) {
            if ($option['label'] == $selectedValue) {
                $activeTagValue = $option['value'];
                break;
            }
        }

        $products = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect(array('name', 'price', 'special_price'))
            ->addFieldToFilter('active_tag', $activeTagValue);

        $productsData = [];
        foreach ($products as $_product) {
            $productsData[] = array(
                'name' => $_product->getName(),
                'sku' => $_product->getSku(),
                'price' => $_product->getPrice(),
                'special_price' => $_product->getSpecialPrice(),
            );
        }
        return $productsData;
    }
}