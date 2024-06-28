<?php
class Ccc_SimilarItems_Block_Adminhtml_Products_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('similarItem');
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $products= Mage::registry('similarProducts');
        if ($products){
            $collection = Mage::getModel('catalog/product')->getCollection()
                ->addFieldToFilter('entity_id', array('in'=>$products))
                ->addAttributeToSelect('name');
            $this->setCollection($collection);
        }
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('similarItems')->__('Id'),
                'index' => 'entity_id',
            )
        );

        $this->addColumn(
            'sku',
            array(
                'header' => Mage::helper('similarItems')->__('Product Sku'),
                'type' => 'text',
                'index' => 'sku',
            )
        );

        $this->addColumn(
            'name',
            array(
                'header' => Mage::helper('similarItems')->__('Product Name'),
                'type' => 'text',
                'index' => 'name',
            )
        );

        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

}