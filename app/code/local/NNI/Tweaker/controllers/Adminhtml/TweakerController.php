<?php

class NNI_Tweaker_Adminhtml_TweakerController extends Mage_Adminhtml_Controller_Action
{
    public function exportCmsPageAction()
    {
        if (($pageId = $this->getRequest()->getParam('page_id', 0))) {
            $cmsPage = Mage::getModel('cms/page')->load($pageId);
            if (is_object($cmsPage) && $cmsPage->getId() > 0) {
                $data = $cmsPage->getData();

                $json = json_encode($data);
                $this->_prepareDownloadResponse('CMS_Page_' . $pageId . '.json', $json);
                return;
            }
        }

        Mage::getSingleton('adminhtml/session')->addError($this->__('Invalid id!'));
        $this->_redirect('*/*/');
    }

    public function importCmsPageAction()
    {
        if (($pageId = $this->getRequest()->getParam('page_id', 0))) {
            $cmsPage = Mage::getModel('cms/page')->load($pageId);
            if (
                (is_object($cmsPage) && $cmsPage->getId() > 0) &&
                (isset($_FILES['jsonfile']['name']) && $_FILES['jsonfile']['name'] != '')
            ) {
                $saveDir = Mage::getBaseDir('var') . "/tmp/";
                $filename = str_replace(" ", '_', $_FILES['jsonfile']['name']);

                $uploader = new Varien_File_Uploader('jsonfile');
                $uploader->setAllowedExtensions(['json']);
                $uploader->setAllowCreateFolders(true);
                $uploader->setAllowRenameFiles(false);
                $uploader->setFilesDispersion(false);
                $uploader->save($saveDir, $filename);

                $io = new Varien_Io_File();
                $io->open(['path' => $saveDir]);
                $json = $io->read($filename);
                $io->rm($saveDir . $filename);

                try {
                    $data = json_decode($json, true);
                    $data['page_id'] = $cmsPage->getId();
                    $data['identifier'] = $cmsPage->getIdentifier();
                    $data['stores'] = $cmsPage->getStores();
                    $cmsPage->setData($data);
                    $cmsPage->save();

                    Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Import successful!'));
                    return;
                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                }
            }
        } else {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Invalid id!'));
        }
    }
}
