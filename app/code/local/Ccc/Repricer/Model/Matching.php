<?php

class Ccc_Repricer_Model_Matching extends Mage_Core_Model_Abstract
{
    public const CONST_REASON_NO_MATCH = 1;
    public const CONST_REASON_NO_OUT_OF_STOCK = 2;
    public const CONST_REASON_NOT_AVAILABLE = 3;
    public const CONST_REASON_WRONG_MATCH = 4;
    public const CONST_REASON_ACTIVE = 5;
    protected function _construct()
    {
        $this->_init('repricer/matching');
    }

    public function getReasonOptionArray()
    {
        return array(
            1   => Mage::helper('repricer')->__('No Match'),
            2   => Mage::helper('repricer')->__('Out of Stock'),
            3   => Mage::helper('repricer')->__('Not Available'),
            4   => Mage::helper('repricer')->__('Wrong Match'),
            5   => Mage::helper('repricer')->__('Active'),
        );
    }
}