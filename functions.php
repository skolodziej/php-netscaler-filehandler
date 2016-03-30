<?php
 function getAuthCookie($nitroNSIP,$nitroUser,$nitroPass) {
    $nitroUrl    = "http://$nitroNSIP/nitro/v1/config/login/";
	$nitroReq    = "POST";
	$nitroData   = '{"login":{"username":"'.$nitroUser.'","password":"'.$nitroPass.'"}}';
	$nitroHeader = "Content-Type: application/vnd.com.citrix.netscaler.login+json";

    $ch = curl_init($nitroUrl);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLINFO_HEADER_OUT, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER,array($nitroHeader));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $nitroData);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $nitroReq);
    $result = curl_exec($ch);
   
	preg_match_all('/^Set-Cookie:\s*([^\r\n]*)/mi', $result, $ms);
	$cookies     = array();
	foreach ($ms[1] as $m) {
	    list($name, $value) = explode('=', $m, 2);
	    $cookies[$name]     = $value;
	}
    
	curl_close($ch);
    return $cookies["NITRO_AUTH_TOKEN"];
 }
 
 function getFile($nitroNSIP,$authToken,$fileName,$fileLocation) {
    $nitroUrl    = "http://$nitroNSIP/nitro/v1/config/systemfile/$fileName?args=filelocation:$fileLocation";
	$nitroReq    = "GET";
	$nitroData   = '';
	$nitroHeader = "Cookie: NITRO_AUTH_TOKEN=$authToken";

	$ch = curl_init($nitroUrl);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER,array($nitroHeader));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $nitroData);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $nitroReq);
    $data = curl_exec($ch);
    curl_close($ch);
    $file = json_decode($data, true);
    $file = $file['systemfile'][0];
    return $plainFile = base64_decode($file['filecontent']);
 }
 
 function sendFile($nitroNSIP,$authToken,$fileName,$localFileLocation,$fileLocation) {
    $file = file_get_contents($localFileLocation.$fileName, true);
    $filecontent = base64_encode($file);
    
    $nitroUrl    = "http://$nitroNSIP/nitro/v1/config/systemfile";
	$nitroReq    = "POST";
	$nitroData   = '{"systemfile": {"filename": "'.$fileName.'","filelocation": "'.$fileLocation.'","filecontent":"'.$filecontent.'","fileencoding": "BASE64"}}';
    $nitroCookie = "Cookie:NITRO_AUTH_TOKEN=$authToken";
    $nitroHeader = "Content-Type:application/vnd.com.citrix.netscaler.systemfile+json";
    
    $ch = curl_init($nitroUrl);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER,array($nitroCookie,$nitroHeader));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $nitroData);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $nitroReq);
    $data = curl_exec($ch);
    $return = json_decode($data, true);
    print_r ($return);
    curl_close($ch);  
 }
 
 function logout($nitroNSIP,$authToken) {
	$nitroUrl    = "http://$nitroNSIP/nitro/v1/config/logout/";
	$nitroReq    = "POST";
	$nitroData   = '{"logout":{}}';
	$nitroCookie = "Cookie:NITRO_AUTH_TOKEN=$authToken";
	$nitroHeader = "Content-Type: application/vnd.com.citrix.netscaler.logout+json";

	$ch = curl_init($nitroUrl);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER,array($nitroCookie,$nitroHeader));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $nitroData);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $nitroReq);
    
	curl_exec($ch);
	curl_close($ch);

	unset($authToken);
}
?>