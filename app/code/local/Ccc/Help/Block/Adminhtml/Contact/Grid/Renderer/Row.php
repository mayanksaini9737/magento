<?php 

class Ccc_Help_Block_Adminhtml_Contact_Grid_Renderer_Row extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action
{
    public function render(Varien_Object $row)
    {
        $contactId = $row->getId();
        $editUrl = $this->getUrl('*/*/edit', array('contact_id' => $contactId));
        
        $html = '<a href="#" class="edit-row" data-edit-url="' . $editUrl . '" data-contact-id="' . $contactId . '">' . Mage::helper('help')->__('Edit') . '</a>';
        
        return $html;
    }
}