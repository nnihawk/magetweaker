<?php

class NNI_Tweaker_Block_Adminhtml_Catalog_Category_Attribute_Grid extends Mage_Adminhtml_Block_Catalog_Product_Attribute_Grid
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('catalog/category_attribute_collection');
        //$collection ->addVisibleFilter();
        $this->setCollection($collection);
        return Mage_Adminhtml_Block_Widget_Grid::_prepareCollection();
    }
}
