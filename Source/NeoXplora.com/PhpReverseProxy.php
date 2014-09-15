<?php

class PhpReverseProxy{
	public $port, $host, $content, $forward_path, $content_type, $user_agent, $XFF, $request_method, $cookie;		
	private $http_code, $version, $resultHeader, $http_code_with_description;
	
	public $enableLog;
	
	public $HTTP_CODE_PAGE_NOT_FOUND = '404';	
	public $HTTP_CODE_DESC_PAGE_NOT_FOUND = 'Not Found';
	
	function logDebug($function, $msg, $htmlComment=true) {	
		if($this->enableLog){
			$output = "[DEBUG] [$function] $msg\n";
			$myFile ="ProxyLog.txt";
			$fh = fopen($myFile, 'a');
			fwrite($fh, $output);
		}
	}
	
	function __construct(){
		$this->version="PHP Reverse Proxy (PRP) 1.0";
		$this->port="8080";
		$this->host="127.0.0.1";
		$this->content="";
		$this->forward_path="";
		$this->path="";
		$this->content_type="";
		$this->user_agent="";
		$this->http_code="";
		$this->http_code_with_description="";
		$this->XFF="";
		$this->request_method="GET";
		$this->cookie="";
		$this->enableLog=false;
	}
	function translateURL($serverName) {
		$pieces = explode("/", $_SERVER['REQUEST_URI']);
		$this->path=$this->forward_path."/".$pieces[count($pieces)-1];
		if($_SERVER['QUERY_STRING']=="")
			return $this->translateServer($serverName).$this->path;
		else
		return $this->translateServer($ServerName).$this->path."?".$_SERVER['QUERY_STRING'];
	}
	function translateServer($serverName) {
		$s = empty($_SERVER["HTTPS"]) ? ''
			: ($_SERVER["HTTPS"] == "on") ? "s"
			: "";
		$protocol = $this->left(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
		if($this->port=="") 
			return $protocol."://".$serverName;
		else
			return $protocol."://".$serverName.":".$this->port;
	}
	function left($s1, $s2) {
		return substr($s1, 0, strpos($s1, $s2));
	}
	function preConnect(){
		$this->logDebug("preConnect", "start preConnect", true);
		$this->user_agent=$_SERVER['HTTP_USER_AGENT'];
		$this->request_method=$_SERVER['REQUEST_METHOD'];
		$tempCookie="";
		foreach ($_COOKIE as $i => $value) {
			$tempCookie=$tempCookie." $i=$_COOKIE[$i];";
		}
		$this->cookie=$tempCookie;
		if(empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$this->XFF=$_SERVER['REMOTE_ADDR'];
		} else {
			$this->XFF=$_SERVER['HTTP_X_FORWARDED_FOR'].", ".$_SERVER['REMOTE_ADDR'];
		}
	}
	function connect(){
		$this->logDebug("connect", "start connect", true);
		$this->preConnect();
		$ch=curl_init();
		if($this->request_method=="POST"){
		  curl_setopt($ch, CURLOPT_POST,1);
		  curl_setopt($ch, CURLOPT_POSTFIELDS,file_get_contents("php://input"));
		}
		$url = $this->translateURL($this->host);
		$this->logDebug("connect", "translate URL = ".$url, true);
		curl_setopt($ch,CURLOPT_URL,$this->translateURL($this->host));
		curl_setopt($ch,CURLOPT_HTTPHEADER, 
		  Array(
			"X-Forwarded-For: ".$this->XFF,
			"User-Agent: ".$this->user_agent
		  ));
		if($this->cookie!=""){
		  curl_setopt($ch,CURLOPT_COOKIE,$this->cookie);
		}
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true); 
		curl_setopt($ch,CURLOPT_AUTOREFERER,true); 
		curl_setopt($ch,CURLOPT_HEADER,true);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		$output=curl_exec($ch);
		$info	= curl_getinfo( $ch );
		curl_close($ch);
		$this->postConnect($info,$output);
	}
	function postConnect($info,$output){
		$this->logDebug("postConnect", "start postConnect ", true);		
		if($output == FAlSE){
			//error connectiong to server
			$this->http_code=$this->HTTP_CODE_PAGE_NOT_FOUND;
			$this->http_code_with_description="HTTP/1.1 ".$this->HTTP_CODE_PAGE_NOT_FOUND.$this->HTTP_CODE_DESC_PAGE_NOT_FOUND;
		}else{
			//response received from server
			$this->content_type=$info["content_type"];
			$this->http_code=$info['http_code'];
			$this->resultHeader=substr($output,0,$info['header_size']);			
			$resultHeaderLines = explode("\r\n", $this->resultHeader);
			if(count($resultHeaderLines) > 0 ){
				$this->http_code_with_description=trim(str_replace("HTTP/1.1", "", $resultHeaderLines[0]));
			}else{
				$this->http_code_with_description="";
			}      
			$content=substr($output,$info['header_size']);
			$this->content=$content;		
		}
		$this->logDebug("postConnect", " httpCode = ".$this->http_code.", resultHeader = ".$this->resultHeader.".http_code_with_description = ".$this->http_code_with_description.", output =".$output, true);
	}
	
	function output(){
		$this->logDebug("output", "start output", true);
		$currentTimeString=gmdate("D, d M Y H:i:s",time());
		header($this->http_code_with_description);
		header("Date: Wed, $currentTimeString GMT");
		header("Content-Type: ".$this->content_type);
		header("Server: $this->version");
		preg_match("/Set-Cookie:[^\n]*/i",$this->resultHeader,$result);
		foreach($result as $i=>$value){
		  header($result[$i]);
		}			
		echo($this->content);
	}	
}
?>