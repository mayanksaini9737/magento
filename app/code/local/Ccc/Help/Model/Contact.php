<?php

class Ccc_Help_Model_Contact extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('help/contact');
    }

    public function getStatusOptionArray()
    {
        return [
            '1' => 'Compelete',
            '2' => 'Pending'
        ];
    }
    public function getMediumArray()
    {
        return [
            '1' => 'Email',
            '2' => 'Number'
        ];
    }

    // public function toOptionArray()
    // {
    //     return  [
    //         '1' => 'Normal',
    //         '2' => 'Special'
    //     ];
    // }
    public function toOptionArray()
    {
        return array(
            array('value' => '1', 'label' => 'Normal'),
            array('value' => '2', 'label' => 'Special'),
        );
    }
}