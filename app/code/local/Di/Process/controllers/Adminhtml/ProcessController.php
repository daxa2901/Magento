<?php 
class Di_Process_Adminhtml_ProcessController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
	{
		$this->loadLayout()
		->_setActiveMenu('process')
		->_addContent($this->getLayout()->createBlock('process/adminhtml_process'))
		->renderLayout();
	}

	public function fileUploadAction()
	{
		$this->loadLayout()
		->_setActiveMenu('process')
		->_addContent($this->getLayout()->createBlock('process/adminhtml_process_upload_edit'))
		->renderLayout();
	}

	public function uploadAction()
	{
		try 
		{
			if (!isset($_FILES['file']['name'])) 
			{
				throw new Exception("No file selected.", 1);
			}
			$id = (int)$this->getRequest()->getParam('id');
			$processModel = Mage::getModel('process/process')->load($id);
			if (!$processModel->getId()) 
			{
				throw new Exception("No record found.", 1);
			}
			$processModel->uploadFile();
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('process')->__('File uploaded successfully.'));
			$this->_redirect('*/*/');
		} 
		catch (Exception $e) 
		{
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			$this->_redirect('*/*/');	
		}
	}
	public function verifyAction()
	{
		try 
		{
			$id = (int)$this->getRequest()->getParam('id');
			$processModel = Mage::getModel('process/process')->load($id);
			if (!$processModel->getId()) 
			{
				throw new Exception("No record found.", 1);
			}
			$processModel->verify();
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('process')->__('File Verified successfully.'));
			$this->_redirect('*/*/');
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

	public function editAction()
	{
		$id = $this->getRequest()->getParam('id');
		$processModel = Mage::getModel('process/process')->load($id);
		if ($processModel->getId() || $id == 0) 
		{
			Mage::register('process_data',$processModel);
			$this->loadLayout();
			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('process/adminhtml_process_edit'))
            ->_addLeft($this->getLayout()->createBlock('process/adminhtml_process_edit_tabs'));

            $this->renderLayout();
		}
		else
		{
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('process')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}

	public function saveAction()
	{
		try 
		{
			if(!$this->getRequest()->getPost())
			{
				throw new Exception("Invalid request.", 1);
			}
			$postData = $this->getRequest()->getPost();
            $id = (int)$this->getRequest()->getParam('id');
            $process = Mage::getModel('process/process')->load($id);
            if (!$process->getId()) 
            {
            	$process->setData('created_date',date('Y-m-d H:i:s'));
            }
            $process->addData($postData);
            $process->save();
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('process')->__('Process saved successfully.'));
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
            $id = (int)$this->getRequest()->getParam('id');
            if (!$id) 
            {
                throw new Exception("Invalid id.", 1);
            }
            $process = Mage::getModel('process/process')->load($id);
            if($process)
            {
                $delete = $process->delete();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('process')->__('Process deleted successfully.'));
                $this->_redirect('*/*/');
            }
        } 
        catch (Exception $e) 
        {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            $this->_redirect('*/*/');   
        }
	}

	public function massDeleteAction() 
    {
        $processIds = $this->getRequest()->getParam('process');
         if(!is_array($processIds))
        {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } 
        else 
        {
            try
            {
                foreach ($processIds as $processId)
                {
                    $process = Mage::getModel('process/process')->load($processId);
                    $result = $process->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('adminhtml')->__('Total of %d record(s) were successfully deleted', count($processIds)));
            } 
            catch (Exception $e)
            {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
            $this->_redirect('*/*/');
    }
}