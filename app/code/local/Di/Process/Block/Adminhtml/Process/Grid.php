<?php 
class Di_Process_Block_Adminhtml_Process_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		$this->_blockGroup = 'process';
    $this->_controller = 'adminhtml_process';
    $this->_headerText = Mage::helper('process')->__('Manage Process');
    $this->_addButtonLabel = Mage::helper('process')->__('Add New Process ');
    parent::__construct();
  }

  public function _prepareCollection()
  {
    $collection = Mage::getModel('process/process')->getCollection();
    foreach ($collection->getItems() as $key => $value) 
    {
      $value->type_id = Mage::getModel('process/process')->getTypes($value->type_id);
      $value->group_id = Mage::getModel('process/process')->getGroupName()[$value->group_id];
    }
    $this->setCollection($collection);
    return parent::_prepareCollection();
  }

  public function _prepareColumns()
  {
   $this->addColumn('process_id', array(
    'header'    => Mage::helper('process')->__('ID'),
    'align'     =>'right',
    'width'     => '50px',
    'index'     => 'process_id',
  ));   

   $this->addColumn('group_id', array(
    'header'    => Mage::helper('process')->__('Group'),
    'align'     =>'right',
    'width'     => '50px',
    'index'     => 'group_id',
  ));   

   $this->addColumn('name', array(
    'header'    => Mage::helper('process')->__('Name'),
    'align'     =>'right',
    'width'     => '50px',
    'index'     => 'name',
  ));   

   $this->addColumn('type_id', array(
    'header'    => Mage::helper('process')->__('Type'),
    'align'     =>'right',
    'width'     => '50px',
    'index'     => 'type_id',
  ));   

   $this->addColumn('per_request_count', array(
    'header'    => Mage::helper('process')->__('per_request_count'),
    'align'     =>'right',
    'width'     => '50px',
    'index'     => 'per_request_count',
  ));   

   $this->addColumn('request_interval', array(
    'header'    => Mage::helper('process')->__('per_request_count'),
    'align'     =>'right',
    'width'     => '50px',
    'index'     => 'per_request_count',
  ));   

   $this->addColumn('request_model', array(
    'header'    => Mage::helper('process')->__('request_model'),
    'align'     =>'right',
    'width'     => '50px',
    'index'     => 'request_model',
  ));   

   $this->addColumn('file_name', array(
    'header'    => Mage::helper('process')->__('file_name'),
    'align'     =>'right',
    'width'     => '50px',
    'index'     => 'file_name',
  ));   

   $this->addColumn('created_date', array(
    'header'    => Mage::helper('process')->__('created_date'),
    'align'     =>'right',
    'width'     => '50px',
    'index'     => 'created_date',
  ));   

   $this->addColumn('action',array(
     'header'    =>  $this->__('Action'),
     'width'     => '100',
     'type'      => 'action',
     'getter'    => 'getId',
     'actions'   => array(
         array(
             'caption'   => $this->__('Upload'),
             'url'       => array('base'=> '*/*/fileUpload'),
             'field'     => 'id'
         ) 
    ),
     'filter'    => false,
     'sortable'  => false,
     'is_system' => true,
  ));

  $this->addColumn('Verify',array(
     'header'    =>  $this->__('Action'),
     'width'     => '100',
     'type'      => 'action',
     'getter'    => 'getId',
     'actions'   => array(
         array(
             'caption'   => $this->__('Verify'),
             'url'       => array('base'=> '*/*/verify'),
             'field'     => 'id'
         ) 
        
     ),
     'filter'    => false,
     'sortable'  => false,
     'is_system' => true,
  ));

  $this->addColumn('Execute',array(
     'header'    =>  $this->__('Action'),
     'width'     => '100',
     'type'      => 'action',
     'getter'    => 'getId',
     'actions'   => array(
        
         array(
             'caption'   => $this->__('Execute'),
             'url'       => array('base'=> '*/*/execute'),
             'field'     => 'id'
         )
     ),
     'filter'    => false,
     'sortable'  => false,
     'is_system' => true,
  ));

  $this->addColumn('download',array(
     'header'    =>  $this->__('Download Sample'),
     'width'     => '100',
     'type'      => 'action',
     'getter'    => 'getId',
     'actions'   => array(
         array(
             'caption'   => $this->__('Sample File'),
             'url'       => array('base'=> '*/*/csvDownload'),
             'field'     => 'id'
         ),
     ),
     'filter'    => false,
     'sortable'  => false,
     'is_system' => true,
  ));


  $this->addColumn('Download',array(
     'header'    =>  $this->__('Download invalid report'),
     'width'     => '100',
     'type'      => 'action',
     'getter'    => 'getId',
     'actions'   => array(
         array(
             'caption'   => $this->__('Invalid Report'),
             'url'       => array('base'=> '*/*/invalidReport'),
             'field'     => 'id'
         ),
     ),
     'filter'    => false,
     'sortable'  => false,
     'is_system' => true,
  ));

   return parent::_prepareColumns();
 }

  public function getRowUrl($row)
  {
    return $this->getUrl('*/*/edit', array('id'=>$row->getId()));	
  }
  
  protected function _prepareMassaction()
  {
    $this->setMassactionIdField('process_id');
    $this->getMassactionBlock()->setFormFieldName('process');

    $this->getMassactionBlock()->addItem('delete', array(
     'label'    => Mage::helper('process')->__('Delete'),
     'url'      => $this->getUrl('*/*/massDelete'),
     'confirm'  => Mage::helper('process')->__('Are you sure?')
   ));

    $this->getMassactionBlock()->addItem('process', array(
     'label'    => Mage::helper('process')->__('Processed Entry'),
     'url'      => $this->getUrl('*/*/massProcess'),
     'confirm'  => Mage::helper('process')->__('Are you sure?')
   ));

    $this->getMassactionBlock()->addItem('pending', array(
     'label'    => Mage::helper('process')->__('Pending Entry'),
     'url'      => $this->getUrl('*/*/massPending'),
     'confirm'  => Mage::helper('process')->__('Are you sure?')
   ));

    $this->getMassactionBlock()->addItem('all_entry', array(
     'label'    => Mage::helper('process')->__('Delete All Entry'),
     'url'      => $this->getUrl('*/*/massDeleteAllEntry'),
     'confirm'  => Mage::helper('process')->__('Are you sure?')
   ));

    return $this;
}

}