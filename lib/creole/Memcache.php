<?php defined('ROOTPATH') OR die('No direct access allowed.');
	class cache extends Memcache{
		
		protected $host;
		protected $port;
		protected $h_mem;
		protected $enabled = false;
		protected $expire = 30;
		protected $compression = false;
		
		public function __construct($host='localhost', $port=11211){
			$this->host = $host;
			$this->port = (int)$port;
			if( extension_loaded('zlib')){
				$this->compression = MEMCACHE_COMPRESSED;
			}
			$this->conn();
		}
		
		protected function conn(){
			try{
				$this->h_mem = $this->pconnect($this->host, $this->port);
				$this->enabled = true;
			}catch(Exception $e){
				throw new CoreException('Can\'t connect to Memcached server on: '.$this->host);	
			}
		}
		
		final public function isEnabled(){
			return $this->enabled;
		}
		
		public function saveResult($name, $result){
			if( !$this->set($name, $result, $this->compression, $this->expire)){
				throw CoreException('Can\'t save variable to memcache: '.$name);
			}
		}
		
		public function loadResult($name){
			return $this->get($name);
		}
	}
?>