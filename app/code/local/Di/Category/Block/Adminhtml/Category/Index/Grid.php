<?php 
class Di_Category_Block_Adminhtml_Category_Index_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('categoryIndexGrid');
		$this->setDefaultSort('category_id');
		$this->setDefaultDir('asc');
		$this->setSaveParametersInSession(true);
	}

	protected function _prepareCollection()
	{
		$category = Mage::getModel('category/category');
		$collection= $category->getCollection();
		$categoryPath = $category->getCategoryToPath();
		foreach ($collection->getItems() as $key => $value) {
			$value->status = $category->getStatus($value->status);
			$value->name = $categoryPath[$value['category_id']];
		}
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	protected function _prepareColumns()
	{
		$this->addColumn('category_id', array(
			'header' => Mage::helper('category')->__('ID'),
			'width' => '50px',
			'align' => 'right',
			'index' => 'category_id',
		));
		
		$this->addColumn('name', array(
			'header' => Mage::helper('category')->__('name'),
			'index' => 'name',
		));

		$this->addColumn('parent_id', array(
			'header' => Mage::helper('category')->__('parent_id'),
			'index' => 'parent_id',
		));
		
		$this->addColumn('path', array(
			'header' => Mage::helper('category')->__('path'),
			'index' => 'path',
		));
		
		$this->addColumn('status', array(
			'header' => Mage::helper('category')->__('Status'),
			'index' => 'status',
		));
	
	
		$this->addColumn('created_at', array(
			'header' => Mage::helper('category')->__('created_at'),
			'index' => 'created_at',
		));

		$this->addColumn('updated_at', array(
			'header' => Mage::helper('category')->__('updated_at'),
			'index' => 'updated_at',
		));

		return parent::_prepareColumns();
	}

	public function getRowUrl($row)
	{
		return $this->getUrl('*/*/edit', array('id'=>$row->getId()));	
	}
}