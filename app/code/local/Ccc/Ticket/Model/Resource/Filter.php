<?php 
class Ccc_Ticket_Model_Resource_Filter extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('ticket/filter', 'filter_id');
    }
}