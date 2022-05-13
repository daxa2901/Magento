<?php 
class Di_Product_Model_Product extends Mage_Core_Model_Abstract
{
	public function _construct()
	{
		$this->_init('product/product');
	}

	const STATUS_ENABLED = 1;
	const STATUS_DISABLED = 2;
	const STATUS_DEFAULT = 1;
	const STATUS_ENABLED_LBL = 'Active';
	const STATUS_DISABLED_LBL = 'Inactive';
	
	const DISCOUNT_MODE_FIXED = 1;
	const DISCOUNT_MODE_FIXED_LBL = 'Fixed';
	const DISCOUNT_MODE_PERCENTAGE = 2;
	const DISCOUNT_MODE_PERCENTAGE_LBL = 'Percentage';
	const DISCOUNT_MODE_DEFAULT = 1;
	
	public function __construct()
	{
		$this->setResourceClassName('Category_Resource');
		parent::__construct();
	}

	public function getStatus($key = null)
	{
		$statuses = [
			self::STATUS_ENABLED => self::STATUS_ENABLED_LBL,
			self::STATUS_DISABLED => self::STATUS_DISABLED_LBL,
		];

		if(!$key)
		{
			return $statuses;
		}

		if (array_key_exists($key,$statuses)) 
		{	
			return $statuses[$key];
		}
		return self::STATUS_DEFAULT;
	}

	public function getDiscountMode($key = null)
	{
		$discountModes = [
			self::DISCOUNT_MODE_FIXED => self::DISCOUNT_MODE_FIXED_LBL,
			self::DISCOUNT_MODE_PERCENTAGE => self::DISCOUNT_MODE_PERCENTAGE_LBL,
		];

		if(!$key)
		{
			return $discountModes;
		}

		if (array_key_exists($key,$discountModes)) 
		{	
			return $discountModes[$key];
		}
		return self::DISCOUNT_MODE_DEFAULT;
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