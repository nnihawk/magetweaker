<?php
class NNI_Tweaker_Block_Adminhtml_EmailQueue extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_controller = 'adminhtml_emailQueue';
        $this->_blockGroup = 'nni_tweaker';
        $this->_headerText = $this->__('Email-Queue');
        $this->removeButton('add');
        $this->addButton('clear_all',
            [
                'label'     => $this->__('Clear All'),
                'class'     => 'scalable delete',
                'onclick'   => 'clearEmailQueue(`'.
                    Mage::helper("adminhtml")->getUrl('adminhtml/tweaker_queue/clearEmailQueue') .'`)',
            ]
        );
    }

    public function getHeaderCssClass()
    {
        return 'icon-head head-newsletter';
    }
}