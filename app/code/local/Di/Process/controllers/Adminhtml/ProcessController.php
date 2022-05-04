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
			$process = Mage::getModel('process/process')->load($id);
			if (!$process->getId()) 
			{
				throw new Exception("No record found.", 1);
			}
			$model = Mage::getModel($process->getData()['request_model']);
			$fileName = $model->setProcess($process)->uploadFile();
			// $processModel->uploadFile();
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
			$process = Mage::getModel('process/process')->load($id);
			if (!$process->getId()) 
			{
				throw new Exception("No record found.", 1);
			}
			$model = Mage::getModel($process->getData()['request_model']);
			$fileName = $model->setProcess($process)->verify();
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('process')->__('File Verified successfully.'));
			$this->_redirect('*/*/');
		} 
		catch (Exception $e) 
		{
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			$this->_redirect('*/*/');	
		}
	}

	public function executeAction()
	{
		try 
		{
			$id = (int)$this->getRequest()->getParam('id');
			$process = Mage::getModel('process/process')->load($id);
			if (!$process->getId()) 
			{
				throw new Exception("No record found.", 1);
			}
			$this->_prepareProcessEntryVariables($process);
			// $this->_redirect('*/*/processEntry');
			$this->loadLayout()
			->_setActiveMenu('process')
			->_addContent($this->getLayout()->createBlock('process/adminhtml_process_execute'))
			->renderLayout();
		} 
		catch (Exception $e) 
		{
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			$this->_redirect('*/*/');	
		}
	}

	public function _prepareProcessEntryVariables($process)
	{
		$sessionVariables = [
			'processId'			=> 	$process->getId(),
			'totalCount'		=>	0,
			'perRequestCount'	=>	0,
			'totalRequest'		=>	0,
			'currentRequest'	=>	0,
		];

		$processEntry = Mage::getModel('process/entry');
		$select = $processEntry->getCollection()
				->getSelect()
				->reset(Zend_Db_Select::COLUMNS)
				->columns(['count(entry_id)'])
				->where('process_id = ?',$process->getId())
                ->where('start_time IS NULL');
        $entryCount = $processEntry->getResource()->getReadConnection()->fetchOne($select);
        if (!$entryCount) 
        {
        	Mage::getSingleton('core/session')->unsetProcessEntryVariables();
            throw new Exception("No Records Available to process.", 1);
        }
        $sessionVariables['totalCount'] = $entryCount;
        $sessionVariables['perRequestCount'] = $process->getPerRequestCount();
        $sessionVariables['totalRequest'] = ceil($sessionVariables['totalCount'] / $sessionVariables['perRequestCount']);
        $sessionVariables['currentRequest'] = 1;

        Mage::getSingleton('core/session')->setProcessEntryVariables($sessionVariables);
	}

	public function processEntryAction()
	{
		try 
		{
			$sessionVariables = Mage::getSingleton('core/session')->getProcessEntryVariables($sessionVariables);
			if((int)$sessionVariables['currentRequest'] > (int)$sessionVariables['totalRequest'])
            {
                throw new Exception("No Request Available.", 1);
            }
			$id = $sessionVariables['processId'];
			$process = Mage::getModel('process/process')->load($id);
			if (!$process->getId()) 
			{
				throw new Exception("No record found.", 1);
			}
			$model = Mage::getModel($process->getData()['request_model']);
			$model->setProcess($process)->execute();

			sleep(2);
			$reload = false;
			if((int)$sessionVariables['currentRequest'] == (int)$sessionVariables['totalRequest'])
            {
                $reload = true;
            }
            $sessionVariables['currentRequest'] += 1;
        	Mage::getSingleton('core/session')->setProcessEntryVariables($sessionVariables);
	
			$response = [
	                'status' => 'success',
	                'reload' => $reload,
               		'sessionVariables' => $sessionVariables,
                	'message' => "Processing : " . ($sessionVariables['currentRequest'] - 1). "/" . ($sessionVariables['totalRequest'])
	            ];

	        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
			
		} 
		catch (Exception $e) 
		{
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			$this->_redirect('*/*/');	
		}

	}
	public function csvDownloadAction()
	{
		try {
			
			$id = (int)$this->getRequest()->getParam('id');
			$process = Mage::getModel('process/process')->load($id);
			if (!$process->getId()) 
			{
				throw new Exception("No record found.", 1);
			}
			$model = Mage::getModel($process->getData()['request_model']);
			$model->setProcess($process);
			
	        $this->_prepareDownloadResponse($process->getFileName(), $model->downloadSample());
	        $this->_getSession()->addSuccess($this->__('File downloaded.'));
			$this->_redirect('*/*/');	
		} 
		catch (Exception $e) 
		{
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			$this->_redirect('*/*/');	
		}
   
	}

	public function invalidReportAction()
	{
		try {
			
			$id = (int)$this->getRequest()->getParam('id');
			$process = Mage::getModel('process/process')->load($id);
			if (!$process->getId()) 
			{
				throw new Exception("No record found.", 1);
			}
			$model = Mage::getModel($process->getData()['request_model']);
			$model->setProcess($process);
	        $this->_prepareDownloadResponse($process->getFileName(), $model->invalidReport());
	        $this->_getSession()->addSuccess($this->__('File downloaded.'));
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

    public function massProcessAction()
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
			    	$adapter = $process->getResource()->getReadConnection();
	            	$conditions = array(
	                'start_time IS NOT NULL', 
	                'end_time IS NOT NULL', 
	                'process_id = '.$processId, 
		            );
			        $result = $adapter->delete('process_entry',$conditions);
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
	public function massPendingAction()
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
			    	$adapter = $process->getResource()->getReadConnection();
	            	$conditions = array(
	                'start_time IS NULL', 
	                'process_id = '.$processId, 
		            );
			        $result = $adapter->delete('process_entry',$conditions);
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