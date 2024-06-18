<?php 

class Ccc_Reportmanager_Adminhtml_ReportmanagerController extends Mage_Adminhtml_Controller_Action
{

    protected function _initAction()
    {
        $this->loadLayout();
        return $this;
    }
    public function indexAction()
    {
        $this->_title($this->__('Report Manager'))->_title($this->__('System'));
        $this->_initAction();
        $this->renderLayout();
    }
    
    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            try {
                $model = Mage::getModel('reportmanager/reportmanager');
                $model->load($id);
                $model->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('reportmanager')->__('The Reportmanager one row has been deleted.')
                );
                $this->_redirect('*/*/');
                return;

            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/index', array('id' => $id));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('reportmanager')->__('Unable to find a Reportmanager to delete.'));
        $this->_redirect('*/*/');
    }

    public function saveReportAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $data =$this->getRequest()->getPost('filterData');
            print_r($data);

            $user = Mage::getSingleton('admin/session')->getUser();
            $userId = $user->getId();

            $reportManagerModel = Mage::getModel('reportmanager/reportmanager');
            $collection = $reportManagerModel
                ->getCollection()
                ->addFieldToFilter('user_id', $userId)
                ->addFieldToFilter('report_type', 1)
                ->getFirstItem();

            $filterId = $collection->getId();
            
            if ($filterId){
                $reportManagerModel->load($filterId);
                $reportManagerModel->setFilterData($data);
            } else {
                $dataToSave = array(
                    'filter_data' => $data,
                    'report_type' => 1,
                    'user_id' => $userId,
                );
                $reportManagerModel->setData($dataToSave);
            }

            $reportManagerModel->save();
            
            $response = array(
                'success' => true,
                'message' => 'filter saved successfully'
            );

            $this->getResponse()->setHeader('Content-type', 'application/json');
            $this->getResponse()->setBody(json_encode($response));
        }
    }
    public function massActiveAction()
    {
        $reportGridIds = $this->getRequest()->getParam('reportmanager');
        $status = $this->getRequest()->getParam('is_active');

        if (!is_array($reportGridIds)) {
            $reportGridIds = array($reportGridIds);
        }

        try {
            foreach ($reportGridIds as $reportGridId) {
                $reportmanager = Mage::getModel('reportmanager/reportmanager')->load($reportGridId);
                if ($reportmanager->getIsActive() != $status) {
                    $reportmanager->setIsActive($status)->save();
                }
            }
            $this->_getSession()->addSuccess(
                $this->__('Total of %d record(s) have been updated.', count($reportGridIds))
            );

        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }

        $this->_redirect('*/*/index');
    }

    protected function _isAllowed()
    {
        $action = strtolower($this->getRequest()->getActionName());
        $aclResource = '';
        switch ($action) {
            case 'index':
                $aclResource = 'catalog/promotions/manage_promotions/actions/show'; 
                break;
        }
        return Mage::getSingleton('admin/session')->isAllowed($aclResource);
    }
}