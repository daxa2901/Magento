<?php 
class Di_Process_Block_Adminhtml_Column_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    public function getProcessOptions()
    {
       $model = Mage::getModel('process/process');
       $select = $model->getCollection()
                  ->getSelect()
                  ->reset(Zend_Db_Select::COLUMNS)
                  ->columns(['value' => 'process_id', 'label' => 'name'])
                  ->order('name ASC');
         $processOptions = $model->getResource()->getReadConnection()->fetchAll($select);
         if (!$processOptions) 
         {
              return [];
         }  
         return $processOptions;
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

        $fieldset->addField('sample_value', 'text', array(
           'label' => Mage::helper('process')->__('Sample value'),
           'class' => 'required-entry',
           'name' => 'sample_value',
       ));

        $fieldset->addField('default_value', 'text', array(
           'label' => Mage::helper('process')->__('Default Value'),
           'name' => 'default_value',
       ));
         
        $fieldset->addField('process_id', 'select', array(
           'label' => Mage::helper('process')->__('Process'),
           'class' => 'required-entry',
           'name' => 'process_id',
           'values' => $this->getProcessOptions(),
       ));
        
       $fieldset->addField('type_cast', 'select', array(
           'label' => Mage::helper('process')->__('Casting Type'),
           'class' => 'required-entry',
           'name' => 'type_cast',
           'values' => [
                Di_Process_Model_Column::TYPE_CAST_VARCHAR => Mage::helper('process')->__('Varchar'),
                Di_Process_Model_Column::TYPE_CAST_INT  => Mage::helper('process')->__('Int'),
                Di_Process_Model_Column::TYPE_CAST_DECIMAL  => Mage::helper('process')->__('Decimal'),
                Di_Process_Model_Column::TYPE_CAST_DATETIME  => Mage::helper('process')->__('Datetime'),
           ],
       ));
        
       $fieldset->addField('required', 'select', array(
           'label' => Mage::helper('process')->__('Is Required?'),
           'class' => 'required-entry',
           'name' => 'required',
           'values' => [
                Di_Process_Model_Column::REQUIRED_YES => Mage::helper('process')->__('Yes'),
                Di_Process_Model_Column::REQUIRED_NO  => Mage::helper('process')->__('No'),
           ],
       ));
        
       $fieldset->addField('exception', 'select', array(
           'label' => Mage::helper('process')->__('Can Throw Exception?'),
           'class' => 'required-entry',
           'name' => 'exception',
           'values' => [
                Di_Process_Model_Column::EXCEPTION_YES => Mage::helper('process')->__('Yes'),
                Di_Process_Model_Column::EXCEPTION_NO  => Mage::helper('process')->__('No'),
           ],
       ));
        
        if ( Mage::getSingleton('adminhtml/session')->getProData() )
        {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getProData());
          Mage::getSingleton('adminhtml/session')->setProData(null);
        } 
        elseif ( Mage::registry('column_data') ) 
        {
          $form->setValues(Mage::registry('column_data')->getData());
        }
        return parent::_prepareForm();
    }
   
}