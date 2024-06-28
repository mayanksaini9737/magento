<?php 
require_once('mage/adminhtml/controllers/catalog/productController.php');
class Ccc_Reportmanager_Adminhtml_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController
{
    public function saveReportAction()
    {
        $filter = $this->getRequest()->getParam('filterData');
        $filterArray = json_decode($filter, true);

        $user = Mage::getSingleton('admin/session')->getUser();
        $userId = $user->getId();

        print_r($filterArray);
        echo $userId;
        die;
        
        $model = Mage::getModel('reportmanager/reportmanager');
        $model->setUserId($userId);
        $model->setFilters(json_encode($filterArray));  // Convert the filters to JSON
        $model->setCreatedAt(now());

        try {
            $model->save();
            Mage::getSingleton('adminhtml/session')->addSuccess('Report saved successfully.');
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError('Error saving report.');
        }

        $this->_redirectReferer();
    }
}