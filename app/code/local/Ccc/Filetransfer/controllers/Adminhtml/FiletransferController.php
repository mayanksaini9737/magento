<?php

class Ccc_Filetransfer_Adminhtml_FiletransferController extends Mage_Adminhtml_Controller_Action
{
    public function _initAction()
    {
        $this->loadLayout();
    }
    public function indexAction()
    {
        $this->_title($this->__('Manage Configuration'))->_title($this->__('Filetransfer'));
        $this->_initAction();
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $this->_title($this->__('Filetransfer'));

        $id = $this->getRequest()->getParam('config_id');

        $model = Mage::getModel('filetransfer/config');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('filetransfer')->__('This Configuration no longer exists.')
                );
                $this->_redirect('*/*/');
                return;
            }
        }

        $this->_title($model->getId() ? $model->getTitle() : $this->__('New Configuration'));

        $data = Mage::getSingleton('adminhtml/session')->getFormData();
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register('config', $model);

        $this->_initAction();
        $this->renderLayout();
    }
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $model = Mage::getModel('filetransfer/config');

            if ($id = $this->getRequest()->getParam('config_id')) {
                $model->load($id);
            }

            $model->setData($data);

            try {
                $model->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('filetransfer')->__('The configuration has been saved.')
                );

                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect(
                        '*/*/edit',
                        array('config_id' => $model->getConfigId(), '_current' => true)
                    );
                    return;
                }

                $this->_redirect('*/*/');
                return;

            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addException(
                    $e,
                    Mage::helper('filetransfer')->__('An error occurred while saving the Promotion.')
                );
            }

            $this->_getSession()->setFormData($data);
            $this->_redirect('*/*/edit', array('config_id' => $this->getRequest()->getParam('config_id')));
            return;
        }

        $this->_redirect('*/*/');
    }

    // public function deleteAction()
    // {
    //     if ($id = $this->getRequest()->getParam('id')) {
    //         $title = "";
    //         try {
    //             $model = Mage::getModel('promotions/promotions');
    //             $model->load($id);
    //             $title = $model->getTitle();
    //             $model->delete();
    //             Mage::getSingleton('adminhtml/session')->addSuccess(
    //                 Mage::helper('filetransfer')->__('The Promotions has been deleted.')
    //             );
    //             $this->_redirect('*/*/');
    //             return;

    //         } catch (Exception $e) {
    //             Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
    //             $this->_redirect('*/*/edit', array('id' => $id));
    //             return;
    //         }
    //     }
    //     Mage::getSingleton('adminhtml/session')->addError(Mage::helper('filetransfer')->__('Unable to find a Promotion to delete.'));
    //     $this->_redirect('*/*/');
    // }

    public function filesAction()
    {
        $this->_title($this->__('Manage Files'))->_title($this->__('Filetransfer'));
        $this->_initAction();
        $this->renderLayout();
    }

    public function downloadAction()
    {
        $filename = $this->getRequest()->getParam('filename');
        $basename = basename($filename);
        $fullPath = Mage::getBaseDir('var') . DS . 'filetransfer' . $filename;

        if (file_exists($fullPath)) {
            $this->_prepareDownloadResponse($basename, file_get_contents($fullPath));
            return;
        }
        $this->_redirect('*/*/files');
    }

    public function extractAction()
    {
        $filename = $this->getRequest()->getParam('filename');
        $configId = $this->getRequest()->getParam('config_id');
        $host = $this->getRequest()->getParam('host');
        $zipFilePath = Mage::getBaseDir('var') . DS . 'filetransfer' . DS . $filename;
        $dir = pathinfo($filename, PATHINFO_FILENAME);

        $extractPath = Mage::getBaseDir('var') . DS . 'filetransfer' . DS . 'extracted' . DS . $dir;
        if (!file_exists($extractPath)) {
            mkdir($extractPath, 0755, true);
        }

        $zip = new Zend_Filter_Compress_Zip(array('target' => $extractPath));
        $zip->decompress($zipFilePath);

        $extractedFiles = scandir($extractPath);
        $extractedFiles = array_diff($extractedFiles, array('.', '..'));

        $filesModel = Mage::getModel('filetransfer/files');
        foreach ($extractedFiles as $_exfile) {
            $filepath = '\extracted' . DS . $dir . DS . $_exfile;
            $modifiedTime = filemtime(Mage::getBaseDir('var') . DS . 'filetransfer' . $filepath);
            $modifiedDate = date('Y-m-d H:i:s', $modifiedTime);

            $data = [
                'filename' => $filepath,
                'config_id' => $configId,
                'host' => $host,
                'modified_date' => $modifiedDate
            ];
            $filesModel->setData($data);
            $filesModel->save();
        }
        
        $this->_redirect('*/*/files');
    }

    public function convertAction()
    {
        $filename = $this->getRequest()->getParam('filename');
        $filepath = Mage::getBaseDir('var') . DS . 'filetransfer' . DS . $filename;

        $xml = simplexml_load_file($filepath);

        $csvDir = Mage::getBaseDir('var') . DS . 'filetransfer' . DS . 'csv';
        if (!file_exists($csvDir)) {
            mkdir($csvDir, 0755, true);
        }
        $csvFilePath = $csvDir . DS . pathinfo($filename, PATHINFO_FILENAME) . '.csv';

        $rows_data = Mage::helper('filetransfer')->getRows();
        $xmlData = $this->readXml($xml, $rows_data);
        
        // echo "<pre>";
        // print_r($xmlData);
        // die;
        
        $this->writeCsv($xmlData, $csvFilePath);

        Mage::getSingleton('adminhtml/session')->addSuccess(
            Mage::helper('filetransfer')->__('The XML file has been converted into CSV.')
        );

        $this->_redirect('*/*/files');
    }

    private function readXml($xml, $rows)
    {
        $result = [];

        foreach ($xml->xpath('//items/item') as $item) {
            $data = [];

            foreach ($rows as $key => $row) {
                $pathParts = explode('.', $row['path']);
                $attribute = $row['attribute'];

                $currentElement = $item;
                for ($i = 2; $i < count($pathParts); $i++) {
                    $currentElement = $currentElement->{$pathParts[$i]};
                }

                $data[$key] = (string) $currentElement[$attribute];
            }
            $result[] = $data;
        }

        return $result;
    }

    public function writeCsv($data, $csvFile)
    {
        $filePaths = [];
        $csv = '';

        $headerRow = array_keys($data[0]);
        $csv .= implode(',', $headerRow) . "\n";

        foreach ($data as $row) {
            $csvRow = array_map(function ($value) {
                return '"' . str_replace('"', '""', $value) . '"';
            }, $row);
            $csv .= implode(',', $csvRow) . "\n";
        }

        file_put_contents($csvFile, $csv);

        return $filePaths;
    }

    public function sellerAction()
    {
        $this->_title($this->__('Manage Seller'))->_title($this->__('Filetransfer'));
        $this->_initAction();
        $this->renderLayout();
    }
}