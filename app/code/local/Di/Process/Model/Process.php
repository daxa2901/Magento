<?php 
class Di_Process_Model_Process extends Mage_Core_Model_Abstract
{
	public function _construct()
	{
		$this->_init('process/process');
	}

	const TYPE_ID_IMPORT = 1;
	const TYPE_ID_EXPORT = 2;
	const TYPE_ID_CRON = 3;
	const TYPE_ID_IMPORT_LBL = 'Import';
	const TYPE_ID_EXPORT_LBL = 'Export';
	const TYPE_ID_CRON_LBL = 'Cron';
	const TYPE_ID_DEFAULT = 1;

	public function getTypes($key = null)
	{
		$types = [
			self::TYPE_ID_IMPORT => self::TYPE_ID_IMPORT_LBL,
			self::TYPE_ID_EXPORT => self::TYPE_ID_EXPORT_LBL,
			self::TYPE_ID_CRON => self::TYPE_ID_CRON_LBL,
		];

		if(!$key)
		{
			return $types;
		}

		if (array_key_exists($key,$types)) 
		{	
			return $types[$key];
		}
		return self::TYPE_ID_DEFAULT;
	}

	public function uploadFile()
    {
    	$uploader = new Varien_File_Uploader('file');
		$uploader->setAllowRenameFiles(true)
		->setAllowedExtensions(['csv'])
		->setAllowCreateFolders(true);
		$uploader->save($this->getFilePath(),$this->file_name);
		return true;
    }

    protected function getFilePath()
    {
    	return Mage::getBaseDir('media').DS.'process'.DS.'import'.DS;
    }

    public function readFile()
    {
    	$headerFlag = false;
		$path = $this->getFilePath().$this->getFileName();
		$csv = new Varien_File_Csv();
		$csvData = $csv->getData($path);
		$headers = [];
		$data = [];
    	foreach ($csvData as $key => $value) 
    	{
    		print_r($value);
    		if ($headerFlag == false) 
    		{
    			// $this->validateHeader();
    			$headerFlag = true;
    			$headers = $value;
    		}
    		else
    		{
				$data[] = array_combine($headers, $value);
    		}
		}
		print_r($data);
		print_r($headers);
		die();
		
    }

    public function verify()
    {
    	$this->readFile();
    }
	public function getGroupName()
    {
        $adapter = $this->getResource()->getReadConnection();
        $query = 'SELECT `group_id`,`name` FROM `process_group`';
        $groupName = $adapter->fetchPairs($query);
        return $groupName;
    }

    public function getProcessName()
    {
        $adapter = $this->getResource()->getReadConnection();
        $query = 'SELECT `process_id`,`name` FROM `process`';
        $processName = $adapter->fetchPairs($query);
        return $processName;
    }

}