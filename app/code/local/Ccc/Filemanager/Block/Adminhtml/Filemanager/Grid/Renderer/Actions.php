<?php 
class Ccc_Filemanager_Block_Adminhtml_Filemanager_Grid_Renderer_Actions extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $deleteUrl = $this->getUrl("*/*/delete") . "?filename={$row->getFullpath()}";
        $downloadUrl = $this->getUrl("*/*/download") . 
        "?fullpath={$row->getFullpath()}&basename={$row->getBasename()}";

        $html = "<a href='{$deleteUrl}'>Delete</a>
        &nbsp&nbsp
        <a href={$downloadUrl}>Download</a>";

        return $html;
    }
}