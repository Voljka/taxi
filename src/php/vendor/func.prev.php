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
	// $sHeader[]="Cookie:_udid=w_cc0aa62c8cc348b3b8aa21a247300a2a; __qca=P0-1337783238-1489688389612; AMCVS_0FEC8C3E55DB4B027F000101%40AdobeOrg=1; AMCV_0FEC8C3E55DB4B027F000101%40AdobeOrg=1611084164%7CMCMID%7C08244970934108574413209010439311368783%7CMCAAMLH-1490293189%7C6%7CMCAAMB-1490293189%7CcIBAx_aQzFEHcPoEv0GwcQ%7CMCOPTOUT-1489695589s%7CNONE; logged_in=true; partners-platform-cookie=He1fN_0391L2CLShX89xFw.TMGnj0yBRCzrms_uXDCzy_ODXNRJ1qjy-h2b6SZxX-Z8xLJ_n9JO7Mr19mUYxC5nBohTAYu-KjJ3GM1MPFml6J3e-NOZM2PUvvib52YWrX9xZt2fguoa8G2iFFXXPQnlGnjEu3M0fZj6jKYeZmKENo4y46KSg7E1XF_XiWAi4fTWavsaJpLNYO4NlcdcefzJQ65eu4L5reBZNcZCnad-idj9hspYqbJv1C1soocTZ43Z8qNpvgocGvn-kmBckvgdw19QWne5XBcox4x6F9D5eHy6NOzzTrG-D0m1nw7ec1ibn-UQDhRrI4UunSmA2p1z6DDWaPqSoPqGPNpzhy9NVYnfpcJHeMLQlwql_JHR5dTZBANka7nzEUsi-a8VZDZmfTTMuwIXPApT9mZ-NwBM-24agJAimkhdmaJtanw4UDolftK7ztBCPEe-C4JXdXcAWmCKuP4TNDWGbMVEKHDpgbX8N_V2NxTqdT6R7xb0BzAiCpNos-CZwp9kl539-IaydCMeeqglZLMvtCIc_VSWQTwiv9lrnR4MexSD131XKtg38CtUD4Zsi6TqDn5YQ6j8dyE8CI9fKtiiMYcMPU_1ki_XUHbuqCPHjYuoFai4Lr6QetH2FOTtKI0q9y1bqMRxMgIbqlh7GwTd7KfyKWi8oYiwIOOsUwIoIZovRhovBWwpD9z169gxImvd7eFjuJrOKEC1TbI776ENTLw8RSLXfIXo3h2W325UuYHuA6_HxkFwpZbt-6w5EYVeOB4-UHx1blBri83J9LEWAoFbgdP1jeD1lIZbYe8rLoB3QbtHBgfwQ6lnOaB3a4p9vyWwtflOloWxrMxkDdcDU4_RgPkoHGc3fK-DyFfgiJt4o_Lh6riTuZBcqaKXlxLIc1Rml33Yk9jNz_amzhFbKyOofhzoqCJcFaPWj8LeWz3_w3pBPsM.1489688383741.1209600000.O-CehdcsnAcDO71U8Lp4EF8Z3DuIbiAuIdL9DEXNpOw; _gat_tealium_0=1; _ceg.s=omx6z3; _ceg.u=omx6z3; aam_uuid=08470434043248839663186524609758056016; utag_main=v_id:015ad858e056004dc2a7fe41540805071001c06900bd0$_sn:1$_ss:0$_pn:4%3Bexp-session$_st:1489690272233$ses_id:1489688387670%3Bexp-session$segment:b$optimizely_segment:b$dc_visit:1$dc_event:12%3Bexp-session$dc_region:eu-central-1%3Bexp-session$userid:79ba2a7f-9bf5-4576-a004-8b76f027709d; user=%7B%22uuid%22%3A%2279ba2a7f-9bf5-4576-a004-8b76f027709d%22%2C%22token%22%3A%225638bff3-1d39-4848-82b4-02864bab772d%22%7D; _ga=GA1.2.75499847.1489688389; _gat_UA_7157694_7=1; mp_mixpanel__c=0; mp_e39a4ba8174726fb79f6a6c77b7a5247_mixpanel=%7B%22distinct_id%22%3A%20%2215ad858e394624-034854ec281bfe-5b123112-1fa400-15ad858e395424%22%2C%22__mps%22%3A%20%7B%7D%2C%22__mpso%22%3A%20%7B%7D%2C%22__mpa%22%3A%20%7B%7D%2C%22__mpu%22%3A%20%7B%7D%2C%22__mpap%22%3A%20%5B%5D%2C%22Lead%20Page%22%3A%20%22https%3A%2F%2Flogin.uber.com%2Flogin%22%2C%22%24initial_referrer%22%3A%20%22%24direct%22%2C%22%24initial_referring_domain%22%3A%20%22%24direct%22%7D";


	$sHeader[]="Cookie:_ua=%7B%22id%22%3A%220adfe820-0837-441e-8ec4-f3f3feb28fc2%22%2C%22ts%22%3A1493917457883%7D; __qca=P0-707996103-1490288458308; _udid=w_8fadae074e3844d38286f957e7aa97f8; AMCVS_0FEC8C3E55DB4B027F000101%40AdobeOrg=1; AMCV_0FEC8C3E55DB4B027F000101%40AdobeOrg=1611084164%7CMCMID%7C47344144760877631691352651521452402060%7CMCAAMLH-1494521363%7C6%7CMCAAMB-1494521363%7CNRX38WO0n5BH8Th-nqAG_A%7CMCOPTOUT-1493923763s%7CNONE; __lnkrntdmcvrd=uber.com; marketing_vistor_id=d883ee17-f4dc-4189-b85d-9dfb7bbfa099; optimizelyEndUserId=oeu1493024658032r0.6043721730547924; _ceg.s=opfu1x; _ceg.u=opfu1x; aam_uuid=46850037910629450221330228361444198803; sid=QA.CAESEOvN13zv50DNvmTBUS0Z2q0YioyIzAUiATEqJDc5YmEyYTdmLTliZjUtNDU3Ni1hMDA0LThiNzZmMDI3NzA5ZDJA9zLQ6apAeamqYQTtcZ0jlcrSAnH2tGcUmP2_UNWETcuem2by6yANNM06fW_BSHQfnx_37l_ri8P2LLbhpcLWgDoBMQ.rEiqj4sX6o2OL0bUSHH_lyPaU2oaz7WwVZUjEcvAdfU; csid=1.1501693450922.ZoASq4wamKATh3DTF+XT6vI4k5RZ9p244iIFD/Tcx/c=; session=2bdcd0b477a98452_590b5f0a.mJR_-cXYsZowA6O4jMfYIGA0OE8; partners-platform-cookie=tyVcSTFugBm_65y1gNAGVw.jh_CmLECRMEHqmAlmTNd2ifu1iFYpTo1TNou_YyZH8wt9X4Ibq_VTxyGa3qQy8ip6nPqXCd0IUR4fheWOC_y8TgNKO4djaX5O5ki4QOkbpimRzwW3BEQ7dTqf-wShxK5-Ot333MbegsAJMmEPPI-ZIXpeZvpBll20xUGtTvUmlV2mBiFyLCRAQLN-b4Bgcq3wSf6mrZ-JC0F0Rl6n6XC_9Kbu5wNe7eEb610gv6dfe4nsP2Wtpt1KrLxr1UTzZtcf0kh48DTc6pVsdsXrkpbL-vilMmSLyFigXH7f5I5CW_0owaakM_6IgNBFPTHK3ojzajhSUcuglI7r0tykLkehiC0ZmdboOkduU4Kz5z9TsltG2wk7i5cHXOLJ7-vrWlA3_zE4pJCLYmit4pUgQQEsOangCAJN80VRlxc1b8ZY6xm-DbOY6km_QQ20ReCzFMivDex9d_mMgGFU4F9VFKPK1VLGQcgQ96vzGC9HRCgq_4.1493917412315.1209600000.ZslWFNWCY0NiN86dwqEepGZjs7-kFke01SmWEbhatAk; mp_e39a4ba8174726fb79f6a6c77b7a5247_mixpanel=%7B%22distinct_id%22%3A%20%2215afc1d39c826b-027e74f21c5b4b-6a11157a-c0000-15afc1d39c955e%22%2C%22__mps%22%3A%20%7B%7D%2C%22__mpso%22%3A%20%7B%7D%2C%22__mpa%22%3A%20%7B%7D%2C%22__mpu%22%3A%20%7B%7D%2C%22__mpap%22%3A%20%5B%5D%2C%22Lead%20Page%22%3A%20%22https%3A%2F%2Flogin.uber.com%2Flogin%22%2C%22%24initial_referrer%22%3A%20%22%24direct%22%2C%22%24initial_referring_domain%22%3A%20%22%24direct%22%7D; mp_mixpanel__c=0; _ga=GA1.2.25308781.1490288458; _gid=GA1.2.2010175705.1493917458; __lnkrntafu=-1; utag_main=v_id:015afc1d38a0001f6dae6cb42ad20406c001c06400718$_sn:4$_ss:0$_st:1493919258362$segment:b$optimizely_segment:b$dc_visit:4$ses_id:1493916562768%3Bexp-session$_pn:6%3Bexp-session$dc_event:19%3Bexp-session$dc_region:eu-central-1%3Bexp-session; aam_uuid=46850037910629450221330228361444198803; fsid=8bee7gen-jmri-jloo-ulmr-x1yy8ux34y8d";
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