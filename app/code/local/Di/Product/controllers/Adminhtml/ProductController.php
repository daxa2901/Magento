<?php 
class Di_Product_Adminhtml_ProductController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
	{
		$this->loadLayout();
        $this->_setActiveMenu('product/group');
		$this->renderLayout();
	}

	public function editAction()
	{

		$id = $this->getRequest()->getParam('id');
		$productModel = Mage::getModel('product/product')->load($id);
		if ($productModel->getId() || $id == 0) 
		{
			Mage::register('product_data',$productModel);
			$this->loadLayout();
			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('product/adminhtml_product_index_edit'))
            ->_addLeft($this->getLayout()->createBlock('product/adminhtml_product_index_edit_tabs'));

            $this->renderLayout();
		}
		else
		{
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('product')->__('Item does not exist'));
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
            $product = Mage::getModel('product/product');
            $product->setData($postData);
            $id = $this->getRequest()->getParam('id');
            if($id)
            {
                $product->product_id = $id;
                $product->updated_at = date('Y-m-d H:i:s');
            }
            else
            {
                $product->created_at = date('Y-m-d H:i:s');
            }
            $product->getFinalPrice();
            $product->save();
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
            $product = Mage::getModel('product/product')->load($id);
            if($product)
            {
                $delete = $product->delete();
                $this->_redirect('*/*/');
            }
        } 
        catch (Exception $e) 
        {
            $this->_redirect('*/*/');   
        }
	}
}