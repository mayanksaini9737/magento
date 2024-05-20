<?php 

class Ccc_Help_Block_Adminhtml_Contact_Grid_Renderer_Row extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action
{
    public function render(Varien_Object $row)
    {
        $contactId = $row->getId();
        $editUrl = $this->getUrl('*/*/inlineEdit', array('contact_id' => $contactId));
        $medium = Mage::getModel('help/contact')->getMediumArray();
        $fields = ['name', 'number', 'help', 'city'];
        
        $html = '<a href="#" class="edit-row"
            data-edit-url="' . $editUrl . '"
            data-medium=\'' . json_encode($medium) . '\'  
            data-fields=\'' . json_encode($fields) . '\'  
            data-contact-id="' . $contactId . '">'
            . Mage::helper('help')->__('Edit') . '</a>';
        
        return $html;
    }
}