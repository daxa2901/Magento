<?php 
class Di_Product_Model_Product extends Mage_Core_Model_Abstract
{
	public function _construct()
	{
		$this->_init('product/product');
	}

	public function getFinalPrice()
	{
		$discount = $this->discount;
		if ($this->discount_mode == 2) 
		{
			$discount = ($this->price * ($this->discount/100));
		}
		$discountPrice = $this->price - $this->cost;
		if ($discountPrice < 1) 
		{
			throw new Exception("Cost must be less than price.", 1);
		}
		if ($discount > $discountPrice || $discount < 1) 
		{
			throw new Exception("Discount must be between price and cost.", 1);
		}
		return $this->price - $discount;
	}
	
}