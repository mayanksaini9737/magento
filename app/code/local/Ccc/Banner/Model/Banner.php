<?php

class Ccc_Banner_Model_Banner extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('banner/banner');
    }

    public function toOptionArray()
    {
        return array(
            array('value'=>'2', 'label'=>'Disable'),
            array('value'=>'1', 'label'=>'Enable'),
        );
    }

}