<?php 
class Ccc_Filetransfer_Model_Resource_Seller extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('filetransfer/seller', 'entity_id');
    }
}