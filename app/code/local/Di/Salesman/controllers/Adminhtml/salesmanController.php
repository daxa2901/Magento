<?php
class Di_Salesman_Adminhtml_salesmanController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('salesman/group');
        $this->renderLayout();
    }

    public function editAction()
    {
        $salesmanId = $this->getRequest()->getParam('id');
        $salesmanModel = Mage::getModel('salesman/salesman')->load($salesmanId);
        if ($salesmanModel->getId() || $salesmanId == 0) 
        {

            Mage::register('salesman_data', $salesmanModel);

            $this->loadLayout();
            $this->_setActiveMenu('salesman/salesman');

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('salesman/adminhtml_salesman_index_edit'))
            ->_addLeft($this->getLayout()->createBlock('salesman/adminhtml_salesman_index_edit_tabs'));

            $this->renderLayout();
        } 
        else 
        {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('salesman')->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction()
    {
        $this->_forward('edit');
    }


    public function saveAction()
    {
        try 
        {
            if (!$this->getRequest()->getPost()) 
            {
                throw new Exception("Invalid request.", 1);
            }
            $postData = $this->getRequest()->getPost();
            $salesman = Mage::getModel('salesman/salesman');
            $salesman->setData($postData);
            $id = $this->getRequest()->getParam('id');
            if($id)
            {
                $salesman->salesman_id = $id;
                $salesman->updated_at = date('Y-m-d H:i:s');
            }
            else
            {
                $salesman->created_at = date('Y-m-d H:i:s');
            }
            $salesman->save();
            $this->_redirect('*/*/');
        } 
        catch (Exception $e) 
        {
            // echo $e->getMessage();
            $this->_redirect('*/*/');
            
        }
    }

    public function deleteAction()
    {
        try 
        {
            $id = (int)$this->getRequest()->getParam('id');
            if (!$id) 
            {
                throw new Exception("Invalid id.", 1);
            }
            $salesman = Mage::getModel('salesman/salesman')->load($id);
            if($salesman)
            {
                $delete = $salesman->delete();
                $this->_redirect('*/*/');
            }
            
        } 
        catch (Exception $e) 
        {
            $this->_redirect('*/*/');   
        }
    }
}