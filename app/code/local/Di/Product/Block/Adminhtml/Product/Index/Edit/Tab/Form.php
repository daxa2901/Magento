<?php

class Di_Product_Block_Adminhtml_Product_Index_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
    $form = new Varien_Data_Form();
    $this->setForm($form);
    $fieldset = $form->addFieldset('product_form', array('legend'=>Mage::helper('product')->__('product information')));
    $fieldset->addField('name', 'text', array(
       'label' => Mage::helper('product')->__('Name'),
       'class' => 'required-entry',
       'name' => 'name',
    ));
    $fieldset->addField('price', 'text', array(
       'label' => Mage::helper('product')->__('Price'),
       'class' => 'required-entry',
       'name' => 'price',
    ));
    $fieldset->addField('sku', 'text', array(
       'label' => Mage::helper('product')->__('sku'),
       'class' => 'required-entry',
       'name' => 'sku',
    ));
   
    $fieldset->addField('cost', 'text', array(
       'label' => Mage::helper('product')->__('Cost'),
       'class' => 'required-entry',
       'name' => 'cost',
    ));
    $fieldset->addField('tax', 'text', array(
       'label' => Mage::helper('product')->__('Tax'),
       'class' => 'required-entry',
       'name' => 'tax',
    ));

    $fieldset->addField('discount', 'text', array(
       'label' => Mage::helper('product')->__('Discount'),
       'class' => 'required-entry',
       'name' => 'discount',
    ));  
    $fieldset->addField('quantity', 'text', array(
       'label' => Mage::helper('product')->__('Quantity'),
       'class' => 'required-entry',
       'name' => 'quantity',
    ));  

    $fieldset->addField('discount_mode', 'select', array(
       'label' => Mage::helper('product')->__('Discount_Mode'),
       'class' => 'required-entry',
       'name' => 'discount_mode',
       'values' => array(
        array(
           'value' => 1,
           'label' => Mage::helper('product')->__('Fixed'),
        ),

        array(
           'value' => 2,
           'label' => Mage::helper('product')->__('Percentage'),
        ),
     ),
    ));
    $fieldset->addField('status', 'select', array(
       'label' => Mage::helper('product')->__('Status'),
       'class' => 'required-entry',
       'name' => 'status',
       'values' => array(
        array(
           'value' => 1,
           'label' => Mage::helper('product')->__('Active'),
        ),

        array(
           'value' => 2,
           'label' => Mage::helper('product')->__('Inactive'),
        ),
     ),
    ));

    if ( Mage::getSingleton('adminhtml/session')->getProData() )
    {
      $form->setValues(Mage::getSingleton('adminhtml/session')->getProData());
      Mage::getSingleton('adminhtml/session')->setProData(null);
   } 
   elseif ( Mage::registry('product_data') ) 
   {
      $form->setValues(Mage::registry('product_data')->getData());
   }
   return parent::_prepareForm();
}
}