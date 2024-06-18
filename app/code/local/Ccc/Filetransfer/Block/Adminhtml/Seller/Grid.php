<?php

class Ccc_Filetransfer_Block_Adminhtml_Seller_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('sellerGrid');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('filetransfer/seller')->getCollection();

        $collection->getSelect()
            ->joinLeft(
                array('NS' => Mage::getSingleton('core/resource')->getTableName('filetransfer/newseller')),
                'main_table.entity_id = NS.entity_id',
                array()
            )
            ->joinLeft(
                array('DS' => Mage::getSingleton('core/resource')->getTableName('filetransfer/discseller')),
                'main_table.entity_id = DS.entity_id',
                array()
            );

        $collection->getSelect()->columns(
            new Zend_Db_Expr(
                "CASE
                WHEN NS.entity_id IS NOT NULL THEN 'new'
                WHEN DS.entity_id IS NOT NULL THEN 'discontinue'
                ELSE 'regular'
            END AS status"
            )
        );

        // print_r($collection->getSelect()->__toString());
        // die;

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }


    protected function _prepareColumns()
    {
        // $this->addColumn(
        //     'entity_id',
        //     array(
        //         'header' => Mage::helper('filetransfer')->__('Entity Id'),
        //         'index' => 'entity_id',
        //     )
        // );

        $this->addColumn(
            'partnumber',
            array(
                'header' => Mage::helper('filetransfer')->__('Partnumber'),
                'index' => 'partnumber',
                'filter_condition_callback' => array($this, '_filterMaster'),
            )
        );

        $this->addColumn(
            'depth',
            array(
                'header' => Mage::helper('filetransfer')->__('Depth'),
                'index' => 'depth',
                'filter_condition_callback' => array($this, '_filterMaster'),
            )
        );

        $this->addColumn(
            'height',
            array(
                'header' => Mage::helper('filetransfer')->__('Height'),
                'index' => 'height',
                'filter_condition_callback' => array($this, '_filterMaster'),
            )
        );

        $this->addColumn(
            'length',
            array(
                'header' => Mage::helper('filetransfer')->__('Length'),
                'index' => 'length',
                'filter_condition_callback' => array($this, '_filterMaster'),
            )
        );

        $this->addColumn(
            'weight',
            array(
                'header' => Mage::helper('filetransfer')->__('weight'),
                'index' => 'weight',
                'filter_condition_callback' => array($this, '_filterMaster'),
            )
        );

        // $this->addColumn(
        //     'date',
        //     array(
        //         'header' => Mage::helper('filetransfer')->__('Created At'),
        //         'type' => 'datetime',
        //         'index' => 'date',
        //     )
        // );

        $this->addColumn(
            'status',
            array(
                'header' => Mage::helper('filetransfer')->__('Status'),
                'index' => 'status',
                'type' => 'options',
                'options' => Mage::getModel('filetransfer/seller')->statusArray(),
                'filter_condition_callback' => array($this, '_statusFilterCallback'),
            )
        );

        return parent::_prepareColumns();
    }

    protected function _filterMaster($collection, $column)
    {
        $value = $column->getFilter()->getValue();
        if ($value) {
            $collection->addFieldToFilter('main_table.' . $column->getIndex(), array('like' => '%' . $value . '%'));
        }
    }

    protected function _statusFilterCallback($collection, $column)
    {
        $value = $column->getFilter()->getValue();
        if ($value) {
            $collection->getSelect()->where(
                new Zend_Db_Expr(
                    "CASE
                    WHEN NS.entity_id IS NOT NULL THEN 'new'
                    WHEN DS.entity_id IS NOT NULL THEN 'discontinue'
                    ELSE 'regular'
                    END = ?"
                ),
                $value
            );
        }
    }
}