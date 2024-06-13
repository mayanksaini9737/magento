<?php 

class Ccc_Filetransfer_Block_Adminhtml_Files_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('FilesGrid');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('filetransfer/files')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'file_id',
            array(
                'header' => Mage::helper('filetransfer')->__('File ID'),
                'index' => 'file_id',
            )
        );

        $this->addColumn(
            'config_id',
            array(
                'header' => Mage::helper('filetransfer')->__('Config Id'),
                'index' => 'config_id',
            )
        );

        $this->addColumn(
            'host',
            array(
                'header' => Mage::helper('filetransfer')->__('Host'),
                'index' => 'host',
            )
        );

        $this->addColumn(
            'filename',
            array(
                'header' => Mage::helper('filetransfer')->__('Filename'),
                'index' => 'filename',
            )
        );

        $this->addColumn(
            'modified_date',
            array(
                'header' => Mage::helper('filetransfer')->__('Modified Date'),
                'index' => 'modified_date',
            )
        );

        $this->addColumn(
            'created_at',
            array(
                'header' => Mage::helper('filetransfer')->__('Created At'),
                'index' => 'created_at',
            )
        );

        $this->addColumn(
            'action',
            array(
                'header' => Mage::helper('filetransfer')->__('Action'),
                'align'  => 'left',
                'filter' => false,
                'sortable' => false,
                'renderer' => 'Ccc_Filetransfer_Block_Adminhtml_Files_Grid_Renderer_Actions',
            )
        );

        return parent::_prepareColumns();
    }

}