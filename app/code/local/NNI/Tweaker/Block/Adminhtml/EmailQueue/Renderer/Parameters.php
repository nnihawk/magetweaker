<?php

class NNI_Tweaker_Block_Adminhtml_EmailQueue_Renderer_Parameters
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * Renders grid column
     *
     * @param   Varien_Object $row
     * @return  string
     */
    public function render(Varien_Object $row)
    {
        $value = $row->getData($this->getColumn()->getIndex());

        return '<pre>' . print_r($value, true) .'</pre>';
    }
}
