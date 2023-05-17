<?php

class NNI_Tweaker_Block_Adminhtml_Catalog_Category_Attribute_Edit extends Mage_Adminhtml_Block_Catalog_Product_Attribute_Edit
{
    public function __construct()
    {
        $this->_objectId = 'attribute_id';
        $this->_controller = 'catalog_category_attribute';

        parent::__construct();

        if ($this->getRequest()->getParam('popup')) {
            $this->_removeButton('back');
            $this->_addButton(
                'close',
                [
                    'label'     => Mage::helper('catalog')->__('Close Window'),
                    'class'     => 'cancel',
                    'onclick'   => 'window.close()',
                    'level'     => -1
                ]
            );
        } else {
            $this->_addButton(
                'save_and_edit_button',
                [
                    'label'     => Mage::helper('catalog')->__('Save and Continue Edit'),
                    'onclick'   => 'saveAndContinueEdit()',
                    'class'     => 'save'
                ],
                100
            );
        }

        $this->_updateButton('save', 'label', Mage::helper('catalog')->__('Save Attribute'));
        $this->_updateButton('save', 'onclick', 'saveAttribute()');

        if (!Mage::registry('entity_attribute')->getIsUserDefined()) {
            $this->_removeButton('delete');
        } else {
            $this->_updateButton('delete', 'label', Mage::helper('catalog')->__('Delete Attribute'));
        }
    }
}
