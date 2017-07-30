<?php

echo "In <br>";

$URL_UBER_AUTO = "https://auth.uber.com/login/handleanswer";

$CRED_USER = "promolist@list.ru";
$CRED_PSSW = "newperson";

$POST_USER = '{"answer":{"type":"VERIFY_INPUT_EMAIL","userIdentifier":{"email":"' . $CRED_USER .'"}},"init":true}';

$POST_PSSW = '{"answer":{"type":"VERIFY_PASSWORD","password":"'. $CRED_PSSW .'"},"rememberMe":true}';



include('./Requests-master/library/Requests.php');
function checkLogin2(){
	Requests::register_autoloader();
	$headers = array(
	    'Origin' => 'https://auth.uber.com',
	    'Accept-Encoding' => 'gzip, deflate, br',
	    'x-csrf-token' => '1499074714-01-eXMvbgRL4q7my4UoP8zLvdIs-E_Z80hDpSPf1nAovGs',
	    'x-uber-origin' => 'arch-frontend',
	    'Accept-Language' => 'en-US,en;q=0.8',
	    'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.110 Safari/537.36',
	    'content-type' => 'application/json',
	    'Accept' => 'application/json',
	    'Referer' => 'https://auth.uber.com/login/?next_url=https%3A%2F%2Fpartners.uber.com%2Fp3%2F&state=awKSGgA5Xb_DSpLUH-c1LdijJeTXjzjGcv1vdcSDqpI%3D',
	    'Connection' => 'keep-alive',
	    'Cookie' => '_udid=w_a93f38c118b945eb9ff9fe412319d574; utag_main=v_id:015cac0bee14007210af2e5938b405069006b06100718$_sn:2$_ss:1$_st:1499076374951$segment:b$optimizely_segment:a$dc_visit:1$ses_id:1499074574951%3Bexp-session$_pn:1%3Bexp-session; mp_e39a4ba8174726fb79f6a6c77b7a5247_mixpanel=%7B%22distinct_id%22%3A%20%2215cac0bef31918-09562444c140cf-317f0158-c0000-15cac0bef32d7c%22%2C%22%24initial_referrer%22%3A%20%22https%3A%2F%2Fauth.uber.com%2Flogin%2Fsession%22%2C%22%24initial_referring_domain%22%3A%20%22auth.uber.com%22%7D; AMCVS_0FEC8C3E55DB4B027F000101%40AdobeOrg=1; AMCV_0FEC8C3E55DB4B027F000101%40AdobeOrg=1611084164%7CMCMID%7C78808930763352101411630973563674613519%7CMCAAMLH-1499679376%7C6%7CMCAAMB-1499679376%7CNRX38WO0n5BH8Th-nqAG_A%7CMCOPTOUT-1499081776s%7CNONE; mp_mixpanel__c=5; arch-frontend:sess=NYLRuFF-ZlAHEgUhRp-gEg.uCtCoASeHH6LKW5B7lIWjTygnEcN50H5c8UAEHhomU59GK_Almg0JCrWlReWwiOSjuk68F--cJyKTOQ2kRD3u9qd4fwl0CZGJajVFQZAR_IDc2VmRGtCWoussAvMOydSvtZ7CEHfNIhnHmjOyoc6iREJgBN4hwa0VTC7eJhtR7OvFxKpNRbyVnKhConP_NX7p94pxWFkzvFkZdJT_DBk7dgSVQu3a84zYFnJd5Ly9Iqd_ZfKunSPaCcoAM6RPjsCYp2N5ENNtYkMUmy49ksWdUmS6ryITMuBRPY8puwsd7Gcv_BRW_IwK7LcsDUMtX8rWF8CZ1NoWRtmXQhIbzAh4RfmDigneJCtCnEWhtyg-Y1Rt2i84OFgzHu20fVqkK_e_BKvR2SIYh98W8FaBkikYbBXowjmHU163Y0jWh0dMLj83G8GS-YTUnuHfhnAW8Aeucha5EaWrirCpW7hnUqImyxOk3UbKL_2gClVa5RMvDK5i6BOSOpB08yxlZH0qb1i.1499074676721.1209600000.RJEPy_ptiY8q9ipPZMhlG91yTVk8nn6mpuF5zMPfjPg; _gat_UA_7157694_35=1; _ga=GA1.2.1481685025.1497535015; _gid=GA1.2.707105736.1499074529'
	);
	$data = '{"answer":{"type":"VERIFY_INPUT_EMAIL","userIdentifier":{"email":"promolist@list.ru"}},"init":true}';
	$response = Requests::post('https://auth.uber.com/login/handleanswer', $headers, $data);

	return $response;
}


function auth2(){
	Requests::register_autoloader();
	$headers = array(
    'Origin' => 'https://auth.uber.com',
    'Accept-Encoding' => 'gzip, deflate, br',
    'x-csrf-token' => '1499074732-01-L5SKCUDE6AcDGjbHxdKByw-OVKLZNGyBIUf97hGKtt0',
    'x-uber-origin' => 'arch-frontend',
    'Accept-Language' => 'en-US,en;q=0.8',
    'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.110 Safari/537.36',
    'content-type' => 'application/json',
    'Accept' => 'application/json',
    'Referer' => 'https://auth.uber.com/login/session',
    'Connection' => 'keep-alive',
    'Cookie' => '_udid=w_a93f38c118b945eb9ff9fe412319d574; utag_main=v_id:015cac0bee14007210af2e5938b405069006b06100718$_sn:2$_ss:1$_st:1499076374951$segment:b$optimizely_segment:a$dc_visit:1$ses_id:1499074574951%3Bexp-session$_pn:1%3Bexp-session; mp_e39a4ba8174726fb79f6a6c77b7a5247_mixpanel=%7B%22distinct_id%22%3A%20%2215cac0bef31918-09562444c140cf-317f0158-c0000-15cac0bef32d7c%22%2C%22%24initial_referrer%22%3A%20%22https%3A%2F%2Fauth.uber.com%2Flogin%2Fsession%22%2C%22%24initial_referring_domain%22%3A%20%22auth.uber.com%22%7D; AMCVS_0FEC8C3E55DB4B027F000101%40AdobeOrg=1; AMCV_0FEC8C3E55DB4B027F000101%40AdobeOrg=1611084164%7CMCMID%7C78808930763352101411630973563674613519%7CMCAAMLH-1499679376%7C6%7CMCAAMB-1499679376%7CNRX38WO0n5BH8Th-nqAG_A%7CMCOPTOUT-1499081776s%7CNONE; mp_mixpanel__c=5; _ga=GA1.2.1481685025.1497535015; _gid=GA1.2.707105736.1499074529; arch-frontend:sess=MzjicsPmpcRfiHeu10bfbQ.JsLJ3nVcjELfN3yrjnvytf7D97uOCuWEHp4kffiz9KlzSE1BzLGeF3QL3VJegQ_AXmC0d04aGCgPnrCZtriUDKGCjjEbPnwYuN57arOLHSYIxWruMciuS-ENvoXhYMdz5F6KBLZGabs1V80S2cgzSP87XFvoFa64ykrGUkPvkaUG1L33WxYeussv2UtFojKrrs7lr_6f-C72Clclrhpr8GatXcr-qdAsgeWx4G8e3O_gzZI7XSyAOaZq2cSyXgM-9m28rjMjB0RdCK7xzAQfbO0jGS3qSOUOrC5Eq3K9qpLbnQP4rdV4vkeF6UdWd49gRsdnQbOFlBs0Db-yPrFrPt83UhELHpfrYHAd-4gJSeQovhzm5_U522zXel1egr8n-SPv6fLQqxXA1ta3dy6ZtC2yGUp2j2lsILKNsPjMVd4bgDU_nwDuN1pkwDHiPQNbzIwlnuA_BuOni2Tz7VnphCDqNa7NJ2Mt4iI0KxP_0-HEghrWRW5kpwkp2BE0W4mrusAOlzQbKndzDhUXzCMFugQ_KMOTAJSfsJjfn3LztdkRuUuDrhBQc28M0f1sQ8Hbi4adGF4Vkx6lPs1FFqtapw.1499074676721.1209600000.yV4gHQdpkfMNhyeZpxvR0CxSTEYM4et95Fo5U_5oQpo; path=/; expires=Mon, 17 Jul 2017 09:37:57 GMT; secure; httponly'
	);
	$data = '{"answer":{"type":"VERIFY_PASSWORD","password":"newperson"},"rememberMe":true}';
	$response = Requests::post('https://auth.uber.com/login/handleanswer', $headers, $data);

	return $response;
}

function GetURL($sURL, &$paData=null, $aPost=0){

	$oCurl = curl_init();
	$sHeader[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
	$sHeader[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
	$sHeader[] = "Cache-Control: max-age=0";
	$sHeader[] = "Connection: keep-alive";
	$sHeader[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";

	$cookie = '_udid=w_a93f38c118b945eb9ff9fe412319d574; utag_main=v_id:015cac0bee14007210af2e5938b405069006b06100718$_sn:2$_ss:1$_st:1499076374951$segment:b$optimizely_segment:a$dc_visit:1$ses_id:1499074574951%3Bexp-session$_pn:1%3Bexp-session; mp_e39a4ba8174726fb79f6a6c77b7a5247_mixpanel=%7B%22distinct_id%22%3A%20%2215cac0bef31918-09562444c140cf-317f0158-c0000-15cac0bef32d7c%22%2C%22%24initial_referrer%22%3A%20%22https%3A%2F%2Fauth.uber.com%2Flogin%2Fsession%22%2C%22%24initial_referring_domain%22%3A%20%22auth.uber.com%22%7D; AMCVS_0FEC8C3E55DB4B027F000101%40AdobeOrg=1; AMCV_0FEC8C3E55DB4B027F000101%40AdobeOrg=1611084164%7CMCMID%7C78808930763352101411630973563674613519%7CMCAAMLH-1499679376%7C6%7CMCAAMB-1499679376%7CNRX38WO0n5BH8Th-nqAG_A%7CMCOPTOUT-1499081776s%7CNONE; mp_mixpanel__c=5; _ga=GA1.2.1481685025.1497535015; _gid=GA1.2.707105736.1499074529; ';
	$cookie .= 'sid=QA.CAESEIIoNQfgMEfQvsF0kNe_PR8YwLnEzgUiATEqJDc5YmEyYTdmLTliZjUtNDU3Ni1hMDA0LThiNzZmMDI3NzA5ZDJAaVByzvRzx00EdaLGxo1rBh0pmhnP3pzsUvftfTsrYnymMNHOXRhzEdy-ykbuxfe_mYfj7ieFr0SAoy72eh__pDoBMQ.miIE_7Sxn3rRbuua5VG8bmBw_H4jL_mrF_l93K2h7Wk; Domain=.uber.com; Path=/; Expires=Sun, 01 Oct 2017 16:50:08 GMT; HttpOnly; Secure; csid=1.epDzZK53wNec/FFXxaTFZrRQgYnA1/PIYjKGxMRqSJ0=; Domain=auth.uber.com; Path=/; Expires=Sun, 01 Oct 2017 16:50:08 GMT; HttpOnly; Secure; _csid=1.1499100908224.2160h.caxlaXIdTaM4PpOJeScmhniD/d8HGN3AixTd9/9q+3Y=; Domain=.uber.com; Path=/; Expires=Mon, 03 Jul 2017 16:55:08 GMT; HttpOnly; Secure; lsid=; Domain=.uber.com; Path=/; Expires=Thu, 01 Jan 1970 00:00:00 GMT; HttpOnly; Secure; arch-frontend:sess=IU8m5N-igvA5RS4J1CGp9g.QIMkyQVbQN6HnyHxQZ6YSUA9QjVyT8-WJZN8Sx76PAktlqeG_Ak042f5xorKc2S_mI9ibILBHKDvQzyOttSwMNJ59vPIR9j0NQ0V7qZ-vjF_wlHy4QGYTaCaELdJV8Z9hH7YVAbi7bz4SAFrHoylUTBKdpnTXBt5CqaaYccazARBbBjkQxIKZMda32n5BmtK8_9eke9S6MgaUnWY8ve-A8vw9w_gNhzdIc_ksvBkj6Ed8OYA75GiaqYkpPc0DGxP-BE0wojfyWt2F54sHnpocpUaRY5jJ1HXgnUcoLPt-oe8XlQni42yBJaVJZCOhfmc8unFoUFPpRCW2VM4yY8Ri8y6iV-RvwRcdf4E8O9CHynXsYJhV0XKd5ZwlXjZgje9clsc7EysaCup1CRPeO8ymSbSDt_3j_ChTuPEAGjLkB-mUb9zmn6OH6ieTsoo2ue2SyqlOtkRvQFbC_Zj4axeyGUppkzfCIZAX9-yjDAr-KzuzERApIoRq8a8VoU6X4mlWwFEwcOW8d0MSgH6JxlklcnCavacCGT80XMNS9M9sXUDXYrG9xzTfgi_FeoO93e5dZjE_GzohCo-w_orY3qsa9KCa1U9N5AzhDly66Uyeamz2N8WwocySeQD48F3m5nnkAVoJqGv4fsQHeEqjBk1ewZmNoZ-V5VKjvXNRETgD7nrU_W7tPBN8ZrRuwvT6GpKBtbvnxVEXOzhVYZrDMhoMNTwp5F1w8V1ZS2TX2lBWafGMv91gM2Ar_4f0oAcSvhGjY2cxyY5GSSMpeVeqF2KGeMgThCvkZJsQSLCze_8Cu4eWwdY1SXCtP8j_wjEdVIXgfPC9pTVJYEpXvspIg-NOGpeysdMF4PYV8UFLOA9JYg.1499074676721.1209600000.OfDChWnygjfp-R4gJX9yiyTs39rqRsJcLI4k1995d4I; path=/; expires=Mon, 17 Jul 2017 09:37:57 GMT; secure; httponly';
	$sHeader[] = $cookie;
	$sHeader[] = "Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3";
	$sHeader[] = "Host: partners.uber.com";
	$sHeader[] = "Pragma: ";

	curl_setopt($oCurl, CURLOPT_URL, $sURL);
	curl_setopt($oCurl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:53.0) Gecko/20100101 Firefox/53.0');
	curl_setopt($oCurl, CURLOPT_HTTPHEADER, $sHeader);
	curl_setopt($oCurl, CURLOPT_HEADER, true);
	curl_setopt($oCurl, CURLOPT_REFERER, 'https://partners.uber.com/p3/fleet/live');
	curl_setopt($oCurl, CURLOPT_ENCODING, 'gzip, deflate, br');
	curl_setopt($oCurl, CURLOPT_AUTOREFERER, true);
	curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, true);
	//curl_setopt($oCurl, CURLOPT_FOLLOWLOCATION, true); //CURLOPT_FOLLOWLOCATION Disabled...
	curl_setopt($oCurl, CURLOPT_TIMEOUT, 0);
	curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, false);
	
	//curl_setopt($oCurl, CURLOPT_COOKIEJAR, '.cookie');
	//curl_setopt($oCurl, CURLOPT_COOKIEFILE, '.cookie');

	curl_setopt($oCurl, CURLOPT_POST, $aPost!==0 );
	if($aPost) curl_setopt($oCurl, CURLOPT_POSTFIELDS, $aPost);

	$sHtml = curl_exec($oCurl);
	$aStatus = curl_getinfo($oCurl);


	$paData[$sURL]=$aStatus;
	$paData[$sURL]['HTML']=$sHtml;

	curl_close($oCurl);

	// if($aStatus['http_code']!=200){
	// 	fnLog("GetURL: failed ".print_r($aStatus,true));
	// 	return false;
	// }

	return $sHtml;
}

function fnLog($str){
	// global $oDB;
	
	// CC::CheckString($str,CC::CC_STRING_QUOTE);
	// $oDB->Query("INSERT INTO logs (adate,ltext) VALUES (NOW(),'{$str}') ");
	// echo (date("[D M Y H:i:s] : ").$str.PHP_EOL);
	file_put_contents('uber_auth.log', date("Y:m-d H:i:s") . PHP_EOL . $str);
}

function auth(){
	Requests::register_autoloader();
	$headers = array(
    'Origin' => 'https://auth.uber.com',
    'Accept-Encoding' => 'gzip, deflate, br',
    'x-csrf-token' => '1499074732-01-L5SKCUDE6AcDGjbHxdKByw-OVKLZNGyBIUf97hGKtt0',
    'x-uber-origin' => 'arch-frontend',
    'Accept-Language' => 'en-US,en;q=0.8',
    'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.110 Safari/537.36',
    'content-type' => 'application/json',
    'Accept' => 'application/json',
    'Referer' => 'https://auth.uber.com/login/session',
    'Connection' => 'keep-alive',
    'Cookie' => '_udid=w_a93f38c118b945eb9ff9fe412319d574; utag_main=v_id:015cac0bee14007210af2e5938b405069006b06100718$_sn:2$_ss:1$_st:1499076374951$segment:b$optimizely_segment:a$dc_visit:1$ses_id:1499074574951%3Bexp-session$_pn:1%3Bexp-session; mp_e39a4ba8174726fb79f6a6c77b7a5247_mixpanel=%7B%22distinct_id%22%3A%20%2215cac0bef31918-09562444c140cf-317f0158-c0000-15cac0bef32d7c%22%2C%22%24initial_referrer%22%3A%20%22https%3A%2F%2Fauth.uber.com%2Flogin%2Fsession%22%2C%22%24initial_referring_domain%22%3A%20%22auth.uber.com%22%7D; AMCVS_0FEC8C3E55DB4B027F000101%40AdobeOrg=1; AMCV_0FEC8C3E55DB4B027F000101%40AdobeOrg=1611084164%7CMCMID%7C78808930763352101411630973563674613519%7CMCAAMLH-1499679376%7C6%7CMCAAMB-1499679376%7CNRX38WO0n5BH8Th-nqAG_A%7CMCOPTOUT-1499081776s%7CNONE; mp_mixpanel__c=5; _ga=GA1.2.1481685025.1497535015; _gid=GA1.2.707105736.1499074529; arch-frontend:sess=MzjicsPmpcRfiHeu10bfbQ.JsLJ3nVcjELfN3yrjnvytf7D97uOCuWEHp4kffiz9KlzSE1BzLGeF3QL3VJegQ_AXmC0d04aGCgPnrCZtriUDKGCjjEbPnwYuN57arOLHSYIxWruMciuS-ENvoXhYMdz5F6KBLZGabs1V80S2cgzSP87XFvoFa64ykrGUkPvkaUG1L33WxYeussv2UtFojKrrs7lr_6f-C72Clclrhpr8GatXcr-qdAsgeWx4G8e3O_gzZI7XSyAOaZq2cSyXgM-9m28rjMjB0RdCK7xzAQfbO0jGS3qSOUOrC5Eq3K9qpLbnQP4rdV4vkeF6UdWd49gRsdnQbOFlBs0Db-yPrFrPt83UhELHpfrYHAd-4gJSeQovhzm5_U522zXel1egr8n-SPv6fLQqxXA1ta3dy6ZtC2yGUp2j2lsILKNsPjMVd4bgDU_nwDuN1pkwDHiPQNbzIwlnuA_BuOni2Tz7VnphCDqNa7NJ2Mt4iI0KxP_0-HEghrWRW5kpwkp2BE0W4mrusAOlzQbKndzDhUXzCMFugQ_KMOTAJSfsJjfn3LztdkRuUuDrhBQc28M0f1sQ8Hbi4adGF4Vkx6lPs1FFqtapw.1499074676721.1209600000.yV4gHQdpkfMNhyeZpxvR0CxSTEYM4et95Fo5U_5oQpo; path=/; expires=Mon, 17 Jul 2017 09:37:57 GMT; secure; httponly'
	);
	$data = '{"answer":{"type":"VERIFY_PASSWORD","password":"newperson"},"rememberMe":true}';
	$response = Requests::post('https://auth.uber.com/login/handleanswer', $headers, $data);

	return $response;
}

function checkLogin(){
	// Requests::register_autoloader();
	// $headers = array(
	//     'Origin' => 'https://auth.uber.com',
	//     'Accept-Encoding' => 'gzip, deflate, br',
	//     'x-csrf-token' => '1499074714-01-eXMvbgRL4q7my4UoP8zLvdIs-E_Z80hDpSPf1nAovGs',
	//     'x-uber-origin' => 'arch-frontend',
	//     'Accept-Language' => 'en-US,en;q=0.8',
	//     'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.110 Safari/537.36',
	//     'content-type' => 'application/json',
	//     'Accept' => 'application/json',
	//     'Referer' => 'https://auth.uber.com/login/?next_url=https%3A%2F%2Fpartners.uber.com%2Fp3%2F&state=awKSGgA5Xb_DSpLUH-c1LdijJeTXjzjGcv1vdcSDqpI%3D',
	//     'Connection' => 'keep-alive',
	//     'Cookie' => '_udid=w_a93f38c118b945eb9ff9fe412319d574; utag_main=v_id:015cac0bee14007210af2e5938b405069006b06100718$_sn:2$_ss:1$_st:1499076374951$segment:b$optimizely_segment:a$dc_visit:1$ses_id:1499074574951%3Bexp-session$_pn:1%3Bexp-session; mp_e39a4ba8174726fb79f6a6c77b7a5247_mixpanel=%7B%22distinct_id%22%3A%20%2215cac0bef31918-09562444c140cf-317f0158-c0000-15cac0bef32d7c%22%2C%22%24initial_referrer%22%3A%20%22https%3A%2F%2Fauth.uber.com%2Flogin%2Fsession%22%2C%22%24initial_referring_domain%22%3A%20%22auth.uber.com%22%7D; AMCVS_0FEC8C3E55DB4B027F000101%40AdobeOrg=1; AMCV_0FEC8C3E55DB4B027F000101%40AdobeOrg=1611084164%7CMCMID%7C78808930763352101411630973563674613519%7CMCAAMLH-1499679376%7C6%7CMCAAMB-1499679376%7CNRX38WO0n5BH8Th-nqAG_A%7CMCOPTOUT-1499081776s%7CNONE; mp_mixpanel__c=5; arch-frontend:sess=NYLRuFF-ZlAHEgUhRp-gEg.uCtCoASeHH6LKW5B7lIWjTygnEcN50H5c8UAEHhomU59GK_Almg0JCrWlReWwiOSjuk68F--cJyKTOQ2kRD3u9qd4fwl0CZGJajVFQZAR_IDc2VmRGtCWoussAvMOydSvtZ7CEHfNIhnHmjOyoc6iREJgBN4hwa0VTC7eJhtR7OvFxKpNRbyVnKhConP_NX7p94pxWFkzvFkZdJT_DBk7dgSVQu3a84zYFnJd5Ly9Iqd_ZfKunSPaCcoAM6RPjsCYp2N5ENNtYkMUmy49ksWdUmS6ryITMuBRPY8puwsd7Gcv_BRW_IwK7LcsDUMtX8rWF8CZ1NoWRtmXQhIbzAh4RfmDigneJCtCnEWhtyg-Y1Rt2i84OFgzHu20fVqkK_e_BKvR2SIYh98W8FaBkikYbBXowjmHU163Y0jWh0dMLj83G8GS-YTUnuHfhnAW8Aeucha5EaWrirCpW7hnUqImyxOk3UbKL_2gClVa5RMvDK5i6BOSOpB08yxlZH0qb1i.1499074676721.1209600000.RJEPy_ptiY8q9ipPZMhlG91yTVk8nn6mpuF5zMPfjPg; _gat_UA_7157694_35=1; _ga=GA1.2.1481685025.1497535015; _gid=GA1.2.707105736.1499074529'
	// );
	$data = '{"answer":{"type":"VERIFY_INPUT_EMAIL","userIdentifier":{"email":"promolist@list.ru"}},"init":true}';

	// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
	$ch = curl_init();

	$cookie_file = './cook.me';

	curl_setopt($ch, CURLOPT_URL, "https://auth.uber.com/login/handleanswer");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	curl_setopt($ch, CURLOPT_POST, 0);
	curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
	// curl_setopt ($ch, CURLOPT_COOKIEJAR, realpath($cookie_file) );	
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.110 Safari/537.36');
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_REFERER, 'https://auth.uber.com/login/?next_url=https%3A%2F%2Fpartners.uber.com%2Fp3%2F&state=awKSGgA5Xb_DSpLUH-c1LdijJeTXjzjGcv1vdcSDqpI%3D');
	curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__).'/mycookie.txt');
	curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__).'/mycookie.txt');
	curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate, br');
	// curl_setopt($ch, CURLOPT_AUTOREFERER, true);
	//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); //CURLOPT_FOLLOWLOCATION Disabled...
	curl_setopt($ch, CURLOPT_TIMEOUT, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$headers = array();
	$headers[] = "Origin: https://auth.uber.com";
	// $headers[] = "Accept-Encoding: gzip, deflate, br";
	$headers[] = "X-Csrf-Token: 1499074714-01-eXMvbgRL4q7my4UoP8zLvdIs-E_Z80hDpSPf1nAovGs";
	$headers[] = "X-Uber-Origin: arch-frontend";
	$headers[] = "Accept-Language: en-US,en;q=0.8";
	// $headers[] = "Content-Type: application/x-www-form-urlencoded";
	$headers[] = "Content-Type: application/json";
	$headers[] = "Accept: application/json";
	$headers[] = 'Cookie: _udid=w_a93f38c118b945eb9ff9fe412319d574; utag_main=v_id:015cac0bee14007210af2e5938b405069006b06100718$_sn:2$_ss:1$_st:1499076374951$segment:b$optimizely_segment:a$dc_visit:1$ses_id:1499074574951%3Bexp-session$_pn:1%3Bexp-session; mp_e39a4ba8174726fb79f6a6c77b7a5247_mixpanel=%7B%22distinct_id%22%3A%20%2215cac0bef31918-09562444c140cf-317f0158-c0000-15cac0bef32d7c%22%2C%22%24initial_referrer%22%3A%20%22https%3A%2F%2Fauth.uber.com%2Flogin%2Fsession%22%2C%22%24initial_referring_domain%22%3A%20%22auth.uber.com%22%7D; AMCVS_0FEC8C3E55DB4B027F000101%40AdobeOrg=1; AMCV_0FEC8C3E55DB4B027F000101%40AdobeOrg=1611084164%7CMCMID%7C78808930763352101411630973563674613519%7CMCAAMLH-1499679376%7C6%7CMCAAMB-1499679376%7CNRX38WO0n5BH8Th-nqAG_A%7CMCOPTOUT-1499081776s%7CNONE; mp_mixpanel__c=5; arch-frontend:sess=NYLRuFF-ZlAHEgUhRp-gEg.uCtCoASeHH6LKW5B7lIWjTygnEcN50H5c8UAEHhomU59GK_Almg0JCrWlReWwiOSjuk68F--cJyKTOQ2kRD3u9qd4fwl0CZGJajVFQZAR_IDc2VmRGtCWoussAvMOydSvtZ7CEHfNIhnHmjOyoc6iREJgBN4hwa0VTC7eJhtR7OvFxKpNRbyVnKhConP_NX7p94pxWFkzvFkZdJT_DBk7dgSVQu3a84zYFnJd5Ly9Iqd_ZfKunSPaCcoAM6RPjsCYp2N5ENNtYkMUmy49ksWdUmS6ryITMuBRPY8puwsd7Gcv_BRW_IwK7LcsDUMtX8rWF8CZ1NoWRtmXQhIbzAh4RfmDigneJCtCnEWhtyg-Y1Rt2i84OFgzHu20fVqkK_e_BKvR2SIYh98W8FaBkikYbBXowjmHU163Y0jWh0dMLj83G8GS-YTUnuHfhnAW8Aeucha5EaWrirCpW7hnUqImyxOk3UbKL_2gClVa5RMvDK5i6BOSOpB08yxlZH0qb1i.1499074676721.1209600000.RJEPy_ptiY8q9ipPZMhlG91yTVk8nn6mpuF5zMPfjPg; _gat_UA_7157694_35=1; _ga=GA1.2.1481685025.1497535015; _gid=GA1.2.707105736.1499074529';
	$headers[] = "Connection: keep-alive";
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$result = curl_exec($ch);
	if (curl_errno($ch)) {
	    echo 'Error:' . curl_error($ch);
	} else {
		echo "OK <br>";
		print_r ($result);
	}


	echo curl_getinfo($ch, CURLINFO_RESPONSE_CODE) . "<br>";

	curl_close ($ch);
	return $result;
}

$checkLoginRespond = checkLogin2();
print_r( (array)$checkLoginRespond );
// $authRespond = auth();

// $authRespond = (array)$authRespond;
// print_r($authRespond['headers']);

// const URL_BASE_STATEMENTS="https://partners.uber.com/p3/money/statements/index";

// GetURL(URL_BASE_STATEMENTS,$aRes);

// $URL_STATEMENTS="https://partners.uber.com/p3/money/statements/view/current";

// GetURL($URL_STATEMENTS,$aRes);

// print_r( $aRes );

// $aRes[$URL_STATEMENTS]["HTML"]=preg_replace("|([^\{]+)(.*)|is", "\${2}", $aRes[$
// 	URL_STATEMENTS]["HTML"]);

// // print_r($aRes[$URL_STATEMENTS]["HTML"]);

// $aRes = json_decode($aRes[$URL_STATEMENTS]["HTML"], true);




// $session_cookie = "sid=QA.CAESEIIoNQfgMEfQvsF0kNe_PR8YwLnEzgUiATEqJDc5YmEyYTdmLTliZjUtNDU3Ni1hMDA0LThiNzZmMDI3NzA5ZDJAaVByzvRzx00EdaLGxo1rBh0pmhnP3pzsUvftfTsrYnymMNHOXRhzEdy-ykbuxfe_mYfj7ieFr0SAoy72eh__pDoBMQ.miIE_7Sxn3rRbuua5VG8bmBw_H4jL_mrF_l93K2h7Wk; Domain=.uber.com; Path=/; Expires=Sun, 01 Oct 2017 16:50:08 GMT; HttpOnly; Secure; csid=1.epDzZK53wNec/FFXxaTFZrRQgYnA1/PIYjKGxMRqSJ0=; Domain=auth.uber.com; Path=/; Expires=Sun, 01 Oct 2017 16:50:08 GMT; HttpOnly; Secure; _csid=1.1499100908224.2160h.caxlaXIdTaM4PpOJeScmhniD/d8HGN3AixTd9/9q+3Y=; Domain=.uber.com; Path=/; Expires=Mon, 03 Jul 2017 16:55:08 GMT; HttpOnly; Secure; lsid=; Domain=.uber.com; Path=/; Expires=Thu, 01 Jan 1970 00:00:00 GMT; HttpOnly; Secure; arch-frontend:sess=IU8m5N-igvA5RS4J1CGp9g.QIMkyQVbQN6HnyHxQZ6YSUA9QjVyT8-WJZN8Sx76PAktlqeG_Ak042f5xorKc2S_mI9ibILBHKDvQzyOttSwMNJ59vPIR9j0NQ0V7qZ-vjF_wlHy4QGYTaCaELdJV8Z9hH7YVAbi7bz4SAFrHoylUTBKdpnTXBt5CqaaYccazARBbBjkQxIKZMda32n5BmtK8_9eke9S6MgaUnWY8ve-A8vw9w_gNhzdIc_ksvBkj6Ed8OYA75GiaqYkpPc0DGxP-BE0wojfyWt2F54sHnpocpUaRY5jJ1HXgnUcoLPt-oe8XlQni42yBJaVJZCOhfmc8unFoUFPpRCW2VM4yY8Ri8y6iV-RvwRcdf4E8O9CHynXsYJhV0XKd5ZwlXjZgje9clsc7EysaCup1CRPeO8ymSbSDt_3j_ChTuPEAGjLkB-mUb9zmn6OH6ieTsoo2ue2SyqlOtkRvQFbC_Zj4axeyGUppkzfCIZAX9-yjDAr-KzuzERApIoRq8a8VoU6X4mlWwFEwcOW8d0MSgH6JxlklcnCavacCGT80XMNS9M9sXUDXYrG9xzTfgi_FeoO93e5dZjE_GzohCo-w_orY3qsa9KCa1U9N5AzhDly66Uyeamz2N8WwocySeQD48F3m5nnkAVoJqGv4fsQHeEqjBk1ewZmNoZ-V5VKjvXNRETgD7nrU_W7tPBN8ZrRuwvT6GpKBtbvnxVEXOzhVYZrDMhoMNTwp5F1w8V1ZS2TX2lBWafGMv91gM2Ar_4f0oAcSvhGjY2cxyY5GSSMpeVeqF2KGeMgThCvkZJsQSLCze_8Cu4eWwdY1SXCtP8j_wjEdVIXgfPC9pTVJYEpXvspIg-NOGpeysdMF4PYV8UFLOA9JYg.1499074676721.1209600000.OfDChWnygjfp-R4gJX9yiyTs39rqRsJcLI4k1995d4I; path=/; expires=Mon, 17 Jul 2017 09:37:57 GMT; secure; httponly"

// echo curl_getinfo($ch, CURLINFO_RESPONSE_CODE) . "<br>";

// print_r( $response );

#

// curl_close($ch);

?>