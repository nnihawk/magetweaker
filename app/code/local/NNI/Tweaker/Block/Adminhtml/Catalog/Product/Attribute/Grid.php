<?php

class NNI_Tweaker_Block_Adminhtml_Catalog_Product_Attribute_Grid extends Mage_Adminhtml_Block_Catalog_Product_Attribute_Grid
{
    /**
     * Prepare product attributes grid collection object
     *
     * @return $this
     */
    protected function _prepareCollection()
    {
        if (Mage::getStoreConfig('nni_tweaker/general_settings/show_hidden_attributes')) {
            $collection = Mage::getResourceModel('catalog/product_attribute_collection');
            $this->setCollection($collection);
            return Mage_Adminhtml_Block_Widget_Grid::_prepareCollection();
        } else {
            return parent::_prepareCollection();
        }
    }
}
