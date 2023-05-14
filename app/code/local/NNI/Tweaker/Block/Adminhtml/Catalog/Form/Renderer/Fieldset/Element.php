<?php
/**
 * Created by PhpStorm.
 * User: hawk
 * Date: 16.07.18
 * Time: 20:08
 */

class NNI_Tweaker_Block_Adminhtml_Catalog_Form_Renderer_Fieldset_Element extends
    Mage_Adminhtml_Block_Catalog_Form_Renderer_Fieldset_Element
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