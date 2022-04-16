<?php

class Di_Category_Block_Adminhtml_Category_Index_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
      
     $form = new Varien_Data_Form();
     $this->setForm($form);
     $fieldset = $form->addFieldset('category_form', array('legend'=>Mage::helper('category')->__('category information')));
     $fieldset->addField('name', 'text', array(
        'label' => Mage::helper('category')->__('Name'),
        'class' => 'required-entry',
        'name' => 'name',
        
     ));
     $fieldset->addField('parent_id', 'select', array(
        'label' => Mage::helper('category')->__('Parent'),
        'name' => 'parent_id',
        'values' =>$this->getPath(),
        
     ));
     
     $fieldset->addField('status', 'select', array(
        'label' => Mage::helper('category')->__('Status'),
        'class' => 'required-entry',
        'name' => 'status',
        'values' => array(
          array(
             'value' => 1,
             'label' => Mage::helper('category')->__('Active'),
          ),

          array(
             'value' => 2,
             'label' => Mage::helper('category')->__('Inactive'),
          ),
       ),
     ));

     if ( Mage::getSingleton('adminhtml/session')->getProData() )
     {
      $form->setValues(Mage::getSingleton('adminhtml/session')->getProData());
      Mage::getSingleton('adminhtml/session')->setProData(null);
   } 
   elseif ( Mage::registry('category_data') ) 
   {
      $form->setValues(Mage::registry('category_data')->getData());
   }
   return parent::_prepareForm();
   }

   protected function getPath()
   {
      $id = (int)$this->getRequest()->getParam('id');
      $path = [];
      if ($id) 
      {
         $categoryPath = Mage::getModel('category/category')->getCategoryToPath($id);
      }
      else
      {
         $categoryPath = Mage::getModel('category/category')->getCategoryToPath();
      }
      $path[] = [
       'value' => null,
       'label' => Mage::helper('category')->__('Root'),
    ];
    
    foreach ($categoryPath as $key => $value) {
       $path[] = [
          'value' => $key,
          'label' => Mage::helper('category')->__($value),
       ];
    }
    return $path;
   }
}