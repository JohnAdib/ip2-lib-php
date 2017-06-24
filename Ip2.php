<?php
/**
	Copyright 2017 IP2IQ
	
	Licensed under the Apache License, Version 2.0 (the "License");
	you may not use this file except in compliance with the License.
	You may obtain a copy of the License at
	
	http://www.apache.org/licenses/LICENSE-2.0
	
	Unless required by applicable law or agreed to in writing, software
	distributed under the License is distributed on an "AS IS" BASIS,
	WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
	See the License for the specific language governing permissions and
	limitations under the License.
*/

	namespace ip2iq;
	
	class Ip2 {
		private $ver;
		private $recordCount;
		private $fh;
		private $dbFilePath;
		
		public function __construct($dbFilePath = null) {
			$this->dbFilePath = $dbFilePath === null ? 
				dirname(__FILE__)."/ip2-cc.dat" : $dbFilePath;
			if(!file_exists($this->dbFilePath)) {
				throw new \Exception("Ip2 database not found!");
			}
			$this->open();
		}
		
		function country($ipAddr) {
			$res = null;
			$ipAddrDec = $this->uint(ip2long($ipAddr));
			$res = $this->searchSegment($ipAddrDec);
			return $res;
		}
		
		function searchSegment($ipAddr) {
			$res = null;
			$bottom = 1;
			$ceil = $this->recordCount;
			
			while($ceil - $bottom > 3) {
				$mid = ($ceil+$bottom)>>1;
				$r = $this->readRecord($this->fh, $mid);
				$beginAddr = $r['beginAddr'];
				
				if($ipAddr > $beginAddr)
					$bottom = $mid;
				else
					$ceil = $mid;
			}
			
			for($i = $bottom; $i <= $ceil; $i++) {
				$r = $this->readRecord($this->fh, $i);
				$beginAddr = $r['beginAddr'];
				$endAddr = $r['endAddr'];
				
				if($ipAddr >= $beginAddr && $ipAddr <= $endAddr) {
					$countryCode = $r['countryCode'];
					$res .= $countryCode;
					//break;
				}
			}
			
			return $res;
		}
		
		function open() {
			if($this->fh === null) {
				$dbFilePath = $this->dbFilePath;
				$this->fh = fopen($dbFilePath, "rb");
				$header = unpack('Nver/NrecordCount', fread($this->fh, 8));
				//var_dump($this->record_count);
				$this->ver = $header['ver'];
				$this->recordCount = $header['recordCount'];
			}
		}
		
		function close() {
			fclose($this->fh);
			$this->fh = null;
		}
		
		function readRecord($fh, $rec_no) {
			fseek($fh, ($rec_no-1)*10+8);
			$line = fread($fh, 10);
			$data = unpack('NbeginAddr/NendAddr/A2countryCode', $line);
			$data['beginAddr'] = $this->uint($data['beginAddr']);
			$data['endAddr'] = $this->uint($data['endAddr']);
			return $data;
		}
		
		function uint($intval) {
			return $intval < 0 ? (($intval & 0x7FFFFFFF) + 0x80000000) : $intval;
		}
		
	}

?>