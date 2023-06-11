<?php

class NNI_Tweaker_Block_Adminhtml_System_Config_Form_Field extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $hintData = Mage::helper('nni_tweaker')->getSystemConfigHintValue($this->_parseSystemConfigPath($element));
        $hintHtml = '';
        if ($hintData) {
            $hintHtml = '<div class="tweaker-hint" title="' . $this->__('Click to show extended information') . '">
                <div class="tweaker-hint-content">' . $hintData .'</div></div>';
        }

        return parent::_getElementHtml($element) . $hintHtml;
    }

    /**
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        if (Mage::getStoreConfig("nni_tweaker/general_settings/show_attribute_codes") == 1) {
            $label = $element->getLabel() .
                "<span class='attribute-code-view'> [" . $this->_parseSystemConfigPath($element). "]</span>";
            $element->setLabel($label);
        }

        return parent::render($element);
    }

    private function _parseSystemConfigPath(Varien_Data_Form_Element_Abstract $element)
    {
        $path = str_replace(['groups[','[fields]','[value]'], ['','',''], $element->getName());
        $path = trim(str_replace(['][',']'], ['/', ''], $path), '/');
        $pathSections = explode('/', $path);
        $nameSpace = substr($element->getHtmlId(), 0, strpos($element->getHtmlId(), '_' . $pathSections[0]));
        return $nameSpace . '/' . $pathSections[0] . '/' . $pathSections[1];
    }
}
