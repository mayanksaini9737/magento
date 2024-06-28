<?php
class Ccc_SimilarItems_Model_Similaritems extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('similarItems/similarItems');
    }

    public function getDeleteArray()
    {
        return [
            '1' => 'Yes',
            '2' => 'No'
        ];
    }
    public function getProductArray()
    {
        $productCollection = Mage::getModel('catalog/product')->getCollection();
        $productArray = array();

        foreach ($productCollection as $product) {
            $productId = $product->getId();
            $sku = $product->getSku();
            $productArray[$productId] = $sku;
        }

        return $productArray;
    }

    protected function _afterSave()
    {
        $this->load($this->getId()); 

        if ($this->getIsDeleted() > 1) {
            $mainProductId = $this->getMainProductId();
            Mage::getModel('catalog/product')
                ->load($mainProductId)
                ->setHasSimilarItem(240) 
                ->save();
        } else {
            $mainProductId = $this->getMainProductId();
            Mage::getModel('catalog/product')
                ->load($mainProductId)
                ->setHasSimilarItem(241) 
                ->save();
        }
        return parent::_afterSave();
    }

    public function toOptionArray()
    {
        return [
            '1' => 'Yes',
            '2' => 'No'
        ];
    }
}
