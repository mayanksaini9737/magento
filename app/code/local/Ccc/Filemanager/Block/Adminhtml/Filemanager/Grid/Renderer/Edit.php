<?php
class Ccc_Filemanager_Block_Adminhtml_Filemanager_Grid_Renderer_Edit extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $filename = $row->getFilename();

        $editUrl = $this->getUrl('*/*/inlineEdit');

        $html = '<span class="editable" data-dirname="' . 
            $row->getDirname() . '" data-extension="' . 
            $row->getExtension() . '" data-url="' . $editUrl . '">'
             . $filename . '</span>';

        return $html;
    }
}