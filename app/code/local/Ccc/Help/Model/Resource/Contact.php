<?php 
class Ccc_Help_Model_Resource_Contact extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('help/contact', 'contact_id');
    }
}