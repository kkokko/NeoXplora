<?php 
  namespace sky;
   
  require_once $GLOBALS["SkyFrameworkPath"]."/Communication/RequestJson.php";
  class TRequestJsonEnc extends TRequestJson{
    public function Execute($AParams){
      if(!$this->CheckPost($AParams)){
        return;
      };

      try{
        // decrypt the stream
        $TheEncrypted = file_get_contents("php://input");
        $TheKey = pack('H*', $AParams->RequestKey);
        $TheIV = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_DES, MCRYPT_MODE_ECB), MCRYPT_DEV_RANDOM);
        $TheRequest = mcrypt_decrypt(MCRYPT_DES, $TheKey, $TheEncrypted, MCRYPT_MODE_ECB, $TheIV);
        // remove wrong padding(php bug)
        $ThePadding = 8-ord($TheRequest[strlen($TheRequest)-1]); 
        $TheRequest = substr($TheRequest, 0, -$ThePadding);
        // process the request 
        $TheResponse = $this->ProcessRequest($TheRequest, $AParams);
      }catch (\Exception $e){
        $TheResponse = $this->HandleError($e);
      }
      $this->ProcessResponse($TheResponse, $AParams, false);
    }

    protected function WriteResponse($AResponse, $AParams){
      $TheKey = pack('H*', $AParams->RequestKey);
      $TheIV = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_DES, MCRYPT_MODE_ECB), MCRYPT_DEV_RANDOM);
      $ThePadding = strlen($AResponse) % 8; // 41 - 1
      $AResponse = str_pad($AResponse, strlen($AResponse) + 8 - $ThePadding);
      $AResponse[strlen($AResponse)-1] = chr($ThePadding);
      $TheResponse = mcrypt_encrypt(MCRYPT_DES, $TheKey, $AResponse, MCRYPT_MODE_ECB, $TheIV);
      echo $TheResponse;
    }
  }
?>