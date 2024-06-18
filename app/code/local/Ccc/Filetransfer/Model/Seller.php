<?php 

class Ccc_Filetransfer_Model_Seller extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('filetransfer/seller');
    }

    public function saveSeller($xmlSellerData){

        $sellerCollection = $this->getCollection()
            ->addFieldToSelect('entity_id')->addFieldToSelect('partnumber');
        $sellerCollectionData = $sellerCollection->getData();

        $sellerPartnumber = [];
        foreach ($sellerCollection as $_seller) {
            $sellerPartnumber[] = $_seller->getPartnumber();
        }

        $newSellerData = [];
        foreach ($xmlSellerData as $_seller){
            $sellerModel = Mage::getModel('filetransfer/seller')->load($_seller['partnumber'], 'partnumber');

            if ($sellerModel->getId()) {
                $sellerModel->addData($_seller)->save();
            } else {
                $sellerModel->setData($_seller)->save();
            }
            if (!in_array($_seller['partnumber'], $sellerPartnumber)){
                $newSellerData[] = [
                    'entity_id' => $sellerModel->getEntityId(),
                    'partnumber' => $_seller['partnumber'],
                ];
            }
        }
        // echo 'ins';
        // die;

        $newSellerModel = Mage::getModel('filetransfer/newseller');
        $newSellerModel->saveNewSeller($newSellerData);

        $xmlPartNumber = array_column($xmlSellerData, 'partnumber');
        $discSellerModel = Mage::getModel('filetransfer/discseller');

        $discSellerModel->deleteDiscSeller($xmlPartNumber);
        $discSellerModel->saveDiscSeller($sellerCollectionData, $xmlPartNumber);

    }


    public function statusArray()
    {
        return [
            'new' => 'New',
            'discontinue' => 'Discontinue',
            'regular' => 'Regular'
        ];
    }
}