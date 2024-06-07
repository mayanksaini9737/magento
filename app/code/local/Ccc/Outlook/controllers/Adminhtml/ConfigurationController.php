<?php

class Ccc_Outlook_Adminhtml_ConfigurationController extends Mage_Adminhtml_Controller_Action
{
    public function _initAction()
    {
        $this->loadLayout();
    }
    public function indexAction()
    {
        $this->_title($this->__('Configuration'))->_title($this->__('Outlook'));
        $this->_initAction();
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $this->_title($this->__('Manage Configuration'));

        $id = $this->getRequest()->getParam('config_id');

        $model = Mage::getModel('outlook/configuration');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('outlook')->__('This configuration no longer exists.')
                );
                $this->_redirect('*/*/');
                return;
            }
        }

        $this->_title($model->getId() ? $model->getTitle() : $this->__('Configuration'));

        $data = Mage::getSingleton('adminhtml/session')->getFormData();
        if (!empty($data)) {
            $model->setData($data);
        }
        Mage::register('configuration', $model);


        $eventModel = Mage::getModel('outlook/events');
        $collection = $eventModel->getCollection()
            ->addFieldToFilter('config_id', $model->getId())
            ->setOrder('group_id', 'Asc')->getData();


        if (!empty($collection)) {
            $eventModel->setData($collection);
        }
        Mage::register('events_data', $eventModel);

        $this->_initAction();

        $this->renderLayout();
    }

    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {

            $model = Mage::getModel('outlook/configuration');

            if ($id = $this->getRequest()->getParam('config_id')) {
                $model->load($id);
            }

            $model->setData($data['config']);
            try {
                $model->save();

                if (!empty($data['events'])){
                    Mage::getModel('outlook/events')->saveEvents($data['events'], $model);
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('help')->__('The Configuration has been saved.')
                );

                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('config_id' => $model->getId(), '_current' => true));
                    return;
                }

                $this->_redirect('*/*/');
                return;

            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addException(
                    $e,
                    Mage::helper('help')->__('An error occurred while saving the Configuration.')
                );
            }

            $this->_getSession()->setFormData($data['config']);
            $this->_redirect('*/*/edit', array('config_id' => $this->getRequest()->getParam('config_id')));
            return;
        }

        $this->_redirect('*/*/');
    }
    
    public function removeForEventsAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $group =Mage::helper('core')->jsonDecode($this->getRequest()->getPost('group'));
            $event =Mage::helper('core')->jsonDecode($this->getRequest()->getPost('event'));

            $eventModel = Mage::getModel('outlook/events');
            if (!empty($group)){
                $collection = $eventModel->getCollection()->addFieldToFilter('group_id', $group);
                foreach ($collection as $_event) {
                    $eventId = $_event->getEventId();
                    $eventModel->load($eventId);
                    $eventModel->delete();
                }
            }
            if (!empty($event)) {
                print_r($event);
                $eventModel->load($event);
                $eventModel->delete();
            }
            $response = array(
                'success' => true,
                'message' => 'Data Deleted successfully'
            );
            $this->getResponse()->setHeader('Content-type', 'application/json');
            $this->getResponse()->setBody(json_encode($response));
        }
    }
}