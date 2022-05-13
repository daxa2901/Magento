<?php

class Di_Salesman_Block_Adminhtml_Salesman_Index_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('salesman_index');
        $this->setDefaultSort('type');
        $this->setDefaultDir('asc');
        $this->setSaveParametersInSession(true);

    }

    /**
     * Init salesman groups collection
     * @return void
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('salesman/salesman')->getCollection();
        foreach ($collection->getItems() as $key => $value) 
        {
            $value->status = Mage::getModel('salesman/salesman')->getStatus($value->status);
        }
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Configuration of grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('salesman_id', array(
            'header' => Mage::helper('salesman')->__('ID'),
            'width' => '50px',
            'align' => 'right',
            'index' => 'salesman_id',
        ));

        $this->addColumn('firstName', array(
            'header' => Mage::helper('salesman')->__('First Name'),
            'index' => 'firstName',
        ));

        $this->addColumn('lastName', array(
            'header' => Mage::helper('salesman')->__('Last Name'),
            'index' => 'lastName',
        ));

        $this->addColumn('email', array(
            'header' => Mage::helper('salesman')->__('Email'),
            'index' => 'email',
            'width' => '200px'
        ));

        $this->addColumn('mobile', array(
            'header' => Mage::helper('salesman')->__('Mobile'),
            'index' => 'mobile',
        ));

        $this->addColumn('percentage', array(
            'header' => Mage::helper('salesman')->__('percentage'),
            'index' => 'percentage',
        ));

        $this->addColumn('status', array(
            'header' => Mage::helper('salesman')->__('Status'),
            'index' => 'status',
        ));

        $this->addColumn('createdAt', array(
            'header' => Mage::helper('salesman')->__('created At'),
            'index' => 'created_at',
        ));

        $this->addColumn('updatedAt', array(
            'header' => Mage::helper('salesman')->__('updated At'),
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
        $this->setMassactionIdField('salesman_id');
        $this->getMassactionBlock()->setFormFieldName('salesman');

        $this->getMassactionBlock()->addItem('delete', array(
         'label'    => Mage::helper('salesman')->__('Delete'),
         'url'      => $this->getUrl('*/*/massDelete'),
         'confirm'  => Mage::helper('salesman')->__('Are you sure?')
       ));

       return $this;
    }

}