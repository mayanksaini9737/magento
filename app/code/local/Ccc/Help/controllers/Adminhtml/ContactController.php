<?php
class Ccc_Help_Adminhtml_ContactController extends Mage_Adminhtml_Controller_Action
{
    public function _initAction()
    {
        $this->loadLayout();
    }
    public function indexAction()
    {
        // Mage::dispatchEvent('event_practice',[]);
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

    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('help/adminhtml_contact_grid')->toHtml()
        );
    }

    public function inlineEditAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $Data =(Mage::helper('core')->jsonDecode($this->getRequest()->getPost('edited_data')));
            $contactId=$Data['contact_id'];
            $editedData=$Data['edited_data'];

            $contactModel = Mage::getModel('help/contact');

            if ($contactId) {
                $contactModel->setData($editedData);
                $contactModel->addData(['contact_id' => $contactId]);
                $contactModel->save();
            }

            $response = array(
                'success' => true,
                'message' => 'Data saved successfully'
            );
            $this->getResponse()->setHeader('Content-type', 'application/json');
            $this->getResponse()->setBody(json_encode($response));
        }
    }

    public function massMediumAction()
    {
        $contactIds = $this->getRequest()->getParam('help');
        $help = $this->getRequest()->getParam('medium');

        if (!is_array($contactIds)) {
            $contactIds = array($contactIds);
        }

        try {
            foreach ($contactIds as $contactId) {
                $contact = Mage::getModel('help/contact')->load($contactId);
                if ($contact->getHelp() != $help) {
                    $contact->setHelp($help)->save();
                }
            }
            $this->_getSession()->addSuccess(
                $this->__('Total of %d record(s) have been updated.', count($contactIds))
            );

        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }

        $this->_redirect('*/*/index');
    }
    public function massStatusAction()
    {
        $contactIds = $this->getRequest()->getParam('help');
        $status = $this->getRequest()->getParam('status');

        if (!is_array($contactIds)) {
            $contactIds = array($contactIds);
        }

        try {
            foreach ($contactIds as $contactId) {
                $contact = Mage::getModel('help/contact')->load($contactId);
                if ($contact->getStatus() != $status) {
                    $contact->setStatus($status)->save();
                }
            }
            $this->_getSession()->addSuccess(
                $this->__('Total of %d record(s) have been updated.', count($contactIds))
            );

        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }

        $this->_redirect('*/*/index');
    }
}