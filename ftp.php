<?php
//Thanks for iYETER on http://stackoverflow.com/questions/927341/upload-entire-directory-via-php-ftp

class FtpNew {
	private $connectionID;
	private $ftpSession = false;
	private $blackList = array('.', '..', 'Thumbs.db');
	public function __construct($ftpHost = "", $port = "21") {
		if ($ftpHost != "") $this->connectionID = ftp_connect($ftpHost, $port);
	}

	public function __destruct() {
		$this->disconnect();
	}

	public function connect($ftpHost, $port=21) {     
		$this->disconnect();
		$this->connectionID = ftp_connect($ftpHost, $port);
		return $this->connectionID;
	}

	public function login($ftpUser, $ftpPass) {
		if (!$this->connectionID) throw new Exception("Connection not established.", -1);
		$this->ftpSession = ftp_login($this->connectionID, $ftpUser, $ftpPass);
		return $this->ftpSession;
	}

	public function disconnect() {
		if (isset($this->connectionID)) {
			ftp_close($this->connectionID);
			unset($this->connectionID);
		}
	}

	public function send_recursive_directory($localPath, $remotePath) {
		return $this->recurse_directory($localPath, $localPath, $remotePath);
	}

	private function recurse_directory($rootPath, $localPath, $remotePath) {
		$errorList = array();
		if (!is_dir($localPath)) throw new Exception("Invalid directory: $localPath");
		chdir($localPath);
		$directory = opendir(".");
		while ($file = readdir($directory)) {
			if (in_array($file, $this->blackList)) continue;
			if (is_dir($file)) {
				$errorList["$remotePath/$file"] = $this->make_directory("$remotePath/$file");
				$errorList[] = $this->recurse_directory($rootPath, "$localPath/$file", "$remotePath/$file");
				chdir($localPath);
			} else {
				$errorList["$remotePath/$file"] = $this->put_file("$localPath/$file", "$remotePath/$file");
			}
		}
		return $errorList;
	}

	public function make_directory($remotePath) {
		$error = "";
		try {
			ftp_mkdir($this->connectionID, $remotePath);
		} catch (Exception $e) {
			if ($e->getCode() == 2) $error = $e->getMessage(); 
		}
		return $error;
	}

	public function put_file($localPath, $remotePath) {
		$error = "";
		try {
			ftp_pasv($this->connectionID, true);
			ftp_put($this->connectionID, $remotePath, $localPath, FTP_BINARY); 
		} catch (Exception $e) {
			if ($e->getCode() == 2) $error = $e->getMessage(); 
		}
		return $error;
	}
}

// Read Arguements from the commandline
function arguments($argv) {
  $_ARG = array();
  foreach ($argv as $arg) {
    if (preg_match('#^-{1,2}([a-zA-Z0-9]*)=?(.*)$#', $arg, $matches)) {
      $key = $matches[1];
      switch ($matches[2]) {
        case '':
        case 'true':
          $arg = true;
          break;
        case 'false':
          $arg = false;
          break;
        default:
          $arg = $matches[2];
      }
      $_ARG[$key] = $arg;
    }else {
      $_ARG['input'][] = $arg;
    }
  }
  return $_ARG;
}

// FTP Operation
$argv = arguments($argv);
//print_r($argv); die;
$ftp = new FtpNew($argv["host"], $argv["port"]);
$ftpSession = $ftp->login($argv["username"], $argv["password"]);
if (!$ftpSession) die("Failed to connect.");
$errorList = $ftp->send_recursive_directory($argv["source"], $argv["target"]);
print_r($errorList);
$ftp->disconnect();