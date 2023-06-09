<?php

class NNI_Tweaker_Block_Adminhtml_Catalog_Product_Edit_Tab_Attributes_Special extends Mage_Bundle_Block_Adminhtml_Catalog_Product_Edit_Tab_Attributes_Special
{
    /**
     * Retrieve element label html
     *
     * @return string
     */
    public function getElementLabelHtml()
    {
        $value = parent::getElementLabelHtml();

        if (Mage::getStoreConfig("nni_tweaker/general_settings/show_attribute_codes")==1)
        {
            $attribute = $this->getElement()->getEntityAttribute();

            return $value . "<span style='color: #999'> [" . $attribute->getAttributeCode() . "]</span>";
        }
        else return $value;
    }
}
