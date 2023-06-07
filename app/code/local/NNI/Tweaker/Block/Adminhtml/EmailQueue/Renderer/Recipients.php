<?php

class NNI_Tweaker_Block_Adminhtml_EmailQueue_Renderer_Recipients
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
        return str_replace('|','<br>', $value);
    }
}
