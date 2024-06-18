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

    public function seller(){
        $filepath = Mage::getBaseDir('var') . DS . 'filetransfer' . DS . 'seller'. DS . 'second.xml';
        $xml = simplexml_load_file($filepath);
        $data_rows = Mage::helper('filetransfer')->getRows();

        $xmlSellerData = [];
        foreach ($xml->xpath('//items/item') as $item) {
            $data = [];
            foreach ($data_rows as $key => $row) {
                $pathParts = explode('.', $row['path']);
                $attribute = $row['attribute'];
                $currentElement = $item;
                for ($i = 2; $i < count($pathParts); $i++) {
                    $currentElement = $currentElement->{$pathParts[$i]};
                }
                $data[$key] = (string) $currentElement[$attribute];
            }
            $xmlSellerData[] = $data;
        }
        

        $sellerModel = Mage::getModel('filetransfer/seller');
        $sellerModel->saveSeller($xmlSellerData);
        echo 'done';
    }
}