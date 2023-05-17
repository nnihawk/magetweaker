<?php

class NNI_Tweaker_Block_Adminhtml_Catalog_Category_Attribute extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'nni_tweaker';
        $this->_controller = 'adminhtml_catalog_category_attribute';
        $this->_headerText = Mage::helper('catalog')->__('Category Attributes');
        $this->_addButtonLabel = Mage::helper('catalog')->__('Add New Attribute');
        parent::__construct();
    }
}
