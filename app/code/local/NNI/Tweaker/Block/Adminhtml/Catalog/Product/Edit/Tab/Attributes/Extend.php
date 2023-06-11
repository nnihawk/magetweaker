<?php

class NNI_Tweaker_Block_Adminhtml_Catalog_Product_Edit_Tab_Attributes_Extend
    extends Mage_Bundle_Block_Adminhtml_Catalog_Product_Edit_Tab_Attributes_Extend
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

            return $value . "<span class='attribute-code-view'> [" . $attribute->getAttributeCode() . "]</span>";
        }
        else return $value;
    }

}
