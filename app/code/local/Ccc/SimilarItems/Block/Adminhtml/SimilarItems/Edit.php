<?php
class Ccc_SimilarItems_Block_Adminhtml_SimilarItems_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'similarItems';
        $this->_controller = 'adminhtml_similarItems';

        parent::__construct();

        $this->_updateButton('save', 'label', Mage::helper('similarItems')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('similarItems')->__('Delete Item'));

        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save and Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('competitor_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'promotions_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'promotions_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if (Mage::registry('similaritem')->getId()) {
            return Mage::helper('similarItems')->__('Edit Promotion %s', $this->escapeHtml(Mage::registry('similaritem')->getMainProductId()));
        } else {
            return Mage::helper('similarItems')->__('New Promotion');
        }
    } 
}
