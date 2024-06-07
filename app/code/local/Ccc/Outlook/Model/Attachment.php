<?php 

class Ccc_Outlook_Model_Attachment extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('outlook/attachment');
    }

    public function getUniqueName($path, $fileName)
    {
        
        $filePath = $path . DS . $fileName;
        if (!file_exists($filePath)) {
            return $fileName; 
        }

        $fileInfo = pathinfo($fileName);
        $name = $fileInfo['filename'];
        $extension = isset($fileInfo['extension']) ? '.' . $fileInfo['extension'] : '';
        $counter = 1;

        do {
            $newFileName = $name . '_' . $counter . $extension;
            $filePath = $path . DS . $newFileName;
            $counter++;
        } while (file_exists($filePath));

        return $newFileName;
    }
}