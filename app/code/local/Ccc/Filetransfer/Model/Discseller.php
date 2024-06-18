<?php

class Ccc_Filetransfer_Model_Discseller extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('filetransfer/discseller');
    }

    public function deleteDiscSeller($xmlPartNumber)
    {
        $discSellerCollection = $this->getCollection()->addFieldToSelect('partnumber');
        $discPartnumber = [];
        foreach ($discSellerCollection as $_discSeller) {
            $discPartnumber[] = $_discSeller->getPartnumber();
        }
        $discSellerPartNumber = array_intersect($discPartnumber, $xmlPartNumber);
        // echo "<pre>";
        // print_r($discSellerPartNumber);
        // die;
        if(!empty($discSellerPartNumber)){
            $model = Mage::getSingleton('core/resource')->getConnection('core_write');
            $discSellerTable = Mage::getSingleton('core/resource')->getTableName('filetransfer/discseller');
            $where = $model->quoteInto('partnumber IN (?)', $discSellerPartNumber);
            $model->delete($discSellerTable, $where);
        }
    }

    public function saveDiscSeller($sellerCollectionData, $xmlPartNumber)
    {
        $discontinueArray = array_filter($sellerCollectionData, function ($item) use ($xmlPartNumber) {
            return !in_array($item['partnumber'], $xmlPartNumber);
        }); 
        // echo "<pre>";

        // print_r($sellerCollectionData);
        // print_r($discontinueArray);
        // die;

        if (!empty($discontinueArray)) {
            $model = Mage::getSingleton('core/resource')->getConnection('core_write');
            $discSellerTable = Mage::getSingleton('core/resource')->getTableName('filetransfer/discseller');
            $model->insertOnDuplicate($discSellerTable, $discontinueArray);
        }
    }
}