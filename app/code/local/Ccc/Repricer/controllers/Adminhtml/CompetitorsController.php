<?php 

class Ccc_Repricer_Adminhtml_CompetitorsController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout();
        return $this;
    }
    public function indexAction()
    {
        $this->_title($this->__('Competitor'));
        $this->_initAction();
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $this->_title($this->__('Manage Competitor'));

        $id = $this->getRequest()->getParam('competitor_id');
        
        $model = Mage::getModel('repricer/competitors');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('repricer')->__('This competitor no longer exists.')
                );
                $this->_redirect('*/*/');
                return;
            }
        }

        $this->_title($model->getId() ? $model->getTitle() : $this->__('New Competitor'));

        $data = Mage::getSingleton('adminhtml/session')->getFormData();
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register('competitor', $model);

        $this->_initAction();
            
        $this->renderLayout();
    }

    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $model = Mage::getModel('repricer/competitors');

            if ($id = $this->getRequest()->getParam('competitor_id')) {
                $model->load($id);
            }

            $model->setData($data);

            Mage::dispatchEvent('competitor_prepare_save', array('competitors' => $model, 'request' => $this->getRequest()));

            try {
                $model->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('repricer')->__('The Competitor has been saved.')
                );

                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('competitor_id' => $model->getId(), '_current' => true));
                    return;
                }

                $this->_redirect('*/*/');
                return;

            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addException(
                    $e,
                    Mage::helper('repricer')->__('An error occurred while saving the Competitor.')
                );
            }

            $this->_getSession()->setFormData($data);
            $this->_redirect('*/*/edit', array('competitor_id' => $this->getRequest()->getParam('competitor_id')));
            return;
        }

        $this->_redirect('*/*/');
    }
    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('competitor_id')) {
            $title = "";
            try {
                $model = Mage::getModel('repricer/competitors');
                $model->load($id);
                $title = $model->getTitle();
                $model->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('repricer')->__('The Competitor has been deleted.')
                );
                Mage::dispatchEvent('adminhtml_competitor_on_delete', array('title' => $title, 'status' => 'success'));
                $this->_redirect('*/*/');
                return;

            } catch (Exception $e) {
                Mage::dispatchEvent('adminhtml_competitor_on_delete', array('title' => $title, 'status' => 'fail'));
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('page_id' => $id));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('repricer')->__('Unable to find a competitor to delete.'));
        $this->_redirect('*/*/');
    }

    public function massDeleteAction()
    {
        $competitorIds = $this->getRequest()->getParam('competitor_id');
        if (!is_array($competitorIds)) {
            $this->_getSession()->addError($this->__('Please select competitor(s).'));
        } else {
            if (!empty($competitorIds)) {
                try {
                    foreach ($competitorIds as $competitorId) {
                        $competitor = Mage::getSingleton('repricer/competitors')->load($competitorId);
                        // Mage::dispatchEvent('competitor_controller_competitor_delete', array('competitor' => $competitor));
                        $competitor->delete();
                    }
                    $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) have been deleted.', count($competitorIds))
                    );
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction()
    {
        $competitorIds = $this->getRequest()->getParam('competitor_id');
        $status = $this->getRequest()->getParam('status');

        if (!is_array($competitorIds)) {
            $competitorIds = array($competitorIds);
        }

        try {
            foreach ($competitorIds as $competitorId) {
                $competitor = Mage::getModel('repricer/competitors')->load($competitorId);
                if ($competitor->getStatus() != $status) {
                    $competitor->setStatus($status)->save();
                }
            }
            if ($status == 1) {
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) have been enabled.', count($competitorIds))
                );
            } else {
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) have been disabled.', count($competitorIds))
                );
            }
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }

        $this->_redirect('*/*/index');
    }

   
}
