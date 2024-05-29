<?php 

class Ccc_Promotions_Adminhtml_ReportController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout();
        return $this;
    }
    public function indexAction()
    {
        $this->_title($this->__('Report'));
        $this->_initAction();
        $this->renderLayout();
    }

    protected function _isAllowed()
    {
        $action = strtolower($this->getRequest()->getActionName());
        $aclResource = '';
        switch ($action) {
            case 'index':
                $aclResource = 'catalog/promotions/manage_report/actions/show'; 
                break;
        }
        return Mage::getSingleton('admin/session')->isAllowed($aclResource);
    }

    public function getProductsAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            // $Data =(Mage::helper('core')->jsonDecode($this->getRequest()->getPost('edited_data')));
            // $selectedValue=$Data['selectedValue'];

            // $attribute = Mage::getSingleton('eav/config')->getAttribute('catalog_product', 'active_tag');

            // $attributeOptions = $attribute->getSource()->getAllOptions(false);
            
            // $activeTagValue = null;
            
            // foreach ($attributeOptions as $option) {
            //     if ($option['label'] == $selectedValue) {
            //         $activeTagValue = $option['value'];
            //         break;
            //     }
            // }
            
            // $products = Mage::getModel('catalog/product')->getCollection()
            //             ->addAttributeToSelect(array('name', 'price', 'special_price'))
            //             ->addFieldToFilter('active_tag', $activeTagValue); 

            // $productsData = [];
            // foreach ($products as $_product) {
            //     $productsData[] = array(
            //         'name'=>$_product->getName(),
            //         'sku'=>$_product->getSku(),
            //         'price'=>$_product->getPrice(),
            //         'special_price'=>$_product->getSpecialPrice(),
            //     );
            // }

            $block = Mage::app()->getLayout()->createBlock('promotions/adminhtml_report_table');
            // $block->setProductsData($productsData); 

            // print_r($block->getProductsData());

            // $this->getResponse()->setHeader('Content-type', 'application/json');
            $this->getResponse()->setBody($block->toHtml());
        }
    }
    
    public function assignToProductAction()
    {
        $Data =(Mage::helper('core')->jsonDecode($this->getRequest()->getPost('edited_data')));
        $selectedValue=$Data['selectedValue'];
        $sku = $Data['selectedSku'];

        $attribute = Mage::getSingleton('eav/config')->getAttribute('catalog_product', 'active_tag');
        $attributeOptions = $attribute->getSource()->getAllOptions(false);

        $activeTagValue = null;
        foreach ($attributeOptions as $option) {
            if ($option['label'] == $selectedValue) {
                $activeTagValue = $option['value'];
                break;
            }
        }

        if ($activeTagValue !== null) {
            $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku);
            if ($product) {
                $product->setActiveTag($activeTagValue);
                try {
                    $product->save();
                    $response = array(
                        'success' => true,
                        'message' => 'Product updated successfully'
                    );
                } catch (Exception $e) {
                    $response = array(
                        'success' => false,
                        'message' => 'Failed to update product: ' . $e->getMessage()
                    );
                }
            } else {
                $response = array(
                    'success' => false,
                    'message' => 'Product not found'
                );
            }
        } else {
            $response = array(
                'success' => false,
                'message' => 'Invalid tag selected'
            );
        }
        
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(json_encode($response));
    }
}