<?php
class Ccc_Promotions_Block_Adminhtml_Report extends Mage_Adminhtml_Block_Widget_Container
{
    public function __construct()
    {
        $this->_headerText = Mage::helper('promotions')->__('Report');
        parent::__construct();
    }

    public function getPromotions()
    {
        $collection = Mage::getModel('promotions/promotions')->getCollection();
        $collection->addFieldToSelect('tag_name');
        return $collection->getData();
    }

    public function getActiveTag()
    {
        if (Mage::getStoreConfig('promotions/general/enable_select')==1) {
            return true;
        } else {
            return false;
        }
    }
}