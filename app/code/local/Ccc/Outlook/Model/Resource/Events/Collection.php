<?php 
class Ccc_Outlook_Model_Resource_Events_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('outlook/events');
    }
}