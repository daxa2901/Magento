<?php 
class Di_Process_Block_Adminhtml_Process_Upload_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
   
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/upload', array('id' => $this->getRequest()->getParam('id'))),
            'method' => 'post',
            'enctype'=> 'multipart/form-data'
         ));
        $form->setUseContainer(true);
        $this->setForm($form);
        $fieldset = $form->addFieldset('process_form', array('legend'=>Mage::helper('process')->__('Process information')));

        $fieldset->addField('file', 'file', array(
           'label' => Mage::helper('process')->__('Upload File'),
           'class' => 'required-entry',
           'name' => 'file',
       ));
        
   }
   
}