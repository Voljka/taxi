<?
// set_include_path(get_include_path() . PATH_SEPARATOR . "/var/www/_includes");

// require_once('conf/configure.conf.inc.php');
// require_once('MySql.class.php');
// require_once('E.class.php');
// require_once('C.class.php');

require ('../config/db.config.php');

// $oDB=new CMySql($aDbSettings['MAIN']);
// $oDB->Query("SET NAMES utf8 COLLATE utf8_general_ci");



function GetURL($sURL, &$paData=null, $aPost=0){

	$oCurl = curl_init();
	$sHeader[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
	$sHeader[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
	$sHeader[] = "Cache-Control: max-age=0";
	$sHeader[] = "Connection: keep-alive";
	$sHeader[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";

	// $sHeader[]="Cookie:_ua=%7B%22id%22%3A%22741bdc5c-6377-4ab1-ceb5-fa6d009033c0%22%2C%22ts%22%3A1496103189505%7D; _udid=w_76f312af9def4586b5862c18b726a407; utag_main=v_id:015afc224fd30017de39e36f695500048001c00d00918$_sn:8$_ss:1$_st:1496104990686$segment:b$optimizely_segment:b$dc_visit:7$ses_id:1496103190686%3Bexp-session$_pn:1%3Bexp-session$dc_event:1%3Bexp-session$dc_region:eu-central-1%3Bexp-session; _ga=GA1.2.9005846.1490288792; AMCV_0FEC8C3E55DB4B027F000101%40AdobeOrg=1611084164%7CMCMID%7C19554999415315289792366110531799765706%7CMCAAMLH-1496707991%7C6%7CMCAAMB-1496707991%7CNRX38WO0n5BH8Th-nqAG_A%7CMCOPTOUT-1496110391s%7CNONE; __qca=P0-74945251-1490288792497; logged_in=true; marketing_vistor_id=43689c1c-a5d1-4731-a1d4-77cc4c54908c; sid=QA.CAESEIy3OnDAHE0ahduw3IUqZmQY5LLIzAUiATEqJDc5YmEyYTdmLTliZjUtNDU3Ni1hMDA0LThiNzZmMDI3NzA5ZDJAdCMQp5HKF_wFgkuyjsLercdyJg5hWHHYwY-Abmwg1N5T5JXNwM_5RvaY4056xArD7CvECJOWZGtgnTP0fuDvBjoBMQ.T6RRzri7ym7Gi9CpARxtObliHqViIUOHFI4EOoAYqvI; csid=1.1502746981213.umeBGOUT9AbmI1TUhsISl6aL6NpsCpJaDaR3zaMkBo4=; fsid=8bee7gen-jmri-jloo-ulmr-x1yy8ux34y8d; partners-platform-cookie=FmVmbMi_8m9yPhcnUSduyw.zQ12VIe-hPzANipfig-LwFvWQZ574lbajuf405d_HOPPzJDAHprl8E8IpmKjB5a5XZsu84WzoXGyxgU3in-Ru2W-b7jb3HL3OczvbHmklir3sjIZev5MfEIlBGBIrlYnRQzT08YEbMawpRB2yk4y6AQyyIoiKITbzlk3Pw6GMJujiswkQs0vsW-kySaURIPyYrPYakbOERSr53WWnChBQpG8cFwDz2GJLLoGAJj5W7siTSsCcWY062KP5zHz8QsknFfPnycQheGqGk9qNA7g6y-OqM6SUyiJtKKROIbIUxgB8ZtG8q0ICa0E8IgYD4SqJnbLHJ5j4eZPs_WkbXBDPwd5bHQClh7h9KkEalx1auQ7gjkIB7pK085_CanYRoq9JrWZ_p-9aYPboPBAYLrhWtJqlA9G6bDQDzzePcs4gXVqhVcD-5AuX9AwdMeIrYvy_gRXCGRbofO1MwI9u9afO8WS1tjPEXoxjX6wjIg7CgA.1495575781231.1209600000.rp4F1V62IcrCgV-eQ8biQLB0rrBdGKlgSw0FJMTr5OM; aam_uuid=19869781430055247482334661483456872149; _gid=GA1.2.990151381.1496103191; mp_e39a4ba8174726fb79f6a6c77b7a5247_mixpanel=%7B%22distinct_id%22%3A%20%2215afc2251f94f8-0ef9eec73b43268-47534130-140000-15afc2251fa3b5%22%2C%22__mps%22%3A%20%7B%7D%2C%22__mpso%22%3A%20%7B%7D%2C%22__mpa%22%3A%20%7B%7D%2C%22__mpu%22%3A%20%7B%7D%2C%22__mpap%22%3A%20%5B%5D%2C%22Lead%20Page%22%3A%20%22https%3A%2F%2Flogin.uber.com%2Flogin%22%2C%22%24initial_referrer%22%3A%20%22%24direct%22%2C%22%24initial_referring_domain%22%3A%20%22%24direct%22%7D; mp_mixpanel__c=1; AMCVS_0FEC8C3E55DB4B027F000101%40AdobeOrg=1";

	$sHeader[]='Cookie:_udid=w_76f312af9def4586b5862c18b726a407; utag_main=v_id:015afc224fd30017de39e36f695500048001c00d00918$_sn:23$_ss:0$_st:1499969973035$segment:b$optimizely_segment:b$dc_visit:13$ses_id:1499966747677%3Bexp-session$_pn:3%3Bexp-session; _ga=GA1.2.9005846.1490288792; AMCV_0FEC8C3E55DB4B027F000101%40AdobeOrg=1611084164%7CMCMID%7C19554999415315289792366110531799765706%7CMCAAMLH-1500559807%7C6%7CMCAAMB-1500571548%7CNRX38WO0n5BH8Th-nqAG_A%7CMCOPTOUT-1499973948s%7CNONE; __qca=P0-74945251-1490288792497; logged_in=true; marketing_vistor_id=43689c1c-a5d1-4731-a1d4-77cc4c54908c; aam_uuid=19869781430055247482334661483456872149; optimizelyEndUserId=oeu1497534366752r0.4994466737485539; _ceg.s=orlcxp; _ceg.u=orlcxp; aam_uuid=19869781430055247482334661483456872149; _ceg.s=ot1iul; _ceg.u=ot1iul; partners-platform-cookie=ArOLZq3OEzKiEHpH_fHRag.ya6uoj24u99iLk1OWA5cOU8dvFC-kRZOd1orc2YZa4mI4TrbU6p2hVKEcI3B8gsoq6NnddxQ9uqHsPLFFMJwRrlfrqwj34Gp2ZMKcPJQklUpNOlWwn6OUkRad4JlMRld5pTITGCDzCdVCcwwpQq7Ble0RFI9E_DtW-qqaqtw0BJvsO7jTo0aKAr7VulicZm8R0T6Vuy3_ylMC1T4sSMXrySMcBJjhG3EwCFfkT_AU7RZmA1FWPDooa3NWhpXAHHpRWKS5WvVl4TJftNLpIwup2JM5qwzWn1L6c_54Um4Z7lTd-OerhtvMm7WtWWZ8dta_QGgb8qxezD4pyZ6u67gmWFt7V2T2sya5l3uubSwCmcJleGSnNiZlis8CInjpqOR0Nv0rMCFUjjnYWRnbHggeTTPv2xt4f-r87gXD9QZMIuAVh_RNWVQK7_CoNdl_sWGtaqFcUuJItOcSQx_WgO-tryyDvUHr_PZnHTWcEbNlmVUPKU9ccdlSZNjQB1ASXKy2OW_AhYCFpFncpUJfBOZE-4Uc3NgEprNR02spmonXjagJBWkR85dqhf_H17EQJOt32IazphyCJOV7osKicojQcWOpR9keIaD1Fhkd-cbpyH6ekZHDNoDy1vILiKeS9i9ay2JA51SOUhlpjV_bXWIN8rpYFlAehenktZYNH1wdHqNXisGxrJPUDqpvGwRT99gKaHz6peta5ba2MwQ1V2CZWS9DeQTVWnUSACkq7453t79rrAAJZOIb6nRisjx4piX.1499623737744.1209600000.XH1GMBPNTELbOuU1mU2l9aRu8DekhPWOil-j2RR9Gnw; sid=QA.CAESEH6WGvDlakLEvsZLBrwExMQYucz4zgUiATEqJDc5YmEyYTdmLTliZjUtNDU3Ni1hMDA0LThiNzZmMDI3NzA5ZDJAH0hKSc9xQquP7mmpP-RPrhz8SI_3vatDymIZ78ttqkMNs2ZoUO9dEBfILOTF_QnHVg6Nhp5HuKmBxBXvT8-VJjoBMQ.TYrP2P5uMtSmI49Ao_pyYrWFRzMUggdRihUCbAAIEcI; _gid=GA1.2.1966544253.1499954643; csid=1.1507731001959.xSO/21dK9i0EwLAyfZmKgNg13gbGZAOpRwaKiiMKTn8=; fsid=8bee7gen-jmri-jloo-ulmr-x1yy8ux34y8d; mp_mixpanel__c=3; mp_e39a4ba8174726fb79f6a6c77b7a5247_mixpanel=%7B%22distinct_id%22%3A%20%2215afc2251f94f8-0ef9eec73b43268-47534130-140000-15afc2251fa3b5%22%2C%22__mps%22%3A%20%7B%7D%2C%22__mpso%22%3A%20%7B%7D%2C%22__mpa%22%3A%20%7B%7D%2C%22__mpu%22%3A%20%7B%7D%2C%22__mpap%22%3A%20%5B%5D%2C%22Lead%20Page%22%3A%20%22https%3A%2F%2Flogin.uber.com%2Flogin%22%2C%22%24initial_referrer%22%3A%20%22%24direct%22%2C%22%24initial_referring_domain%22%3A%20%22%24direct%22%7D';
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

	if($aStatus['http_code']!=200){
		fnLog("GetURL: failed ".print_r($aStatus,true));
		return false;
	}

	return $sHtml;
}

function fnLog($str){
	// global $oDB;
	
	// CC::CheckString($str,CC::CC_STRING_QUOTE);
	// $oDB->Query("INSERT INTO logs (adate,ltext) VALUES (NOW(),'{$str}') ");
	// echo (date("[D M Y H:i:s] : ").$str.PHP_EOL);
	file_put_contents('../logs/gerUrl_errors.log', date("Y:m-d H:i:s") . PHP_EOL . $str);
}

?>