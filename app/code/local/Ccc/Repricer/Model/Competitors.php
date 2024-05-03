<?php

class Ccc_Repricer_Model_Competitors extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('repricer/competitors');
    }

    public function getCompetitors()
    {
        $allCompetitors = $this->getCollection();
        $result = [];
        foreach ($allCompetitors as $competitor) {
            $result[$competitor->getCompetitorId()] = $competitor->getName();
        }
        return $result;
    }
}