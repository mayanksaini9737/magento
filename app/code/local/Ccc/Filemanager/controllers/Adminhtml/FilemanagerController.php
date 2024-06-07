<?php

class Ccc_Filemanager_Adminhtml_FilemanagerController extends Mage_Adminhtml_Controller_Action
{
    public function _initAction()
    {
        $this->loadLayout();
    }
    public function indexAction()
    {
        $this->_title($this->__('Manage Filemanager'))->_title($this->__('Filemanager'));
        $this->_initAction();
        $this->renderLayout();
        if($this->getRequest()->isXmlHttpRequest()){
            $dir = $this->getRequest()->getPost('dir');
            // print_r($dir);
            $block = Mage::getBlockSingleton('filemanager/adminhtml_filemanager_grid');
            // $block = Mage::app()->getLayout()->createBlock('filemanager/adminhtml_filemanager_grid');
            $block->setTargetDir($dir);
            $this->getResponse()->setBody($block->toHtml());
        }
    }

    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('filemanager/adminhtml_filemanager_grid')->toHtml()
        );
    }

    public function deleteAction()
    {
        $filename = $this->getRequest()->getParam('filename');
        $filePath = Mage::getBaseDir() . DS . $filename;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        $this->_redirect('*/*/index');
    }

    public function downloadAction()
    {
        $basename = $this->getRequest()->getParam('basename');
        $filepath = $this->getRequest()->getParam('fullpath');

        $fullpath = Mage::getBaseDir() . DS . $filepath;
        if (file_exists($filepath)) {
            $this->_prepareDownloadResponse($basename, file_get_contents($fullpath));
            return;
        }
        $this->_redirect('*/*/index');
    }

    public function inlineEditAction()
    {
        $req = $this->getRequest();
        if($req->isXmlHttpRequest()){

            $newFilename =$req->getPost('value');
            $oldFilename =$req->getPost('oldname');
            $directory = $req->getPost('directory');
            $extension = $req->getPost('ext');
            
            $newpath = $directory.'\\'.$newFilename .'.'. $extension;
            $oldpath = $directory. '\\'. $oldFilename.'.'. $extension;

            // print_r($oldpath); 
            // print_r($newpath); 

            if(file_exists($oldpath) && !file_exists($newpath)){
                rename($oldpath , $newpath);
                $response = array(
                    'success' => true,
                    'message' => 'File Rename successfully'
                );
            } else {
                if (!file_exists($oldpath)) {
                    $response = array(
                        'success' => false,
                        'message' => 'Original file does not exist'
                    );
                } else {
                    $response = array(
                        'success' => false,
                        'message' => 'Filename already exists in directory'
                    );
                }
            }


            $this->getResponse()->setHeader('Content-type', 'application/json');
            $this->getResponse()->setBody(json_encode($response));
        }
    }
}