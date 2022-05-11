<?php 
class Di_Process_Block_Adminhtml_Process_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
   public function getGroupOptions()
   {
       $model = Mage::getModel('process/group');
       $select = $model->getCollection()
                  ->getSelect()
                  ->reset(Zend_Db_Select::COLUMNS)
                  ->columns(['value' => 'group_id', 'label' => 'name'])
                  ->order('name ASC');
         $groupOptions = $model->getResource()->getReadConnection()->fetchAll($select);
         if (!$groupOptions) 
         {
              return [];
         }  
         return $groupOptions;
   }
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('process_form', array('legend'=>Mage::helper('process')->__('Process information')));

        $fieldset->addField('name', 'text', array(
           'label' => Mage::helper('process')->__('Name'),
           'class' => 'required-entry',
           'name' => 'name',
       ));
        
        $fieldset->addField('per_request_count', 'text', array(
           'label' => Mage::helper('process')->__('per_request_count'),
           'class' => 'required-entry',
           'name' => 'per_request_count',
       ));
        
        $fieldset->addField('group_id', 'select', array(
           'label' => Mage::helper('process')->__('Group'),
           'class' => 'required-entry',
           'name' => 'group_id',
           'values' => $this->getGroupOptions(),
       ));
        
        $fieldset->addField('request_interval', 'text', array(
           'label' => Mage::helper('process')->__('request_interval'),
           'class' => 'required-entry',
           'name' => 'request_interval',
       ));
        
        $fieldset->addField('request_model', 'text', array(
           'label' => Mage::helper('process')->__('request_model'),
           'class' => 'required-entry',
           'name' => 'request_model',
       ));
        
        $fieldset->addField('type_id', 'select', array(
           'label' => Mage::helper('process')->__('Type'),
           'class' => 'required-entry',
           'name' => 'type_id',
           'values' => [
               Di_Process_Model_Process::TYPE_ID_IMPORT => Mage::helper('process')->__('Import'),
                Di_Process_Model_Process::TYPE_ID_EXPORT  => Mage::helper('process')->__('Export'),
                Di_Process_Model_Process::TYPE_ID_CRON => Mage::helper('process')->__('Cron'),
           ],
       ));
        
        $fieldset->addField('file_name', 'text', array(
           'label' => Mage::helper('process')->__('File Name'),
           'class' => 'required-entry',
           'name' => 'file_name',
       ));
        
        if ( Mage::getSingleton('adminhtml/session')->getProData() )
        {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getProData());
          Mage::getSingleton('adminhtml/session')->setProData(null);
        } 
        elseif ( Mage::registry('process_data') ) 
        {
          $form->setValues(Mage::registry('process_data')->getData());
        }
        return parent::_prepareForm();
    }
   
}