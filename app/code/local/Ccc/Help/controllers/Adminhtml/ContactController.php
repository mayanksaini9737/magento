<?php
class Ccc_Help_Adminhtml_ContactController extends Mage_Adminhtml_Controller_Action
{
    public function _initAction()
    {
        $this->loadLayout();
    }
    public function indexAction()
    {
        $this->_initAction();
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $this->_title($this->__('Manage Contact'));

        $id = $this->getRequest()->getParam('contact_id');

        $model = Mage::getModel('help/contact');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('contact')->__('This contact no longer exists.')
                );
                $this->_redirect('*/*/');
                return;
            }
        }

        $this->_title($model->getId() ? $model->getTitle() : $this->__('Contact'));

        $data = Mage::getSingleton('adminhtml/session')->getFormData();
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register('contact', $model);

        $this->_initAction();

        $this->renderLayout();
    }

    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $model = Mage::getModel('help/contact');

            if ($id = $this->getRequest()->getParam('contact_id')) {
                $model->load($id);
            }

            $model->setData($data);

            Mage::dispatchEvent('contact_prepare_save', array('contact' => $model, 'request' => $this->getRequest()));

            try {
                $model->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('help')->__('The Contact has been saved.')
                );

                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('contact_id' => $model->getId(), '_current' => true));
                    return;
                }

                $this->_redirect('*/*/');
                return;

            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addException(
                    $e,
                    Mage::helper('help')->__('An error occurred while saving the Contact.')
                );
            }

            $this->_getSession()->setFormData($data);
            $this->_redirect('*/*/edit', array('contact_id' => $this->getRequest()->getParam('contact_id')));
            return;
        }

        $this->_redirect('*/*/');
    }

    protected function _isAllowed()
    {
        $action = strtolower($this->getRequest()->getActionName());
        switch ($action) {
            case 'save':
                $aclResource = 'help/contact/actions/save';
                break;
            case 'new':
                $aclResource = 'help/contact/actions/add_new';
                break;
            case 'edit':
                $aclResource = 'help/contact/actions/edit';
                break;
            case 'index':
                $aclResource = 'help/contact/actions/show_all';
                break;
            default:
                $aclResource = 'help/contact/actions/show_all';
                break;
        }
        return Mage::getSingleton('admin/session')->isAllowed($aclResource);
    }
}