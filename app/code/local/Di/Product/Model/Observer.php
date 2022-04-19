<?php 
class Di_Product_Model_Observer extends Mage_Core_Model_Abstract
{
	public function callMe(Varien_Event_Observer $observer)
	{
		// echo "<pre>";
		echo "string";
		// print_r($observer->getEvent()->getProduct());
		die();
	}
	public function beforeRedirect(Varien_Event_Observer $observer)
	{
		// print_r($observer->getEvent()->getProduct());
		echo "111";
		// die();
	}
}