<?php
class Ccc_Banner_Block_Adminhtml_Banner_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        // $this->setTemplate('banner/grid.phtml');
        $this->setId('bannerGrid');
        $this->setDefaultSort('banner_id');
        $this->setDefaultDir('ASC');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('banner/banner')->getCollection();

        if (!(Mage::getSingleton('admin/session')->isAllowed('banner/banner/actions/show_all'))) {
            $collection->setOrder('banner_id', 'DESC');
            $collection->getSelect()->limit('5'); 
            foreach ($collection as $record){}
        }

        $this->setCollection($collection);
        // $this->getCollection()->load();
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        if ((Mage::getSingleton('admin/session')->isAllowed('banner/banner/actions/banner_id_col'))) {
            $this->addColumn(
                'banner_id',
                array(
                    'header' => Mage::helper('banner')->__('ID'),
                    'align' => 'left',
                    'index' => 'banner_id',
                )
            );
        }

        if ((Mage::getSingleton('admin/session')->isAllowed('banner/banner/actions/image_col'))) {
            $this->addColumn(
                'banner',
                array(
                    'header' => Mage::helper('banner')->__('Image'),
                    'align' => 'left',
                    'index' => 'banner_image',
                    'renderer' => 'banner/adminhtml_grid_renderer_image'
                )
            );
        }

        $this->addColumn(
            'banner_image',
            array(
                'header' => Mage::helper('banner')->__('Banner Name'),
                'align' => 'left',
                'index' => 'banner_image',
            )
        );

        $this->addColumn(
            'status',
            array(
                'header' => Mage::helper('banner')->__('Status'),
                'index' => 'status',
                'type' => 'options',
                'options' => array(
                    1 => Mage::helper('banner')->__('Enabled'),
                    2 => Mage::helper('banner')->__('Disabled')
                ),
            )
        );

        if ((Mage::getSingleton('admin/session')->isAllowed('banner/banner/actions/showon_col'))) {
            $this->addColumn(
                'show_on',
                array(
                    'header' => Mage::helper('banner')->__('Show On'),
                    'index' => 'show_on',
                )
            );
        }

        return parent::_prepareColumns();
    }

    // Mass Actions 
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('banner_id');
        $this->getMassactionBlock()->setFormFieldName('banner_id'); // Change to 'banner_id'

        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label' => Mage::helper('banner')->__('Delete'),
                'url' => $this->getUrl('*/*/massDelete'),
                'confirm' => Mage::helper('banner')->__('Are you sure you want to delete selected banners?')
            )
        );

        $statuses = Mage::getSingleton('banner/status')->getOptionArray();

        array_unshift($statuses, array('label' => '', 'value' => ''));
        $this->getMassactionBlock()->addItem(
            'status',
            array(
                'label' => Mage::helper('banner')->__('Change status'),
                'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
                'additional' => array(
                    'visibility' => array(
                        'name' => 'status',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => Mage::helper('banner')->__('Status'),
                        'values' => $statuses
                    )
                )
            )
        );

        Mage::dispatchEvent('banner_adminhtml_banner_grid_prepare_massaction', array('block' => $this));
        return $this;
    }



    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }

        $this->getCollection()->addStoreFilter($value);
    }


    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('banner_id' => $row->getId()));
    }
}
