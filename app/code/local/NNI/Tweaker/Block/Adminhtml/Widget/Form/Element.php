<?php

class NNI_Tweaker_Block_Adminhtml_Widget_Form_Element extends Mage_Adminhtml_Block_Widget_Form_Renderer_Fieldset_Element
{
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $this->_element = $element;
        if (Mage::getStoreConfig("nni_tweaker/general_settings/show_attribute_codes") == 1) {
            $html = $this->toHtml();
            $html =  str_replace(
                '</label>',
                "<span class='attribute-code-view'> [" . $element->getName() . "]</span></label>" ,
                $html
            );
            return $html;
        } else {
            return parent::render($element);
        }

    }
}
