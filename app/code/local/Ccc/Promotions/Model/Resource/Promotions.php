<?php 
class Ccc_Promotions_Model_Resource_Promotions extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('promotions/promotions', 'id');
    }
}