<?php 
class Ccc_Filetransfer_Model_Resource_Newseller_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        parent::_construct();
        $this->_init('filetransfer/newseller');
    }
}
