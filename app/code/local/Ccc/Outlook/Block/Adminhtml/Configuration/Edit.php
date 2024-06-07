<?php
class Ccc_Outlook_Block_Adminhtml_Configuration_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId = 'config_id';
        $this->_controller = 'adminhtml_configuration';
        $this->_blockGroup = 'outlook';

        parent::__construct();

        $this->_updateButton('save', 'label', Mage::helper('help')->__('Save Configuration'));
        $this->_updateButton('delete', 'label', Mage::helper('help')->__('Delete Configuration'));

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
        if (Mage::registry('configuration')->getConfigId()) {
            return Mage::helper('outlook')->__('Edit Configuration "%s" ', $this->escapeHtml(Mage::registry('configuration')->getConfigId()));
        } else {
            return Mage::helper('outlook')->__('New Configuration');
        }
    } 
}