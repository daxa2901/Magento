<?php

class Di_Product_Block_Adminhtml_Product_Index_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct()
    {
        parent::__construct();
        $this->setId('product_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('product')->__('product Info.'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
        'label'     => Mage::helper('product')->__('Product Information'),
        'title'     => Mage::helper('product')->__('Product Information'),
        'content' => $this->getLayout()->createBlock('product/adminhtml_product_index_edit_tab_form')->toHtml(),
    ));
        $this->addTab('image_section', array(
        'label'     => Mage::helper('product')->__('Images'),
        'title'     => Mage::helper('product')->__('Images'),
        'content' => $this->getLayout()->createBlock('product/adminhtml_product_index_edit_tab_media')->toHtml(),
        'active'    => ( $this->getRequest()->getParam('tab') == 'Images' ) ? true : false,
    ));

        return parent::_beforeToHtml();
    }
    // public function getProduct()
    // {
    //     if (!($this->getData('product') instanceof Mage_Catalog_Model_Product)) {
    //         $this->setData('product', Mage::registry('product'));
    //     }
    //     return $this->getData('product');
    // }


    // protected function _prepareLayout()
    // {
    //     $product = $this->getProduct();
    //     print_r($product)
    //     if (!($setId = $product->getAttributeSetId())) {
    //         $setId = $this->getRequest()->getParam('set', null);
    //     }

    //     if ($setId) {
    //         $groupCollection = Mage::getResourceModel('eav/entity_attribute_group_collection')
    //             ->setAttributeSetFilter($setId)
    //             ->setSortOrder()
    //             ->load();

    //         foreach ($groupCollection as $group) {
    //             $attributes = $product->getAttributes($group->getId(), true);
    //             // do not add groups without attributes

    //             foreach ($attributes as $key => $attribute) {
    //                 if( !$attribute->getIsVisible() ) {
    //                     unset($attributes[$key]);
    //                 }
    //             }

    //             if (count($attributes)==0) {
    //                 continue;
    //             }
    //             $this->addTab('group_'.$group->getId(), array(
    //                 'label'     => Mage::helper('catalog')->__($group->getAttributeGroupName()),
    //                 'content'   => $this->_translateHtml($this->getLayout()->createBlock($this->getAttributeTabBlock(),
    //                     'adminhtml.catalog.product.edit.tab.attributes')->setGroup($group)
    //                         ->setGroupAttributes($attributes)
    //                         ->toHtml()),
    //             ));
    //         }
    //     }
    // }
}