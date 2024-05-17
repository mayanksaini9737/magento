<?php
class Ccc_Help_Block_Adminhtml_Contact_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId = 'contact_id';
        $this->_controller = 'adminhtml_contact';
        $this->_blockGroup = 'help';

        parent::__construct();

        $this->_updateButton('save', 'label', Mage::helper('help')->__('Save Contact'));
        $this->_updateButton('delete', 'label', Mage::helper('help')->__('Delete Contact'));

        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save and Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = '
            function toggleEditor() {
                if (tinyMCE.getInstanceById("edit_form") == null) {
                    tinyMCE.execCommand("mceAddControl", false, "edit_form");
                } else {
                    tinyMCE.execCommand("mceRemoveControl", false, "edit_form");
                }
            }

            function saveAndContinueEdit(){
                edit_form.submit($("edit_form").action + "back/edit/");
            }
        ';
    }

    public function getHeaderText()
    {
        if (Mage::registry('contact')->getId()) {
            return Mage::helper('help')->__('Edit Contact "%s"', $this->escapeHtml(Mage::registry('contact')->getName()));
        } else {
            return Mage::helper('help')->__('New Contact');
        }
    } 
}
