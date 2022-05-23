<?php
class Di_Vendor_Block_Adminhtml_Vendor_Grid Extends Mage_Adminhtml_Block_Widget_Grid {

	public function __construct() {
		parent::__construct();
	}

	protected function _getStoreId() {
		$storeId = (int) $this->getRequest()->getParam('store', 0);
		return $storeId;
	}

	protected function _prepareCollection() {
		$collection = Mage::getModel('vendor/vendor')->getCollection()
			->addAttributeToSelect('first_name')
			->addAttributeToSelect('last_name')
			->addAttributeToSelect('status');
		$storeId = $this->_getStoreId();
		$collection->joinAttribute(
			'first_name',
			'vendor/first_name',
			'entity_id',
			null,
			'inner',
			$storeId
		);
		$collection->joinAttribute(
			'last_name',
			'vendor/last_name',
			'entity_id',
			null,
			'inner',
			$storeId
		);
		$collection->joinAttribute(
			'id',
			'vendor/entity_id',
			'entity_id',
			null,
			'inner',
			$storeId
		);
		// $collection->joinAttribute(
		// 	'status',
		// 	'vendor/status',
		// 	'status',
		// 	null,
		// 	'inner',
		// 	$storeId
		// );
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	protected function _prepareColumns() {
		$this->addColumn('id',
			[
				'header' => Mage::helper('vendor')->__('Id'),
				'width' => '50px',
				'index' => 'id',
			]
		);
		$this->addColumn('first_name',
			[
				'header' => Mage::helper('vendor')->__('First Name'),
				'width' => '50px',
				'index' => 'first_name',
			]
		);
		$this->addColumn('last_name',
			[
				'header' => Mage::helper('vendor')->__('Last Name'),
				'width' => '50px',
				'index' => 'last_name',
			]
		);

		$this->addColumn('status',
			[
				'header' => Mage::helper('vendor')->__('Status'),
				'width' => '50px',
				'index' => 'status',
			]
		);

		return parent::_prepareColumns();

	}

	public function getGridUrl() {
		return $this->getUrl('*/*/index', array('_current' => true));
	}

	public function getRowUrl($row) {
		return $this->getUrl('*/*/edit', array(
			'store' => $this->getRequest()->getParam('store'),
			'id' => $row->getId())
		);
	}

}