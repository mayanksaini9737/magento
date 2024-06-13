<?php 
class Ccc_Filetransfer_Block_Adminhtml_Files_Grid_Renderer_Actions extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $filename = $row->getFilename();
        
        $downloadUrl = $this->getUrl("*/*/download") . "?filename={$filename}";
        $extractUrl = $this->getUrl("*/*/extract") . "?filename={$filename}"
            . "&config_id={$row->getConfigId()}"
            . "&host={$row->getHost()}";
        $convertUrl = $this->getUrl("*/*/convert") . "?filename={$filename}";

        $html = "<a href={$downloadUrl}>Download</a>";

        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        if ($extension == 'zip'){
            $html.= "&nbsp&nbsp<a href='{$extractUrl}'>Extract</a>";
        } elseif ($extension == 'xml'){
            $html.= "&nbsp&nbsp<a href='{$convertUrl}'>Convert</a>";
        }

        return $html;
    }
}