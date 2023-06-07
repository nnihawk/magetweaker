<?php

class NNI_Tweaker_Block_Adminhtml_System_Config_Fieldset_Hint
    extends Mage_Adminhtml_Block_Abstract
    implements Varien_Data_Form_Element_Renderer_Interface
{
    protected function _toHtml()
    {
        $html = <<<HTML
        <h3 style="padding: 5px">NNI Tweaker</h3>
        <table class="nni_tweaker_hint">
            <tr><td>%s:</td><td>Created by Niko Nikolaou 2021 - 2023</td>
            <tr><td>%s:</td><td>%s</td>
            <tr><td>%s:</td><td>Freeware</td>
            </tr><td>%s:</td><td><a href="mailto:hawkxxx@web.de">hawkxxx@web.de</a></td></tr>
            </tr><td>%s:</td><td>>= 7.0</td></tr>
            <tr><td colspan="2">%s</td></tr>
        </table>
        HTML;

        $html = sprintf(
            $html,
            $this->__('Author'),
            $this->__('Version'),
            (string)Mage::getConfig()->getNode('modules/NNI_Tweaker/version'),
            $this->__('License'),
            $this->__('Email'),
            $this->__('Required PHP-Version'),
            "This is a small extension for some really missing functionalities in Magento / OpenMage."
        );

        return $html;
    }

    /**
     * Render fieldset html
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
       return $this->toHtml();
    }
}
