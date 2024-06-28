<?php 
    
class Ccc_SimilarItems_Adminhtml_SimilarItemsController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout();
        return $this;
    }
    public function indexAction()
    {
        $this->_title($this->__('similarItems'))->_title($this->__('Catalog'));
        $this->_initAction();
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $this->_title($this->__('Manage Similar Items'));

        $id = $this->getRequest()->getParam('id');
        
        $model = Mage::getModel('similarItems/similarItems');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('similarItems')->__('This item no longer exists.')
                );
                $this->_redirect('*/*/');
                return;
            }
        }

        $this->_title($model->getId() ? $model->getTitle() : $this->__('New Similar Item'));

        $data = Mage::getSingleton('adminhtml/session')->getFormData();
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register('similaritem', $model);

        $this->_initAction();
            
        $this->renderLayout();
    }


    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $model = Mage::getModel('similarItems/similarItems');

            if ($id = $this->getRequest()->getParam('id')) {
                $model->load($id);
            }

            $model->setData($data);

            try {
                $model->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('promotions')->__('The Similar Item has been saved.')
                );

                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array(
                        'id' => $model->getId(), 
                        '_current' => true)
                    );
                    return;
                }

                $this->_redirect('*/*/');
                return;

            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addException($e,
                    Mage::helper('similarItems')
                        ->__('An error occurred while saving the Promotion.')
                );
            }

            $this->_getSession()->setFormData($data);
            $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            return;
        }
    }

    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            try {
                $currentDate = date("Y/m/d H:i:s");
                $model = Mage::getModel('similarItems/similarItems');
                $model->load($id);
                $model->setIsDeleted(1)
                    ->setDeletedAt($currentDate)
                    ->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('similarItems')->__('The Item has been deleted.')
                );
                $this->_redirect('*/*/');
                return;

            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/index', array('id' => $id));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')
            ->addError(Mage::helper('reportmanager')->__('Unable to find a Item to delete.'));
        $this->_redirect('*/*/');
    }

    public function massDeleteAction()
    {
        $similarItemIds = $this->getRequest()->getParam('ItemIds');
        $delete = $this->getRequest()->getParam('is_deleted');
        $currentDate = date("Y/m/d H:i:s"); 

        if (!is_array($similarItemIds)) {
            $similarItemIds = array($similarItemIds);
        }

        try {
            foreach ($similarItemIds as $item) {
                $similarItem = Mage::getModel('similarItems/similarItems')->load($item);
                $similarItem->setIsDeleted($delete);
                if ($delete == 1){
                    $similarItem->setDeletedAt($currentDate);
                } else {
                    $similarItem->setDeletedAt(null);
                }
                $similarItem->save();
            }
            $this->_getSession()->addSuccess(
                $this->__('Total of %d record(s) have been updated.', count($similarItemIds))
            );

        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }

        $this->_redirect('*/*/index');
    }

    public function reportAction()
    {
        $this->_title($this->__('Report'))->_title($this->__('Similar Items'))
            ->_title($this->__('Catalog'));
        $this->_initAction();
        $this->renderLayout();
    }

    public function productAction(){
        if ($this->getRequest()->isXmlHttpRequest()){
            $block = $this->getLayout()->createBlock('similarItems/adminhtml_item');
            $this->getResponse()->setBody($block->toHtml());
        }
    }

    public function sameItemsAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()){
            $productId = $this->getRequest()->getParam('id');
            $collection = Mage::getModel('similarItems/similarItems')->getCollection()
                ->addFieldToFilter('main_product_id', $productId);

            $products = [];
            foreach ($collection as $item){
                if ($item->getIsDeleted() == 2){
                    $products[] = $item->getSimilarProductId();
                }
            }
            Mage::register('similarProducts', $products);

            $block = $this->getLayout()->createBlock('similarItems/adminhtml_products_grid');
            $this->getResponse()->setBody($block->toHtml());
        }
    }

    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('similarItems/adminhtml_products_grid')->toHtml()
        );
    }
}