<?php
class Ccc_SimilarItems_Block_Adminhtml_SimilarItems_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('similarItem');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('similarItems/similarItems')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'id',
            array(
                'header' => Mage::helper('similarItems')->__('Id'),
                'index' => 'id',
            )
        );

        $this->addColumn(
            'main_product_id',
            array(
                'header' => Mage::helper('similarItems')->__('Main Product Id'),
                'type' => 'options',
                'index' => 'main_product_id',
                'options' => Mage::getModel('similarItems/similarItems')->getProductArray()
            )
        );

        $this->addColumn(
            'similar_product_id',
            array(
                'header' => Mage::helper('similarItems')->__('Similar Product Id'),
                'type' => 'options',
                'index' => 'similar_product_id',
                'options' => Mage::getModel('similarItems/similarItems')->getProductArray()
            )
        );

        $this->addColumn(
            'created_at',
            array(
                'header' => Mage::helper('similarItems')->__('Created At'),
                'type' => 'datetime',
                'index' => 'created_at',
            )
        );

        $this->addColumn(
            'is_deleted',
            array(
                'header' => Mage::helper('similarItems')->__('Is Deleted'),
                'index' => 'is_deleted',
                'type' => 'options',
                'options' =>  Mage::getModel('similarItems/similarItems')->getDeleteArray(),
            )
        );

        $this->addColumn(
            'deleted_at',
            array(
                'header' => Mage::helper('similarItems')->__('Deleted At'),
                'index' => 'deleted_at',
                'type'=>'datetime',
            )
        );

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('similarItems');
        $this->getMassactionBlock()->setFormFieldName('ItemIds'); 
        $deleteArr = Mage::getModel('similarItems/similarItems')->getDeleteArray();

        $this->getMassactionBlock()->addItem(
            'is_deleted',
            array(
                'label' => Mage::helper('similarItems')->__('Delete Items'),
                'url' => $this->getUrl('*/*/massDelete', array('_current' => true)),
                'additional' => array(
                    'visibility' => array(
                        'name' => 'is_deleted',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => Mage::helper('similarItems')->__('Is Deleted'),
                        'values' => $deleteArr
                    )
                )
            )
        );
        return $this;
    }
}