<?php

class NNI_Tweaker_Adminhtml_Tweaker_QueueController extends Mage_Adminhtml_Controller_Action
{
    public function indexAjaxAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('nni_tweaker/adminhtml_emailQueue_grid')->toHtml()
        );
    }

    public function indexAction()
    {
        $this->loadLayout()->_setActiveMenu('system');
        $this->_title("Email-Queue Overview");
        $block = $this->getLayout()->createBlock('nni_tweaker/adminhtml_emailQueue','email_queue_grid');
        $messages = $this->getLayout()->getBlock('messages');
        $messages->addNotice($this->__('Overview about Magento`s current email-queue'));
        $this->_addContent($block);
        $this->renderLayout();
    }

    public function removeMassactionAction()
    {
        $params = $this->getRequest()->getParams();
        if (isset($params['message_ids']) && count($params['message_ids']) > 0) {
            $emails = Mage::getModel('core/email_queue')->getCollection()
                ->addFieldToFilter('message_id', ['in' => $params['message_ids']]);
            $emails->walk('delete');

            $this->_getSession()->addSuccess($this->__('Items deleted!'));
        } else {
            $this->_getSession()->addError($this->__('Items could not be deleted!'));
        }

        $this->_redirectReferer();
    }

    public function clearEmailQueueAction()
    {
        $queue = Mage::getModel('core/email_queue')->getCollection();
        $queue->walk('delete');

        $this->_getSession()->addSuccess($this->__('Items deleted!'));

    }

    public function showEmailMessageAction()
    {
        $messageId = $this->getRequest()->getParam('message_id', 0);
        if ($messageId) {
            $email = Mage::getModel('core/email_queue')->load($messageId);

            $this->getResponse()->setBody($email->getMessageBody());
            return;
        }

        $this->_getSession()->addError($this->__('Email not found!'));
        $this->_redirectReferer();
    }
}
