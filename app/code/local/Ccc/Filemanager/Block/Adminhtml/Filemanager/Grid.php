<?php 

class Ccc_Filemanager_Block_Adminhtml_Filemanager_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    protected $targetDir = '';
    public function __construct()
    {
        parent::__construct();
        $this->setId('filemanagerGrid');
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);
        // Mage::app()->getLayout()->getBlock('head')->addJs('Filemanager.js');
    }

    public function setTargetDir($dir){
        $this->targetDir = $dir;
        Mage::getSingleton('adminhtml/session')->setTargetDir($dir);
        return $this;
    }
    protected function _prepareCollection()
    {
        // if(empty($this->targetDir)){
        //     $this->targetDir = 'root-script';
        // }
        // $collection = Mage::getModel('filemanager/filemanager')->addTargetDir($this->targetDir);
        // $this->setCollection($collection);
        // return parent::_prepareCollection();

        if (empty($this->targetDir)) {
            $this->targetDir = Mage::getSingleton('adminhtml/session')->getTargetDir();
            if (empty($this->targetDir)) {
                $this->targetDir = 'filemanager';
            }
        }

        $collection = Mage::getModel('filemanager/filemanager')->addTargetDir($this->targetDir);
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('creation_date', array(
            'header' => Mage::helper('filemanager')->__('Creation Date'),
            'index'  => 'creation_date',
            'type'   => 'datetime',
            'filter_condition_callback' => array($this, '_filterDate')
        ));

        $this->addColumn('filename', array(
            'header' => Mage::helper('filemanager')->__('Filename'),
            'index'  => 'filename',
            'renderer' => 'Ccc_Filemanager_Block_Adminhtml_Filemanager_Grid_Renderer_Edit',
            'filter_condition_callback' => array($this, '_filterCallback'),
        ));

        $this->addColumn('folder_path', array(
            'header' => Mage::helper('filemanager')->__('Folder Path'),
            'index'  => 'folder_path',
            'filter_condition_callback' => array($this, '_filterCallback'),
        ));

        $this->addColumn('extension', array(
            'header' => Mage::helper('filemanager')->__('Extension'),
            'index'  => 'extension',
            'filter_condition_callback' => array($this, '_filterCallback'),
        ));

        $this->addColumn('actions', array(
            'header' => Mage::helper('filemanager')->__('Actions'),
            'align'  => 'left',
            'filter' => false,
            'sortable' => false,
            'renderer' => 'Ccc_Filemanager_Block_Adminhtml_Filemanager_Grid_Renderer_Actions',
        ));

        return parent::_prepareColumns();
    }
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

    protected function _filterCallback($collection, $column)
    {
        $value = $column->getFilter()->getValue();
        if($value){
            $collection->addFieldToFilter($column->getIndex(), array('like'=>'%'. $value .'%'));
        }
    }
    protected function _filterDate($collection, $column)
    {
        $value = $column->getFilter()->getValue();
        if ($value) {
            // print_r($value);
            if (isset($value['orig_from'])) {
                $from = date('Y-m-d H:i:s', strtotime($value['orig_from']));
                $collection->addFieldToFilter($column->getIndex(), array('gteq' => $from));
            }
            if (isset($value['orig_to'])) {
                $to = date('Y-m-d H:i:s', strtotime($value['orig_to'] . ' +1 day -1 second'));
                $collection->addFieldToFilter($column->getIndex(), array('lteq' => $to));
            }
        }
    }
}