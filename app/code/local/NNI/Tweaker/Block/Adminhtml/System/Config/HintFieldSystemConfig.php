<?php
class NNI_Tweaker_Block_Adminhtml_System_Config_HintFieldSystemConfig
    extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    public function _prepareToRender()
    {
        $this->addColumn('config_path', array(
            'label' => $this->__('Config Path'),
            'style' => 'width:200px',
        ));
        $this->addColumn('hint', array(
            'label' => $this->__('Hint-Text'),
            'style' => 'width:200px',
            //'renderer' => $this->_getRenderer(),
        ));

        $this->_addAfter = false;
        $this->_addButtonLabel = $this->__('Add');
    }
}