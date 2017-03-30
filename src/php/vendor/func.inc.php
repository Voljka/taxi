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
	$sHeader[]="Cookie:_udid=w_cc0aa62c8cc348b3b8aa21a247300a2a; __qca=P0-1337783238-1489688389612; AMCVS_0FEC8C3E55DB4B027F000101%40AdobeOrg=1; AMCV_0FEC8C3E55DB4B027F000101%40AdobeOrg=1611084164%7CMCMID%7C08244970934108574413209010439311368783%7CMCAAMLH-1490293189%7C6%7CMCAAMB-1490293189%7CcIBAx_aQzFEHcPoEv0GwcQ%7CMCOPTOUT-1489695589s%7CNONE; logged_in=true; partners-platform-cookie=He1fN_0391L2CLShX89xFw.TMGnj0yBRCzrms_uXDCzy_ODXNRJ1qjy-h2b6SZxX-Z8xLJ_n9JO7Mr19mUYxC5nBohTAYu-KjJ3GM1MPFml6J3e-NOZM2PUvvib52YWrX9xZt2fguoa8G2iFFXXPQnlGnjEu3M0fZj6jKYeZmKENo4y46KSg7E1XF_XiWAi4fTWavsaJpLNYO4NlcdcefzJQ65eu4L5reBZNcZCnad-idj9hspYqbJv1C1soocTZ43Z8qNpvgocGvn-kmBckvgdw19QWne5XBcox4x6F9D5eHy6NOzzTrG-D0m1nw7ec1ibn-UQDhRrI4UunSmA2p1z6DDWaPqSoPqGPNpzhy9NVYnfpcJHeMLQlwql_JHR5dTZBANka7nzEUsi-a8VZDZmfTTMuwIXPApT9mZ-NwBM-24agJAimkhdmaJtanw4UDolftK7ztBCPEe-C4JXdXcAWmCKuP4TNDWGbMVEKHDpgbX8N_V2NxTqdT6R7xb0BzAiCpNos-CZwp9kl539-IaydCMeeqglZLMvtCIc_VSWQTwiv9lrnR4MexSD131XKtg38CtUD4Zsi6TqDn5YQ6j8dyE8CI9fKtiiMYcMPU_1ki_XUHbuqCPHjYuoFai4Lr6QetH2FOTtKI0q9y1bqMRxMgIbqlh7GwTd7KfyKWi8oYiwIOOsUwIoIZovRhovBWwpD9z169gxImvd7eFjuJrOKEC1TbI776ENTLw8RSLXfIXo3h2W325UuYHuA6_HxkFwpZbt-6w5EYVeOB4-UHx1blBri83J9LEWAoFbgdP1jeD1lIZbYe8rLoB3QbtHBgfwQ6lnOaB3a4p9vyWwtflOloWxrMxkDdcDU4_RgPkoHGc3fK-DyFfgiJt4o_Lh6riTuZBcqaKXlxLIc1Rml33Yk9jNz_amzhFbKyOofhzoqCJcFaPWj8LeWz3_w3pBPsM.1489688383741.1209600000.O-CehdcsnAcDO71U8Lp4EF8Z3DuIbiAuIdL9DEXNpOw; _gat_tealium_0=1; _ceg.s=omx6z3; _ceg.u=omx6z3; aam_uuid=08470434043248839663186524609758056016; utag_main=v_id:015ad858e056004dc2a7fe41540805071001c06900bd0$_sn:1$_ss:0$_pn:4%3Bexp-session$_st:1489690272233$ses_id:1489688387670%3Bexp-session$segment:b$optimizely_segment:b$dc_visit:1$dc_event:12%3Bexp-session$dc_region:eu-central-1%3Bexp-session$userid:79ba2a7f-9bf5-4576-a004-8b76f027709d; user=%7B%22uuid%22%3A%2279ba2a7f-9bf5-4576-a004-8b76f027709d%22%2C%22token%22%3A%225638bff3-1d39-4848-82b4-02864bab772d%22%7D; _ga=GA1.2.75499847.1489688389; _gat_UA_7157694_7=1; mp_mixpanel__c=0; mp_e39a4ba8174726fb79f6a6c77b7a5247_mixpanel=%7B%22distinct_id%22%3A%20%2215ad858e394624-034854ec281bfe-5b123112-1fa400-15ad858e395424%22%2C%22__mps%22%3A%20%7B%7D%2C%22__mpso%22%3A%20%7B%7D%2C%22__mpa%22%3A%20%7B%7D%2C%22__mpu%22%3A%20%7B%7D%2C%22__mpap%22%3A%20%5B%5D%2C%22Lead%20Page%22%3A%20%22https%3A%2F%2Flogin.uber.com%2Flogin%22%2C%22%24initial_referrer%22%3A%20%22%24direct%22%2C%22%24initial_referring_domain%22%3A%20%22%24direct%22%7D";

	$sHeader[] = "Accept-Language: ru-Ru,ru;q=0.5";
	$sHeader[] = "Pragma: ";

	curl_setopt($oCurl, CURLOPT_URL, $sURL);
	curl_setopt($oCurl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1; rv:5.0) Gecko/20100101 Firefox/5.0 Firefox/5.0');
	curl_setopt($oCurl, CURLOPT_HTTPHEADER, $sHeader);
	curl_setopt($oCurl, CURLOPT_HEADER, true);
	curl_setopt($oCurl, CURLOPT_REFERER, 'https://partners.uber.com/p3/fleet/live');
	curl_setopt($oCurl, CURLOPT_ENCODING, 'gzip,deflate');
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