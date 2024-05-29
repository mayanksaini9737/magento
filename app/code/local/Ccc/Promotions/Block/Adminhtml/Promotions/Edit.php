<?php
class Ccc_Promotions_Block_Adminhtml_Promotions_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId = 'id';
        $this->_controller = 'adminhtml_promotions';
        $this->_blockGroup = 'promotions';

        parent::__construct();

        $this->_updateButton('save', 'label', Mage::helper('promotions')->__('Save Promotion'));
        $this->_updateButton('delete', 'label', Mage::helper('promotions')->__('Delete Promotion'));

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
        if (Mage::registry('promotions')->getId()) {
            return Mage::helper('promotions')->__('Edit Promotion %s', $this->escapeHtml(Mage::registry('promotions')->getTitle()));
        } else {
            return Mage::helper('promotions')->__('New Promotion');
        }
    } 
}
