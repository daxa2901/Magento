<?php 
class Di_Product_Block_Adminhtml_Product_Index_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('ProductIndexGrid');
		$this->setDefaultSort('product_id');
		$this->setDefaultDir('asc');
		$this->setSaveParametersInSession(true);
	}

	protected function _prepareCollection()
	{
		$collection= Mage::getModel('product/product')->getCollection();
		foreach ($collection->getItems() as $key => $value) 
		{
			$value->status = Mage::getModel('product/product')->getStatus($value->status);
		}
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	protected function _prepareColumns()
	{
		$this->addColumn('product_id', array(
			'header' => Mage::helper('product')->__('ID'),
			'width' => '50px',
			'align' => 'right',
			'index' => 'product_id',
		));
		
		$this->addColumn('name', array(
			'header' => Mage::helper('product')->__('name'),
			'index' => 'name',
		));

		$this->addColumn('price', array(
			'header' => Mage::helper('product')->__('Price'),
			'index' => 'price',
		));

		$this->addColumn('cost', array(
			'header' => Mage::helper('product')->__('Cost'),
			'index' => 'cost',
		));
		
		$this->addColumn('sku', array(
			'header' => Mage::helper('product')->__('sku'),
			'index' => 'sku',
		));

		$this->addColumn('quantity', array(
			'header' => Mage::helper('product')->__('Quantity'),
			'index' => 'quantity',
		));

		$this->addColumn('tax', array(
			'header' => Mage::helper('product')->__('Tax'),
			'index' => 'tax',
		));

		$this->addColumn('discount', array(
			'header' => Mage::helper('product')->__('Discount'),
			'index' => 'discount',
		));

		$this->addColumn('status', array(
			'header' => Mage::helper('product')->__('Status'),
			'index' => 'status',
		));
	
		$this->addColumn('created_at', array(
			'header' => Mage::helper('product')->__('created_at'),
			'index' => 'created_at',
		));

		$this->addColumn('updated_at', array(
			'header' => Mage::helper('product')->__('updated_at'),
			'index' => 'updated_at',
		));

		return parent::_prepareColumns();
	}

	public function getRowUrl($row)
	{
		return $this->getUrl('*/*/edit', array('id'=>$row->getId()));	
	}

	protected function _prepareMassaction()
  	{
    	$this->setMassactionIdField('product_id');
    	$this->getMassactionBlock()->setFormFieldName('product');

	    $this->getMassactionBlock()->addItem('delete', array(
	     'label'    => Mage::helper('product')->__('Delete'),
	     'url'      => $this->getUrl('*/*/massDelete'),
	     'confirm'  => Mage::helper('product')->__('Are you sure?')
	   ));

	   return $this;
	}
}