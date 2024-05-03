<?php

class Ccc_Banner_Adminhtml_BannerController extends Mage_Adminhtml_Controller_Action
{

    protected function _initAction()
    {
        $this->loadLayout()->_setActiveMenu('banner/banner')
            ->_addBreadcrumb(Mage::helper('banner')->__('BANNER'), Mage::helper('banner')->__('BANNER'))
            ->_addBreadcrumb(Mage::helper('banner')->__('Manage Banner'), Mage::helper('banner')->__('Manage Banner'))
        ;
        return $this;
    }
    public function indexAction()
    {
        $this->_title($this->__('Banner'));
        $this->_initAction();
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $this->_title($this->__('Banner'))
            ->_title($this->__('Manage Content'));

        $id = $this->getRequest()->getParam('banner_id');
        $model = Mage::getModel('banner/banner');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('banner')->__('This banner no longer exists.')
                );
                $this->_redirect('*/*/');
                return;
            }
        }

        $this->_title($model->getId() ? $model->getTitle() : $this->__('New Banner'));

        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register('banner', $model);

        $this->_initAction()
            ->_addBreadcrumb(
                $id ? Mage::helper('banner')->__('Edit Banner') : Mage::helper('banner')->__('New Banner'),
                $id ? Mage::helper('banner')->__('Edit Banner') : Mage::helper('banner')->__('New Banner')
            );

        $this->renderLayout();
    }


    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('banner_id')) {
            $title = "";
            try {
                $model = Mage::getModel('banner/banner');
                $model->load($id);
                $title = $model->getTitle();
                $model->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('banner')->__('The Banner has been deleted.')
                );
                // go to grid
                Mage::dispatchEvent('adminhtml_banner_on_delete', array('title' => $title, 'status' => 'success'));
                $this->_redirect('*/*/');
                return;

            } catch (Exception $e) {
                Mage::dispatchEvent('adminhtml_banner_on_delete', array('title' => $title, 'status' => 'fail'));
                // display error message
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                // go back to edit form
                $this->_redirect('*/*/edit', array('page_id' => $id));
                return;
            }
        }
        // display error message
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('banner')->__('Unable to find a banner to delete.'));
        // go to grid
        $this->_redirect('*/*/');
    }

    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $data = $this->_filterPostData($data);
            $model = Mage::getModel('banner/banner');

            if ($id = $this->getRequest()->getParam('banner_id')) {
                $model->load($id);
            }

            try {
                if (!empty($_FILES['banner_image']['name'])) {

                    $uploader = new Varien_File_Uploader('banner_image');
                    $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(false);
                    $path = Mage::getBaseDir('media') . DS . 'banner' . DS;
                    $uploader->save($path, $_FILES['banner_image']['name']);

                    // Delete old image if exists
                    $oldImage = $model->getData('banner_image');
                    if (!empty($oldImage)) {
                        $oldImagePath = Mage::getBaseDir('media') . DS . $oldImage;
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }

                    $data['banner_image'] = 'banner/' . $uploader->getUploadedFileName();
                } elseif (isset($data['banner_image']['delete']) && $data['banner_image']['delete'] == 1) {
                    // Delete the old image
                    $oldImage = $model->getData('banner_image');
                    if (!empty($oldImage)) {
                        $oldImagePath = Mage::getBaseDir('media') . DS . $oldImage;
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }

                    $data['banner_image'] = ''; // Empty the image field if delete checkbox is checked
                } else {
                    unset($data['banner_image']); // Unset the image data if no new image uploaded and not deleting existing one
                }
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('banner_id' => $this->getRequest()->getParam('banner_id')));
                return;
            }

            $model->setData($data);

            Mage::dispatchEvent('banner_prepare_save', array('banner' => $model, 'request' => $this->getRequest()));

            if (!$this->_validatePostData($data)) {
                $this->_redirect('*/*/edit', array('banner_id' => $model->getId(), '_current' => true));
                return;
            }

            try {
                $model->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('banner')->__('The banner has been saved.')
                );

                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('banner_id' => $model->getId(), '_current' => true));
                    return;
                }

                $this->_redirect('*/*/');
                return;

            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addException(
                    $e,
                    Mage::helper('banner')->__('An error occurred while saving the banner.')
                );
            }

            $this->_getSession()->setFormData($data);
            $this->_redirect('*/*/edit', array('banner_id' => $this->getRequest()->getParam('banner_id')));
            return;
        }

        $this->_redirect('*/*/');
    }


    protected function _filterPostData($data)
    {
        $data = $this->_filterDates($data, array('custom_theme_from', 'custom_theme_to'));
        return $data;
    }

    protected function _validatePostData($data)
    {
        $errorNo = true;
        if (!empty($data['layout_update_xml']) || !empty($data['custom_layout_update_xml'])) {
            $validatorCustomLayout = Mage::getModel('adminhtml/layoutUpdate_validator');
            if (!empty($data['layout_update_xml']) && !$validatorCustomLayout->isValid($data['layout_update_xml'])) {
                $errorNo = false;
            }
            if (
                !empty($data['custom_layout_update_xml'])
                && !$validatorCustomLayout->isValid($data['custom_layout_update_xml'])
            ) {
                $errorNo = false;
            }
            // Assuming _getSession() method is defined in the parent class
            foreach ($validatorCustomLayout->getMessages() as $message) {
                $this->_getSession()->addError($message);
            }
        }
        return $errorNo;
    }

    protected function _isAllowed()
    {
        $action = strtolower($this->getRequest()->getActionName());
        switch ($action) {
            case 'showall':
                $aclResource = 'banner/banner/actions/show_all';
                break;
            case 'addnew':
                $aclResource = 'banner/banner/actions/add_new';
                break;
            case 'banneridcol':
                $aclResource = 'banner/banner/actions/banner_id_col';
                break;
            case 'imagecol':
                $aclResource = 'banner/banner/actions/image_col';
                break;
            case 'showoncol':
                $aclResource = 'banner/banner/actions/showon_col';
                break;
            default:
                $aclResource = 'banner/banner';
                break;
        }
        return Mage::getSingleton('admin/session')->isAllowed($aclResource);
    }

    public function massDeleteAction()
    {
        $bannerIds = $this->getRequest()->getParam('banner_id');
        if (!is_array($bannerIds)) {
            $this->_getSession()->addError($this->__('Please select banner(s).'));
        } else {
            if (!empty($bannerIds)) {
                try {
                    foreach ($bannerIds as $bannerId) {
                        $banner = Mage::getSingleton('banner/banner')->load($bannerId);
                        // Mage::dispatchEvent('banner_controller_banner_delete', array('banner' => $banner));
                        $banner->delete();
                    }
                    $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) have been deleted.', count($bannerIds))
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
        $bannerIds = $this->getRequest()->getParam('banner_id');
        $status = $this->getRequest()->getParam('status');

        if (!is_array($bannerIds)) {
            $bannerIds = array($bannerIds);
        }

        try {
            foreach ($bannerIds as $bannerId) {
                $banner = Mage::getModel('banner/banner')->load($bannerId);
                // Check if the status is different than the one being set
                if ($banner->getStatus() != $status) {
                    $banner->setStatus($status)->save();
                }
            }
            // Use appropriate success message based on the status changed
            if ($status == 1) {
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) have been enabled.', count($bannerIds))
                );
            } else {
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) have been disabled.', count($bannerIds))
                );
            }
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }

        $this->_redirect('*/*/index');
    }

}