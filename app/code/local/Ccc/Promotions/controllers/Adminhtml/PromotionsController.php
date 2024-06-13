<?php 

class Ccc_Promotions_Adminhtml_PromotionsController extends Mage_Adminhtml_Controller_Action
{

    protected function _initAction()
    {
        $this->loadLayout();
        return $this;
    }
    public function indexAction()
    {
        $this->_title($this->__('Pramotions'))->_title($this->__('Catalog'));
        $this->_initAction();
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $this->_title($this->__('Manage Promotions'));

        $id = $this->getRequest()->getParam('id');
        
        $model = Mage::getModel('promotions/promotions');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('promotions')->__('This Promotion no longer exists.')
                );
                $this->_redirect('*/*/');
                return;
            }
        }

        $this->_title($model->getId() ? $model->getTitle() : $this->__('New Promotions'));

        $data = Mage::getSingleton('adminhtml/session')->getFormData();
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register('promotions', $model);

        $this->_initAction();
            
        $this->renderLayout();
    }

    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $model = Mage::getModel('promotions/promotions');

            if ($id = $this->getRequest()->getParam('id')) {
                $model->load($id);
            }

            $model->setData($data);

            try {
                $model->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('promotions')->__('The Promotion has been saved.')
                );

                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId(), '_current' => true));
                    return;
                }

                $this->_redirect('*/*/');
                return;

            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addException(
                    $e,
                    Mage::helper('promotions')->__('An error occurred while saving the Promotion.')
                );
            }

            $this->_getSession()->setFormData($data);
            $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            return;
        }

        $this->_redirect('*/*/');
    }
    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $title = "";
            try {
                $model = Mage::getModel('promotions/promotions');
                $model->load($id);
                $title = $model->getTitle();
                $model->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('promotions')->__('The Promotions has been deleted.')
                );
                $this->_redirect('*/*/');
                return;

            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $id));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('promotions')->__('Unable to find a Promotion to delete.'));
        $this->_redirect('*/*/');
    }

    public function massActiveAction()
    {
        $promotionIds = $this->getRequest()->getParam('promotions');
        $status = $this->getRequest()->getParam('is_active');

        if (!is_array($promotionIds)) {
            $promotionIds = array($promotionIds);
        }

        try {
            foreach ($promotionIds as $promotionId) {
                $promotion = Mage::getModel('promotions/promotions')->load($promotionId);
                if ($promotion->getIsActive() != $status) {
                    $promotion->setIsActive($status)->save();
                }
            }
            $this->_getSession()->addSuccess(
                $this->__('Total of %d record(s) have been updated.', count($promotionIds))
            );

        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }

        $this->_redirect('*/*/index');
    }

    protected function _isAllowed()
    {
        $action = strtolower($this->getRequest()->getActionName());
        switch ($action) {
            case 'index':
                $aclResource = 'catalog/promotions/manage_promotions/actions/show'; 
                break;
            default:
                $aclResource = 'catalog/promotions';
        }
        return Mage::getSingleton('admin/session')->isAllowed($aclResource);
    }
}