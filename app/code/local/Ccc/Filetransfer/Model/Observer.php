<?php 

class Ccc_Filetransfer_Model_Observer
{
    public function ftpFetch()
    {
        $configCollection = Mage::getModel('filetransfer/config')->getCollection();
        foreach($configCollection as $_config){
            $_config->fetchFiles();
        }
    }
}