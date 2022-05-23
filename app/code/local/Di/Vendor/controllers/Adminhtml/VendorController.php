<?php 
class Di_Vendor_Adminhtml_VendorController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
	{
		$this->loadLayout();
		$this->_setActiveMenu('vendor/vendor');
		$this->_addContent($this->getLayout()->createBlock('vendor/adminhtml_vendor'));
		$this->renderLayout();
	}

	public function editAction()
	{

        try 
        {
    		$id = $this->getRequest()->getParam('id');
    		$vendorModel = Mage::getModel('vendor/vendor')->load($id);
    		if ($vendorModel->getId() || $id == 0) 
    		{
    			Mage::register('current_vendor',$vendorModel);
    			$this->loadLayout();
    			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
                $this->_addContent($this->getLayout()->createBlock('vendor/adminhtml_vendor_edit'))
                ->_addLeft($this->getLayout()->createBlock('vendor/adminhtml_vendor_edit_tabs'));
                $this->renderLayout();
    		}
    		else
    		{
    			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('vendor')->__('Item does not exist'));
    		}
        } 
        catch (Exception $e) 
        {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
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
           $vendorData = $this->getRequest()->getPost('vendor');
            $vendor = Mage::getSingleton('vendor/vendor');

            if ($vendorId = $this->getRequest()->getParam('id'))
            {
                if (!$vendor->load($vendorId))
                {
                    throw new Exception("No Row Found");
                }
                Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
            }

            $vendor->addData($vendorData);
            $vendor->save();
            Mage::getSingleton('core/session')->addSuccess("Vendor data added.");
            $this->_redirect('*/*/');

        } 
        catch (Exception $e) 
        {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            $this->_redirect('*/*/');
        }
	}

	public function deleteAction()
	{
		try 
        {
           $vendorModel = Mage::getModel('vendor/vendor');

            if (!($vendorId = (int) $this->getRequest()->getParam('id')))
            {
                throw new Exception('Id not found');
            }

            if (!$vendorModel->load($vendorId))
            {
                throw new Exception('vendor does not exist');
            }

            if (!$vendorModel->delete())
            {
                throw new Exception('Error in delete record', 1);
            }

            Mage::getSingleton('core/session')->addSuccess($this->__('The vendor has been deleted.'));
            $this->_redirect('*/*/');
        } 
        catch (Exception $e) 
        {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            $this->_redirect('*/*/');   
        }
	}
}