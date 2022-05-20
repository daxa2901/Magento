<?php 
class Di_Process_Model_Process_Abstract extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        $this->_init('process/process');
    }

    protected $headers = []; 
    protected $datas = []; 
    protected $invalidHeaders = []; 
    protected $invalidData = [];
    protected $processColumns = [];
    protected $jsonData = [];
    protected $currentRow = [];
    protected $process = null;

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

    public function setProcess($process)
    {
        $this->process = $process;
        return $this;
    }

    public function getProcess()
    {
        return $this->process;
    }

    public function setCurrentRow($currentRow)
    {
        $this->currentRow = $currentRow;
        return $this;
    }

    public function getCurrentRow()
    {
        return $this->currentRow;
    }

    public function getCurrentRowTmp()
    {
        return  array_combine(array_keys($this->getCurrentRow()),array_fill(0, count($this->getCurrentRow()), NULL));
    }
    
    protected function setHeaders($headers)
    {
        $this->headers = $headers;
        return $this;
    }
    protected function getHeaders()
    {
        return $this->headers;
    }
    
    public function addHeader($key,$header)
    {
        $this->headers[$key] = $header;
        return $this;
    }

    public function getHeader($key)
    {
        if (array_key_exists($key,$this->getHeaders())) 
        {
            return $this->headers[$key];
        }
        return null;
    }

    public function removeHeader($key)
    {
        if (array_key_exists($key,$this->getHeaders())) 
        {
            unset($this->headers[$key]);
        }
        return $this;
    }
    
    protected function setJsonData($jsonData)
    {
        $this->jsonData = $jsonData;
        return $this;
    }
    protected function getJsonData()
    {
        return $this->jsonData;
    }
    
    public function addJsonData($key,$jsonData)
    {
        $this->jsonData[$key] = $jsonData;
        return $this;
    }

    public function removeJsonData($key)
    {
        if (array_key_exists($key,$this->getJsonData())) 
        {
            unset($this->jsonData[$key]);
        }
        return $this;
    }
    
    public function setDatas($datas)
    {
        $this->datas = $datas;
        return $this;
    }
    public function getDatas()
    {
        return $this->datas;
    }
    
    public function addDatas($key,$data)
    {
        $this->datas[$key] = $data;
        return $this;
    }

    // public function getDatas($key)
    // {
    //  if (array_key_exists($key,$this->getDatas())) 
    //  {
    //      return $this->datas[$key];
    //  }
    //  return null;
    // }

    public function removeDatas($key)
    {
        if (array_key_exists($key,$this->getDatas())) 
        {
            unset($this->datas[$key]);
        }
        return $this;
    }
    
    protected function setInvalidData($datas)
    {
        $this->invalidData[] = $datas;
        return $this;
    }
    protected function getInvalidDatas()
    {
        return $this->invalidData;
    }

    public function addInvalidData($key,$data)
    {
        $this->invalidData[$key] = $data;
        return $this;
    }

    public function getInvalidData($key)
    {
        if (array_key_exists($key,$this->getInvalidData())) 
        {
            return $this->invalidData[$key];
        }
        return null;
    }

    public function removeInvalidData($key)
    {
        if (array_key_exists($key,$this->getInvalidData())) 
        {
            unset($this->invalidData[$key]);
        }
        return $this;
    }
    
    
    protected function setInvalidHeaders($headers)
    {
        $this->invalidHeaders[] = $headers;
        return $this;
    }
    protected function getInvalidHeaders()
    {
        return $this->invalidHeaders;
    }

    public function addInvalidHeader($key,$header)
    {
        $this->invalidHeaders[$key] = $header;
        return $this;
    }

    public function getInvalidHeader($key)
    {
        if (array_key_exists($key,$this->getInvalidHeaders())) 
        {
            return $this->invalidHeaders[$key];
        }
        return null;
    }

    public function removeInvalidHeader($key)
    {
        if (array_key_exists($key,$this->getInvalidHeaders())) 
        {
            unset($this->invalidHeaders[$key]);
        }
        return $this;
    }
    

    public function uploadFile()
    {
        $uploader = new Varien_File_Uploader('file');
        $uploader->setAllowRenameFiles(false)
        ->setAllowedExtensions(['csv'])
        ->setAllowCreateFolders(true);
        $uploader->save($this->getFilePath(),$this->getProcess()->getFileName());
        return true;
    }

    protected function getFilePath()
    {
        return Mage::getBaseDir('media').DS.'process'.DS.'import'.DS;
    }

    public function getProcessColumns()
    {
        if ($this->processColumns) 
        {
            return $this->processColumns;
        }
        $model = Mage::getModel('process/column');
        $select = $model->getCollection()
                  ->getSelect()
                  ->where('process_id = '.$this->getProcess()->getId());

        $this->processColumns = $model->getResource()->getReadConnection()->fetchAll($select);
        return $this->processColumns;
    }

    public function getRequiredColumns()
    {
        $processColumns = $this->getProcessColumns();
        $requiredColumns = array_map(function ($row)
        {
            if ($row['required'] == 1) 
            {
                return $row['name'];
            }
            return null;
        }, $processColumns);
        return array_filter($requiredColumns);
    }

    // public function getColumnsName()
    // {
    //     $processColumns = array_map(function ($row)
    //     {
    //             return $row['name'];
    //     }, $this->getProcessColumns());
    //     return $processColumns;
       
    // }

    public function validateHeader()
    {
        $requiredColumns = $this->getRequiredColumns();
        $invalidHeaders = array_diff($requiredColumns,$this->getHeaders());
        if ($invalidHeaders) 
        {
            throw new Exception("Missing header(s) : ".implode(',', $invalidHeaders), 1);
        }
        // $processColumnNameArray = $this->getColumnsName();
        // $extraHeaders = array_diff($this->getHeaders(),$processColumnNameArray);
        // if ($extraHeaders) 
        // {
        //     throw new Exception("Extra header(s) : ".implode(',', $extraHeaders), 1);
        // }
        return true;
    }

    protected function validateData()
    {
        if (!$this->getDatas()) 
        {
            throw new Exception("No data available.", 1);
        }
        $data = $this->getDatas();
        foreach ($data as $key => &$value) 
        {
            try 
            {
                $this->_validateRow($value);

                $this->_prepareRow($value);              
            } 
            catch (Exception $e) 
            {
                $this->currentRow['message'] = $e->getMessage();
                $this->addInvalidData($key,$this->currentRow);
                unset($data[$key]);
            }
        }
        $this->setDatas($data);
        return true;
    }

    public function prepareRowForJson(&$row)
    {
        return $row;
    }

    public function _prepareRow(&$row)
    {
        $entry = [
            'process_id' => $this->getProcess()->getProcessId(),
            'identifier' => $this->getIdentifier($row),
            'data'  => null
        ];
        $tableRow =$this->prepareRowForJson($row);
        $entry['data'] = json_encode($tableRow);
        $row = $entry;
    }

    public function validateRow(&$row)
    {
        return $row;
    }
    protected function _validateRow(&$row)
    {
        $this->currentRow = $row;
        $processColumns = $this->getProcessColumns();
        $processColumns = array_combine(array_column($processColumns,'name'), $processColumns);
        $flag = false;
        $tempRow = $this->getCurrentRowTmp();
        
        foreach ($this->currentRow as $key => &$value) 
        {
            try 
            {
                if ($key == 'Index') 
                {
                    continue;
                }
                if (array_key_exists($key,$processColumns)) 
                {
                    $value = $this->validateRowValue($value,$processColumns[$key]);                 
                }
            } 
            catch (Exception $e) 
            {
                $tempRow[$key] = $value;
                $flag = true;
            }
        }
        if ($flag) 
        {
            $flag = false;
            $tempRow['Index'] = $row['Index'];
            $row = $tempRow;
            throw new Exception("Invalid Row", 1);
        }
        $this->validateRow($row);
    }

    public function validateRowValue($value,$processColumnRow)
    {
        if (!$value)
        {
            if ($processColumnRow['required'] == 1) 
            {
                throw new Exception("Invalid", 1);
            } 
            return $value = $processColumnRow['default_value'];
        }

        if ($processColumnRow['type_cast'] == 2) 
        {
            if (!$value = (int)$value) 
            {
                throw new Exception($value, 1);
            }
        }

        if ($processColumnRow['type_cast'] == 4) 
        {
            if (!$value = (double)$value) 
            {
                throw new Exception($value, 1);
            }
        }

        if ($processColumnRow['type_cast'] == 1) 
        {
            if (!$value = (string)$value) 
            {
                throw new Exception($value, 1);
            }
        }
        return $value;
    }

    public function readFile()
    {
        $headerFlag = false;
        $path = $this->getFilePath().$this->getProcess()->getFileName();
        $csv = new Varien_File_Csv();
        $csvData = $csv->getData($path);
        $datas = [];

        foreach ($csvData as $key => $value) 
        {
            if ($headerFlag == false) 
            {
                $headerFlag = true;
                $this->setHeaders($value);
                $this->validateHeader();
            }
            else
            {
                $this->addDatas($key,array_combine($this->getHeaders(), $value));
            }
        }
        
        $data = $this->getDatas();
        $path  = array_column($this->getDatas(), 'path');
        array_multisort($path,SORT_ASC,SORT_STRING,$data);
        // array_multisort($data,SORT_ASC,SORT_STRING);
        $this->setDatas($data);
    }

    public function generateInvalidDataReport()
    {
        $csv = new Varien_File_Csv();
        $data = $this->getInvalidDatas();
        $headers = $this->getHeaders();
        $headers[] = 'message';
        array_splice($data, 0,0,[$headers]);
        $csv->saveData($this->getFilePath(). DS .'invalid'. DS .$this->getProcess()->getFileName(),$data);
    }

    public function processEntries()
    {
        $entryModel = Mage::getModel('process/entry');
        if ($this->getDatas()) 
        {
            $entryModel->getResource()->getReadConnection()->insertOnDuplicate('process_entry',$this->getDatas());
        }
    }

    public function prepareJsonData($row)
    {
        return json_encode($row);
    }

    public function getIdentifier($row)
    {
        return $row['Email'];
    }

    public function verify()
    {
        echo "<pre>";
        $this->readFile();
        $this->validateData();
        $this->generateInvalidDataReport();
        $this->processEntries();
        // print_r($this->getDatas());
        // die();
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

    public function downloadSample()
    {
        $model = Mage::getModel('process/column');
        $select = $model->getCollection()
                  ->getSelect()
                  ->where('process_id = '.$this->getProcess()->getId())
                  ->order('sort_order ASC');
        $columns = $model->getResource()->getReadConnection()->fetchAll($select);
        if (!$columns) 
        {
            throw new Exception("No columns found.", 1);
        }

        $io = new Varien_Io_File();
        $path = Mage::getBaseDir('var') . DS . 'export';
        $name = $this->getProcess()->getFileName();
        $file = $path . DS . $name;
        $io->setAllowCreateFolders(true);
        $io->open(array('path' => $path));
        $io->streamOpen($file, 'w+');
        $io->streamLock(true);
        $finalColumn[] = array_column($columns,'name');
        $finalColumn[] = array_column($columns,'sample_value');
        // $finalColumn[] = array_column($columns,'required');
         
        foreach ($finalColumn as $row) 
        {
            $io->streamWriteCsv($row); 
        }
        $io->streamUnlock();
        $io->streamClose();
        $csv = [
                'type' => 'filename',
                'value' => $file,
                'rm' => '1'
        ];
        return $csv;
    }

    public function invalidReport()
    {
        $path = $this->getFilePath().'invalid'.DS.$this->getProcess()->getFileName();
        $csv = [
                'type' => 'filename',
                'value' => $path,
                'rm' => '1'
        ];
        return $csv;
    }

    public function execute()
    {
        $startTime = date('Y-m-d H:i:s');
        $entry = Mage::getModel('process/entry');
        $select = $entry->getCollection()
                ->getSelect()
                ->where('process_id = ?',$this->getProcess()->getId())
                ->where('start_time IS NULL')
                ->limit($this->getProcess()->getPerRequestCount());
        $entries = $entry->getResource()->getReadConnection()->fetchAll($select);
        if (!$entries) 
        {
            throw new Exception("No entries found.", 1);
        }
        $entryIds = implode(',', array_column($entries, 'entry_id'));
        $query = "UPDATE `process_entry` SET `start_time` = '{$startTime}' WHERE `entry_id` IN ({$entryIds})";
        $entry->getResource()->getReadConnection()->fetchAll($query);
         $this->importEntries($entries);

        $endTime = date('Y-m-d H:i:s');
        $query = "UPDATE `process_entry` SET `end_time` = '{$endTime}' WHERE `entry_id` IN ({$entryIds})";
        $entry->getResource()->getReadConnection()->fetchAll($query);
        return $this;
    }

   public function importEntries($entries)
    {
        $salesman = Mage::getModel('salesman/salesman');
        foreach ($entries as $key => $entry) 
        {
            $salesman->setData(json_decode($entry['data'], true));
            $salesman->created_at = date('Y-m-d H:i:s');
            $salesman->save();
        }
        return true;
    }

}  