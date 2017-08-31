<?php
$agent = filter_input(INPUT_SERVER, 'HTTP_USER_AGENT');

$url = $_SERVER['REQUEST_URI'];

function getUserIP() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

$forbidden_ips = array(
"80.175.42.151",
"164.52.7.132",
"149.202.175.8",
"84.22.137.57",
"46.161.9.49",
"85.240.215.43",
"125.161.176.87",
"78.37.15.84",
"180.251.165.48",
"196.52.43.53",
"87.118.116.12",
"192.151.152.122",
"148.251.50.205",
"51.255.214.89",
"137.226.113.11",
"142.54.188.34",
"61.216.14.176",
"221.204.19.123",
"35.199.151.99",
"155.94.89.82",
"159.203.240.225",
"91.196.50.33",
"220.181.159.73",
"179.61.185.92",
"124.124.197.72",
"184.154.36.171",
"149.202.175.5",
"116.211.145.144",
"212.237.59.163",
"177.32.111.70",
);

$invalid_urls = array(
	"wp-login",
	"wordpress",
	"wp-admin",
	"/administrator",
	"SELECT",
	"wp-content",
	"admin.php",
	"wp-includes",
	"muieblackcat",
	"phpmyadmin",
);


$invalid_agents = array(
	"nikto",
	"sqlmap",
	"ZmEu",
	"sysscan",
	"zgrab",
	"zmap",
	"masscan",
	"urllib",
	"AhrefsBot",
	"Wget",
//	"curl"
);





checkAgents($invalid_agents, $agent);
checkUrl($invalid_urls, $url);

$ip_address = getUserIp();
if (in_array($ip_address, $forbidden_ips))
{
	echo "What are you doing?";
	sendBomb();
	exit();
}


//check for niikto, sql map or "bad" subfolders which only exist on wordpress
if (startswith($url,'wp-') || startswith($url,'wordpress') || startswith($url,'wp/'))
{
      sendBomb();
      exit();
}





function checkAgents($invalid_agents, $agent){
	$illegal_count = 0;
	foreach($invalid_agents as $keyword){
		if(strpos($agent, $keyword) !== false){
                         $illegal_count += 1;
                 }
	}

	if($illegal_count > 0){
                echo 'Illegal Request, Counter attacking.';
                sendBomb();
                exit();
        }	
}




function checkUrl($invalid_urls, $url){
	$illegal_count = 0;
	foreach($invalid_urls as $keyword){
        	if(strpos($url, $keyword) !== false){
               		 $illegal_count += 1;            
       		 }
	}
		
	if($illegal_count > 0){
        	echo 'Illegal Request, Counter attacking.';
        	sendBomb();
        	exit();

	}
}




function sendBomb(){
        //prepare the client to recieve GZIP data. This will not be suspicious
        //since most web servers use GZIP by default
        header("Content-Encoding: gzip");
        header("Content-Length: ".filesize('/home/edantonio505/10G.gzip'));
        //Turn off output buffering
        if (ob_get_level()) ob_end_clean();
        //send the gzipped file to the client
        readfile('/home/edantonio505/10G.gzip');
}

function startsWith($a, $b) { 
    return strpos($a, $b) === 0;
}




