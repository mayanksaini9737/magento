<?php 

class Ccc_Filetransfer_Model_Config extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('filetransfer/config');
    }


    public function fetchFiles()
    {
        $ftpModel = Mage::getModel('filetransfer/ftp');
        $ftpModel->setConfigObj($this);
        $allFiles = $ftpModel->getAllFile();
        $fileModel = Mage::getModel('filetransfer/files')->setConfigObj($this);
        $fileModel->saveFiles($allFiles);
    }
}