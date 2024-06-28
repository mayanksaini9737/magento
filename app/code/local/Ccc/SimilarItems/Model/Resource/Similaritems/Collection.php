<?php 
class Ccc_SimilarItems_Model_Resource_Similaritems_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('similarItems/similarItems');
    }
}