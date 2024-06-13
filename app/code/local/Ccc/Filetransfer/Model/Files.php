<?php 

class Ccc_Filetransfer_Model_Files extends Mage_Core_Model_Abstract
{
    protected $_config = null;
    protected function _construct()
    {
        $this->_init('filetransfer/files');
    }

    public function setConfigObj($obj)
    {
        $this->_config = $obj;
        return $this;
    }

    public function saveFiles($allFiles)
    {
        foreach ($allFiles as $_file) {
            $this->setData($_file);
            $this->save();
        }
    }
}