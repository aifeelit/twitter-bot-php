<?php
/**
 * A php logger class.
 * @author p1r0 <lucifers.preacher@gmail.com>
 * @version 0.2
 */
class Logger
{
	private $id;
	private $logLevel = 10;
	private $fileName = "logger.log";
	private $maxFileSize = "1 M";
	private $zipUtil = "bzip2";
	private $zipUtilOptions = "-2"; 
	
	public static $LOG_ALL = 10;
	public static $LOG_WARNINGS = 3;
	public static $LOG_ERRORS = 1;
	public static $LOG_DEBUG = 4;
	public static $LOG_NONE = 0;

	/**
	 * Class constructor
	 *
	 * @param String $id The id for the log messages.
	 * @param String $fileName The file name for the log file.
	 * @return Logger
	 */
	public function Logger($id = "Anonymous Logger", $fileName = "")
	{
		$this->id = $id;
		if($fileName != "")
		{
			$this->fileName = $fileName;
		}

		clearstatcache();
	}

	/**
	 * Set the log level for the logger.
	 * The possible options are:<br>
	 * Logger::LOG_ALL : Logs everything sent to logger<br>
	 * Logger::LOG_WARNING : Logs errors plus warnings<br>
	 * Logger::LOG_ERROR : Logs only errors<br>
	 * Logger::LOG_DEBUG : Logs errors, warning and debug messages.<br>
	 * Logger::LOG_NONE : No output is logged.  
	 * 
	 * @param Integer $logLevel The log level.
	 */
	public function setLogLevel($logLevel)
	{
		$this->logLevel = $logLevel;
	}
	
	/**
	 * Sets the maximum size for the log file, once its reached the
	 * log file will be ziped and a newone opened.
	 *
	 * @param String $fileSize File size in the following format:
	 * Value Unit. Example: 1 M will be 1 megabyte, 1 K 1 kilobyte and so on.
	 */
	public function setMaxFileSize($fileSize)
	{
		/**
		 * @todo Validate the $fileSize format.
		 */
		$this->maxFileSize = $fileSize;
	}
	
	/**
	 * Logs a debug message with the concatentation of all
	 * parameters passed to the function.<br>
	 * If one parameter is NOT string it's var_dump output will
	 * be logged.
	 */
	public function debug()
	{

		$numargs = func_num_args();
		$arg_list = func_get_args();

		if($this->logLevel >= Logger::$LOG_DEBUG)
		{
		
			$msg = "[DEBUG]";
			for ($i = 0; $i < $numargs; $i++)
			{
				$msg .= " ".$this->toString($arg_list[$i]) ;
			}
			$this->save($msg);
		}		 
	}
	
	/**
	 * Logs an error message with the concatentation of all
	 * parameters passed to the function.<br>
	 * If one parameter is NOT string it's var_dump output will
	 * be logged.
	 */
	public function error()
	{
		$numargs = func_num_args();
		$arg_list = func_get_args();

		if($this->logLevel >= Logger::$LOG_ERRORS)
		{
			$msg = "[ERROR]";
			for ($i = 0; $i < $numargs; $i++)
			{			
				$msg .= " ".$this->toString($arg_list[$i]) ;
			}
			$this->save($msg);
		}
	}
	/**
	 * Logs a warning message with the concatentation of all
	 * parameters passed to the function.<br>
	 * If one parameter is NOT string it's var_dump output will
	 * be logged.
	 */
	public function warning()
	{
		$numargs = func_num_args();
		$arg_list = func_get_args();

		if($this->logLevel >= Logger::$LOG_WARNINGS)
		{
			$msg = "[WARNING]";
			for ($i = 0; $i < $numargs; $i++)
			{
				$msg .= " ".$this->toString($arg_list[$i]) ;
			}
			$this->save($msg);
		}
	}

	private function toString($obj)
	{
		$ret = "";

		if(!is_string($obj))
		{
			ob_start();
			var_dump($obj);
			$ret = ob_get_contents();
			ob_clean();
		}
		else
		{
			$ret = $obj;
		}

		return $ret;
	}

	private function save($msg)
	{
		$backtrace = debug_backtrace();

		$time = date("d/m/Y - G:i:s");
		$msg = "[".$this->id."::".basename($backtrace[1]["file"])."::{$backtrace[1]["line"]}][{$time}]".$msg."\n";

		if(!file_exists($this->fileName)){
        	touch($this->fileName);
    	}
    	
    	if(!is_writable($this->fileName)){
    		chmod($this->fileName, 0777);
    	}

		$fh = fopen($this->fileName, "a+");
		fwrite($fh, $msg, strlen($msg));
		fclose($fh);
		
		if(rand(0, 10) == 5)
		{
			$this->rotateLog();
		}
	}

	private function rotateLog()
	{
		clearstatcache();
		$fsize = filesize($this->fileName);
		
		if($fsize >= $this->getMaxFileSize())
		{
			$this->zipLogFile();	
		}		
	}
	
	private function getMaxFileSize()
	{
		//La unidad
		$ar = split(" ", $this->maxFileSize);
		
		$uni = count($ar > 1) ? $ar[1] : "k";
		$val = $ar[0];
		
		switch(strtolower($uni))
		{
			case "k";
				$val *= 1024;
				break;
			case "m":
				$val *= (1024*1024);
			default:
				break;
		}
		
		return $val;
	}

	private function zipLogFile()
	{
		clearstatcache();
		
		$ord = 0;
		
		while(file_exists($this->fileName.".".$ord) || 
			  file_exists($this->fileName.".".$ord.".bz") ||
			  file_exists($this->fileName.".".$ord.".bz2"))
	    {
	  		$ord++;
	    }
	    
	    copy($this->fileName, $this->fileName.".".$ord);
	    unlink($this->fileName);
	    $cmd = $this->zipUtil." ".$this->zipUtilOptions." ".$this->fileName.".".$ord;
	    exec($cmd);
	    
	}
}
?>