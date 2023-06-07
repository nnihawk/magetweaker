<?php

class NNI_Tweaker_Block_Adminhtml_EmailQueue_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('email_queue');
        $this->setDefaultSort('message_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('core/email_queue')->getCollection();
        $collection->getSelect()
            ->joinLeft(
                ['recipients' => 'core_email_queue_recipients'],
                'main_table.message_id = recipients.message_id',
                ['email_recipients' => new Zend_Db_Expr("GROUP_CONCAT(recipients.`recipient_email` SEPARATOR '|')")]
            )
        ->group('main_table.message_id');

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('message_id', array(
            'header' => $this->__('Message-ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'message_id',
        ));

        $this->addColumn('entity_type', array(
            'header' => $this->__('Type'),
            'width' => '50px',
            'index' => 'entity_type',
        ));

        $this->addColumn('event_type', array(
            'header' => $this->__('Event'),
            'align' => 'center',
            'width' => '50px',
            'index' => 'event_type',
        ));

        $this->addColumn('email_recipients', array(
            'header' => $this->__('Recipients'),
            'index' => 'email_recipients',
            'filter' => false,
            'renderer' => 'nni_tweaker/adminhtml_emailQueue_renderer_recipients',
            'sortable'  => false
        ));

        $this->addColumn('message_parameters', array(
            'header' => $this->__('Message Parameters'),
            'index' => 'message_parameters',
            'filter' => false,
            'sortable'  => false,
            'renderer' => 'nni_tweaker/adminhtml_emailQueue_renderer_parameters'
        ));

        $this->addColumn('created_at', array(
            'header' => $this->__('Created at'),
            'align' => 'right',
            'type'  => 'datetime',
            'width' => '50px',
            'index' => 'created_at',
        ));

        $this->addColumn('processed_at', array(
            'header' => $this->__('Processed at'),
            'align' => 'right',
            'width' => '50px',
            'type'  => 'datetime',
            'index' => 'processed_at',
        ));

        $showMessageUrl = Mage::helper('adminhtml')->getUrl('adminhtml/tweaker_queue/showEmailMessage');

        $this->addColumn('action', [
            'header' => $this->__('Actions'),
            'align' => 'center',
            'width' => '50px',
            'type'  => 'action',
            'index' => 'action',
            'getter'  => 'getId',
            'actions'   => [
                [
                    'onclick' => 'clearEmailQueue($message_id, `'. $showMessageUrl .'`)',
                    'caption' => Mage::helper('catalog')->__('Show Message'),
                    'field'   => 'message_id',
                    'style'   => 'cursor: pointer',
                    'title'   => $this->__('Show content of Email')
                ]
            ],
        ]);

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('message_id');
        $this->getMassactionBlock()->setFormFieldName('message_ids');



        $this->getMassactionBlock()->addItem('remove', [
            'label'    => $this->__('Remove'),
            'url'      => $this->getUrl('*/*/removeMassaction'),
        ]);

        return $this;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/indexAjax', ['_current' => true]);
    }

    public function getRowUrl($row)
    {
        return '';
    }
}