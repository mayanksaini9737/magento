<?php 

class Ccc_Filetransfer_Model_Newseller extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('filetransfer/newseller');
    }

    public function saveNewSeller($newSellerData)
    {
        if (!empty($newSellerData)) {
            $model = Mage::getSingleton('core/resource')->getConnection('core_write');
            $newSellerTable = Mage::getSingleton('core/resource')->getTableName('filetransfer/newseller');
            $model->insertOnDuplicate($newSellerTable, $newSellerData);
        }
    }
}