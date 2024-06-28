<?php
class Ccc_SimilarItems_Block_Adminhtml_Item extends Mage_Adminhtml_Block_Template
{
    public function __construct()
    {
        $this->setTemplate("similarItems/item.phtml");
        $this->_headerText = Mage::helper('similarItems')->__('Item');
        parent::__construct();
    }

    public function getProduct()
    {
        $productId = $this->getRequest()->getParam('id');
        if ($productId){
            return Mage::getModel('catalog/product')->load($productId);
        }
        return false;
    }

    public function similarItemButton()
    {
        return Mage::getStoreConfig('similarItems/general/enable_select');
    }
}