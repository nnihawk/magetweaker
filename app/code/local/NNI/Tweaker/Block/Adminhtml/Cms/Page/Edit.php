<?php

class NNI_Tweaker_Block_Adminhtml_Cms_Page_Edit extends Mage_Adminhtml_Block_Cms_Page_Edit
{
    public function __construct()
    {
        parent::__construct();

        if (($pageId = $this->getRequest()->getParam('page_id', 0)) &&
            Mage::getStoreConfig("nni_tweaker/general_settings/cms_page_allow_import_export") ==1
        ) {
            $this->updateButton('back', 'sort_order','-10');
            $cmsPage = Mage::getModel('cms/page')->load($pageId);

            $this->_addButton('open_preview', [
                'label'     => $this->__('Preview'),
                'onclick'   => 'window.location.href = \'' . Mage::getUrl($cmsPage->getIdentifier()) . '\'',
                'class'     => 'go',
                'title'     => $this->__('Open preview')
            ], -1,-3);

            $this->_addButton('export_to_json', [
                'label'     => $this->__('Export to JSON'),
                'onclick'   => 'exportCMSPage()',
                'style'     => 'color: #fff;border-color: #4d22ac;background: linear-gradient(180deg,
                                    rgba(240,240,244,1) 0%, #4d22ac 50%)',
                'class'     => 'go',
                'title'     => $this->__('Export to JSON-file for import')
            ], -1,-2);

            $this->_addButton('import_from_json', [
                'label'     => $this->__('Import from JSON'),
                'onclick'   => 'importCMSPage(this)',
                'style'     => 'color: #fff;border-color: #ac222f;background: linear-gradient(180deg,
                                    rgba(240,240,244,1) 0%, #ac222f 50%)',
                'class'     => 'save',
                'title'     => $this->__('Select JSON for import')
            ], -1,-1);


            $importCmsUrl = Mage::helper("adminhtml")->getUrl(
                'adminhtml/tweaker/importCmsPage',
                ['page_id' => $pageId],
            );

            $exportCmsUrl = Mage::helper("adminhtml")->getUrl(
                'adminhtml/tweaker/exportCmsPage',
                ['page_id' => $pageId],
            );

            $this->_formScripts[] = "
             function handleFileSelectImportCMSPage(event) {
                let url = '" . $importCmsUrl . "';
                let files = event.target.files;
                let formData = new FormData();
                formData.append('form_key', FORM_KEY);
                for (let i = 0; i < files.length; i++)
                {
                    if (!files[i].type.match('.json') && !files[i].type.match('.JSON'))
                    {
                        alert('Wrong file-format!');
                        return;
                    }
                    
                    formData.append('jsonfile',  files[i], files[i].name);
                }
                
                let xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                  if (this.readyState == 4 && this.status == 200)
                  {
                    window.location.reload();
                  }
                };
                xhttp.open('POST', url , true);
                xhttp.send(formData);
            }
            
            
             function importCMSPage(elem){
                let fileUploadField = document.getElementById('cmspageimportfile');
               
                if (!fileUploadField) {
                    fileUploadField = document.createElement('input');
                    fileUploadField.setAttribute('id', 'cmspageimportfile');
                    fileUploadField.setAttribute('type', 'file');
                    fileUploadField.setAttribute('name', 'cmspageimportfile');
                    fileUploadField.style.display = 'none';
                    fileUploadField.addEventListener('change', handleFileSelectImportCMSPage, false);
                    elem.parentElement.append(fileUploadField);
                }
                
                fileUploadField.click();
             }
                          
             function exportCMSPage(){
                window.location.href = '" . $exportCmsUrl . "';
             }
            ";
        }

    }
}