<?php

class Di_Category_Model_Category extends Mage_Core_Model_Abstract
{
	public function _construct()
	{
		$this->_init('category/category');
	}
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 2;
    const STATUS_DEFAULT = 1;
    const STATUS_ENABLED_LBL = 'Active';
    const STATUS_DISABLED_LBL = 'Inactive';
    
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

	public function getCategoryToPath($id=null)
    {
    	$adapter = $this->getResource()->getReadConnection();
        if (!$id) 
        {
        	$query1 = 'SELECT `category_id`,`path` FROM `category`';
        }
        else
        {
            $path = $adapter->fetchOne("SELECT `path` FROM `category` WHERE `category_id` = {$id}");
            $path = $path.'/%';
            $query1 = "SELECT `category_id`,`path` FROM `category` WHERE `category_id`<> {$id} AND `path` NOT LIKE ('$path')";
        }
        $query = 'SELECT `category_id`,`name` FROM `category`';
    	$categoryName = $adapter->fetchPairs($query);
    	$categoryPath = $adapter->fetchPairs($query1);
        $categories=[];
        if ($categoryPath) 
        {
	        foreach ($categoryPath as $key => $value) 
	        {
                $explodeArray=explode('/', $value);
                $tempArray = [];

                foreach ($explodeArray as $keys => $value) 
                {
                    if(array_key_exists($value,$categoryName))
                    {
                        array_push($tempArray,$categoryName[$value]);
                    }
                }

                $implodeArray = implode('/', $tempArray);
                $categories[$key]= $implodeArray;
	        }
        }
        return $categories;
	}

}