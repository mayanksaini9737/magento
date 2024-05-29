<?php

class Ccc_Reportmanager_Model_Reportmanager extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('reportmanager/reportmanager');
    }

    public function getStatusArray()
    {
        return [
            '1' => 'Yes',
            '2' => 'No',
        ];
    }
    public function getReportType()
    {
        return [
            '1' => 'Product',
            '2' => 'Customer',
        ];
    }

    public function toOptionArray()
    {
        return array(
            array('value' => '1', 'label' => 'Yes'),
            array('value' => '2', 'label' => 'No'),
        );
    }
}