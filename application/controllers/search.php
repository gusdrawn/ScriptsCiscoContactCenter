	<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	/*
		Autor: Gustavo Guillen

		Script principal donde se observa la configuracion para Generar los Scripts.

	*/
	class Search extends CI_Controller {
		function __construct()    {
			parent::__construct();
	    }

		public function index()    {

			$this->load->helper('url');
			$this->load->helper('form');
			$this->load->helper('date');
			$datestring = "%m-%d-%Y";
			$time = time();
			$data = array (
			'hoy' => mdate($datestring, $time)
			);
			$this->load->view('header');
			$this->load->view('panel', $data);
			$this->load->view('footer');
		}
		public function reporte()    {
			global $datos;
			$this->load->helper('url');
			$this->load->helper('form');
			$this->load->helper('download');
			$var_post = array(
				'ip' 			=> $this->input->post('ipvoip'),
				'script' 		=> nl2br($this->input->post('script')),
				'agencia' 		=> $this->input->post('agencia'),
				'nfxs' 			=> array_filter ($this->input->post('lineafxs')),
				'slot0' 		=> $this->input->post('slot0'),
				'slot1' 		=> $this->input->post('slot1'),
				'slot2' 		=> $this->input->post('slot2'),
				'slot3' 		=> $this->input->post('slot3'),
				'nfxo' 			=> array_filter ($this->input->post('lineafxo')),
				'interfacelan'	=> $this->input->post('interfacelan'),
				'version'		=> $this->input->post('version'),
				'iplan'			=> $this->input->post('iplan'),
				'modelo'		=> $this->input->post('modelo'),
				'region'		=> $this->input->post('region'),
				'iploop'		=> $this->input->post('iploop'),
				'pswitch'		=> $this->input->post('pswitch')
			 );
			if (strlen($var_post['agencia']) == 3) {
				$numeración = "0".$var_post['agencia'];

			} else {
				$numeración = $var_post['agencia'];

			}

			$var_edit = array (
				'ip0'  		=> long2ip(ip2long($var_post['ip']) -1),
				'ip1'  		=> long2ip(ip2long($var_post['ip'])),
				'ip40' 		=> long2ip(ip2long($var_post['ip']) + 39),
				'iplan0'  	=> long2ip(ip2long($var_post['iplan']) -1),
				'iplan1' 	=> long2ip(ip2long($var_post['iplan'])),
				'iplan200' 	=> long2ip(ip2long($var_post['iplan']) + 199),
				'nro' 		=>  $numeración

				);
			$region = array (
				'mtr',
				'ort',
				'occ',
				'cll',
				'zufa',
				'coa'
				);
				$quitar = array("h323-gateway", "dial-peer", "<br />", "#\s+#", "FastEthernet");
				$negado   = array("no h323-gateway", "no dial-peer", "\r", "!\r", "GigabitEthernet");
				$quitado = str_replace($quitar, $negado, $var_post['script']);

				$datos = array_merge($var_post, $var_edit);
			// VERIFICAR SI LA LINEA FXO EMPIEZA CON 0 O NO
			$i = 0;
			while ($i <= (count ($datos['nfxo']) - 1)) {
				if (substr($datos['nfxo'][$i], 0, 1) != 0) 	$datos['nfxo'][$i] = "0".$datos['nfxo'][$i];
				$i++;
					}
			//BUSCAR SLOT FXO Y FXS
			$i = 0;
			$datos['fxs'] = array();
			$datos['fxo'] = array();
			while ($i <= 3) {
				switch ($datos['slot'.$i]) {
					case 1:
					//serial
					break;
					case 2:
					//serial
					break;
					case 3:
						array_push($datos['fxo'], '0/'.$i.'/0', '0/'.$i.'/1');
						break;
					case 4:
						array_push($datos['fxo'], '0/'.$i.'/0', '0/'.$i.'/1', '0/'.$i.'/2', '0/'.$i.'/3');
						break;
					case 5:
						array_push($datos['fxs'], '0/'.$i.'/0', '0/'.$i.'/1');
						break;
					case 6:
						array_push($datos['fxs'], '0/'.$i.'/0', '0/'.$i.'/1', '0/'.$i.'/2', '0/'.$i.'/3');
						break;
				}
				$i++;
				}
				function pswitch ($funcion) {
					global $datos;
					switch ($funcion) {
						case 1:
						// 24 puertos
						$i = 24;
						break;
						case 2:
						// 48 puertos
						$i = 48;
						break;
						default:
						//Por seguridad
						$i = 24;
						break;
					}
					$d = 1;
					$script_voice = "";
					while ($d < $i) {
						$script_voice .= "interface GigabitEthernet0/".$d."\r
switchport voice vlan 2\r
macro description cisco-phone\r
spanning-tree portfast\r
!\r
\r";
$d++;
					}
					$script_voice .= "interface GigabitEthernet0/".$i."\r
description ##CONEXION ROUTE2900-".$datos['interfacelan']."##\r
switchport trunk encapsulation dot1q\r
switchport trunk allowed vlan 1,2\r
switchport mode trunk\r
speed 100\r
duplex full\r
!\r
\r";
					return $script_voice;

				}
				function fxs ($funcion) {
					global $datos;
					if (count ($datos['nfxs']) <= count($datos['fxs']) ){
								$s = count ($datos['nfxs']);
					} else {
								echo "No hay suficientes Slots FXS para las lineas";
					}
					$station = array(
						83 	=> "ATM",
						84 	=> "Telecom",
						85 	=> "Boveda",
						86 	=> "Garita",
						87 	=> "FAX",
						88 	=> "FAX",
						90 	=> "Linea Verde",
						91 	=> "Linea Verde",
						92 	=> "Linea Verde",
						93 	=> "Linea Verde",
						94 	=> "Linea Verde",
						95 	=> "POS WEB",
						96 	=> "POS WEB",
						97 	=> "POS WEB",
						98 	=> "POS WEB"
			 		);
			 		$script_voice = "";
					switch ($funcion){
						case 1:
							//voice-port
							$i = 0;
							while ($i < $s) {
								$script_voice .= "!\r
voice-port ".$datos['fxs'][($i)]."\r
timing hookflash-in 1500 400\r
station-id name ".$station[substr($datos['nfxs'][$i], -2)]." Ag. ".$datos['agencia']."\r
station-id number ".substr($datos['nfxs'][$i], -2)."\r
!\r";
								$i++;
							}
						break;
						case 2:
							//dial-peer
							$i = 0;
							while ($i < $s) {
								$script_voice .= "!\r
dial-peer voice 20".($i)." pots\r
description ".$station[substr($datos['nfxs'][$i], -2)]." Ag. ".$datos['agencia']."\r
service mgcpapp\r
destination-pattern ".$datos['nfxs'][$i]."\r
port ".$datos['fxs'][$i]."\r
no register e164\r
!\r";
								$i++;

							}
						break;


					}

				return $script_voice;
				}
				// esto es PARA FXO NO CONFUNDIR
				function fxo ($funcion) {
					global $datos;
					if (count ($datos['nfxo']) <= count($datos['fxo']) ){
								$s = count ($datos['nfxo']);
					} else {
								echo "No hay suficientes Slots FXO para las lineas";
					}
			 		$script_voice = "";
					switch ($funcion){
						case 1:
							//voice-port
							$i = 0;
							while ($i < $s) {
								$script_voice .= "!\r
voice-port ".$datos['fxo'][$i]."\r
trunk-group FXO\r
supervisory disconnect dualtone mid-call\r
supervisory custom-cptone us-custom\r
supervisory dualtone-detect-params 1\r
no battery-reversal\r
echo-cancel coverage 24\r
no vad\r
cptone VE\r
timeouts call-disconnect 5\r
timeouts ringing 60\r
timeouts wait-release 5\r
connection plar opx 505".$datos['nro']."99\r
impedance 900c\r
station-id name LINEA-".($i+1)."\r
station-id number ".$datos['nfxo'][$i]."\r
!\r";
									$i++;
							}
						break;
					}

				return $script_voice;
				}
				// VERSION TIP CUCM 9 ############################################################################################################
				//print_r($datos);
				switch ($datos['version']) {
			case 1:
			$script = "!\r
!\r
ip dhcp excluded-address ".$datos['ip1']." ".$datos['ip40']."\r
!\r
ip dhcp pool VLAN-IPT\r
network ".$datos['ip0']." 255.255.255.0\r
default-router ".$datos['ip1']."\r
option 150 ip 10.132.69.42 10.132.69.45 10.132.69.44\r
lease 0 8\r
!\r
!\r
trunk group FXO\r
max-calls any 12\r
max-retry 4\r
hunt-scheme round-robin\r
!\r
!\r
voice-card 0\r
dspfarm\r
dsp services dspfarm\r
!\r
!\r
voice call send-alert\r
voice rtp send-recv\r
!\r
voice service voip\r
allow-connections h323 to h323\r
supplementary-service h450.12\r
fax protocol t38 version 0 ls-redundancy 0 hs-redundancy 0 fallback none\r
!\r
voice class codec 1\r
codec preference 1 g711alaw\r
codec preference 2 g711ulaw\r
codec preference 3 g729br8\r
codec preference 4 g729r8\r
!\r
voice class h323 1\r
h225 timeout tcp establish 3\r
call start fast\r
!\r
voice class dualtone-detect-params 1\r
freq-max-power 0\r
freq-min-power 35\r
freq-power-twist 15\r
freq-max-delay 40\r
cadence-variation 8\r
!\r
voice class custom-cptone Venezuela\r
dualtone disconnect\r
frequency 1450\r
cadence 500 500\r
!\r
voice class custom-cptone us-custom\r
dualtone busy\r
frequency 425\r
cadence 500 500\r
dualtone ringback\r
frequency 425\r
cadence 1000 4000\r
dualtone reorder\r
frequency 480 620\r
cadence 250 250\r
dualtone out-of-service\r
frequency 950\r
cadence 330 330\r
dualtone number-unobtainable\r
frequency 480 620\r
cadence 250 250\r
dualtone disconnect\r
frequency 425\r
cadence 500 500\r
!\r
application\r
global\r
service alternate default\r
!\r
".$quitado."\r
!\r
interface ".$datos['interfacelan'].".2\r
description IPT Agencia ".$datos['agencia']."\r
encapsulation dot1Q 2\r
ip address ".$datos['ip1']." 255.255.255.0\r
h323-gateway voip interface\r
h323-gateway voip h323-id Ag_".$datos['agencia']."\r
h323-gateway voip bind srcaddr ".$datos['ip1']."\r
!\r
!\r";
$script .= fxo(1);
$script .= "\r
!\r
!\r";
$script .= fxs(1);
$script .= "\r
!\r
ccm-manager fallback-mgcp \r
ccm-manager redundant-host 10.132.69.42 10.132.69.45\r
ccm-manager mgcp\r
ccm-manager music-on-hold\r
!\r
mgcp\r
mgcp call-agent 10.132.69.40 service-type mgcp version 0.1\r
mgcp dtmf-relay voip codec all mode out-of-band\r
mgcp bind control source-interface ".$datos['interfacelan'].".2\r
mgcp bind media source-interface ".$datos['interfacelan'].".2\r
!\r
mgcp profile default\r
!\r
sccp local ".$datos['interfacelan'].".2\r
sccp ccm 10.132.69.45 identifier 2 version 7.0 \r
sccp ccm 10.132.69.42 identifier 1 version 7.0 \r
sccp ip precedence 2\r
sccp\r
!\r
sccp ccm group 1\r
associate ccm 1 priority 1\r
associate ccm 2 priority 2\r
associate profile 1 register CONFER_".$datos['agencia']."\r
!\r
dspfarm profile 1 conference  \r
description CONFER_".$datos['agencia']."\r
codec g711ulaw\r
codec g711alaw\r
codec g729ar8\r
codec g729abr8\r
codec g729r8\r
codec g729br8\r
maximum sessions 1\r
associate application SCCP\r
!\r
!\r
!\r
dial-peer voice 19 pots\r
trunkgroup FXO\r
description Local\r
destination-pattern 0[2-9]......\r
forward-digits 7\r
!\r
dial-peer voice 20 pots\r
trunkgroup FXO\r
description Celulares\r
destination-pattern 004[1-2]........\r
forward-digits 11\r
!\r
dial-peer voice 21 pots\r
trunkgroup FXO\r
description Nacionales\r
destination-pattern 002.........\r
forward-digits 11\r
!\r
dial-peer voice 22 pots\r
trunkgroup FXO\r
description Servicios\r
destination-pattern 00800.......\r
forward-digits 11\r
!\r
dial-peer voice 23 pots\r
trunkgroup FXO\r
description Servicios\r
destination-pattern 0171\r
forward-digits 3\r
!\r
dial-peer voice 24 pots\r
trunkgroup FXO\r
description Servicios\r
destination-pattern 0113\r
forward-digits 3\r
!\r
dial-peer voice 25 pots\r
trunkgroup FXO\r
description Servicios\r
destination-pattern 0122\r
forward-digits 3\r
!\r
!\r
dial-peer voice 100 voip\r
description ENTRANTES CCM Subscriber 1\r
preference 1\r
destination-pattern 505".$datos['nro']."99\r
session target ipv4:10.132.69.45\r
voice-class codec 1  \r
voice-class h323 1\r
dtmf-relay cisco-rtp h245-alphanumeric\r
no vad\r
ip qos dscp cs5 media\r
ip qos dscp cs3 signaling\r
!\r
dial-peer voice 101 voip\r
description ENTRANTES CCM Subscriber 2\r
preference 2\r
destination-pattern 505".$datos['nro']."99\r
session target ipv4:10.132.69.42\r
voice-class codec 1  \r
voice-class h323 1\r
dtmf-relay cisco-rtp h245-alphanumeric\r
no vad\r
ip qos dscp cs5 media\r
ip qos dscp cs3 signaling\r
!\r
dial-peer voice 102 voip\r
description ENTRANTES CCM Subscriber 3\r
preference 3\r
destination-pattern 505".$datos['nro']."99\r
session target ipv4:10.132.69.44\r
voice-class codec 1  \r
voice-class h323 1\r
dtmf-relay cisco-rtp h245-alphanumeric\r
no vad\r
ip qos dscp cs5 media\r
ip qos dscp cs3 signaling\r
!\r
dial-peer voice 103 voip\r
description Music On Hold PSTN\r
incoming called-number .T\r
voice-class codec 1  \r
no vad\r
!\r
";
$script .= fxs(2);
$script .= "
!\r
num-exp 1. 505".$datos['nro']."1.\r
num-exp 2. 505".$datos['nro']."2.\r
num-exp 3. 505".$datos['nro']."3.\r
num-exp 4. 505".$datos['nro']."4.\r
num-exp 6. 505".$datos['nro']."6.\r
num-exp 7. 505".$datos['nro']."7.\r
num-exp 8. 505".$datos['nro']."8.\r
num-exp 9. 505".$datos['nro']."9.\r
gateway \r
timer receive-rtp 1200\r
!\r
!\r
gatekeeper\r
shutdown\r
!\r
!\r
call-manager-fallback\r
secondary-dialtone 0\r
max-conferences 4 gain -6\r
transfer-system full-consult\r
ip source-address ".$datos['ip1']." port 2000\r
max-ephones 7\r
max-dn 24 dual-line\r
system message primary SRST Activo\r
transfer-pattern ..\r
moh music-on-hold.au\r
multicast moh 239.1.1.1 port 16384\r
date-format dd-mm-yy\r
!\r";
				$name = "new_".$datos['nro'].".txt";
				break;
				case 2:
			// VERSION TIP CUCM 7 ############################################################################################################
			$script = "
!\r
ip dhcp excluded-address ".$datos['ip1']." ".$datos['ip40']."\r
!\r
ip dhcp pool VLAN-IPT\r
network ".$datos['ip0']." 255.255.255.0\r
default-router ".$datos['ip1']."\r
option 150 ip 10.160.70.10 10.160.70.11 10.160.70.12\r
lease 0 8\r
!\r
!\r
trunk group FXO\r
max-calls any 12\r
max-retry 4\r
hunt-scheme round-robin\r
!\r
!\r
voice-card 0\r
dspfarm\r
dsp services dspfarm\r
!\r
!\r
voice call send-alert\r
voice rtp send-recv\r
!\r
voice service voip\r
allow-connections h323 to h323\r
supplementary-service h450.12\r
fax protocol t38 version 0 ls-redundancy 0 hs-redundancy 0 fallback none\r
!\r
voice class codec 1\r
codec preference 1 g711alaw\r
codec preference 2 g711ulaw\r
codec preference 3 g729br8\r
codec preference 4 g729r8\r
!\r
voice class h323 1\r
h225 timeout tcp establish 3\r
call start fast\r
call preserve\r
!\r
voice class dualtone-detect-params 1\r
freq-max-power 0\r
freq-min-power 35\r
freq-power-twist 15\r
freq-max-delay 40\r
cadence-variation 8\r
!\r
voice class custom-cptone Venezuela\r
dualtone disconnect\r
frequency 1450\r
cadence 500 500\r
!\r
voice class custom-cptone us-custom\r
dualtone busy\r
frequency 425\r
cadence 500 500\r
dualtone ringback\r
frequency 425\r
cadence 1000 4000\r
dualtone reorder\r
frequency 480 620\r
cadence 250 250\r
dualtone out-of-service\r
frequency 950\r
cadence 330 330\r
dualtone number-unobtainable\r
frequency 480 620\r
cadence 250 250\r
dualtone disconnect\r
frequency 425\r
cadence 500 500\r
!\r
".$quitado."\r
!\r
interface ".$datos['interfacelan'].".2\r
description IPT Agencia ".$datos['agencia']."\r
encapsulation dot1Q 2\r
ip address ".$datos['ip1']." 255.255.255.0\r
h323-gateway voip interface\r
h323-gateway voip h323-id Ag_".$datos['agencia']."\r
h323-gateway voip bind srcaddr ".$datos['ip1']."\r
!\r
!\r";
$script .= fxo(1);
$script .= "\r
!\r
!\r";
$script .= fxs(1);
$script .= "\r
!\r
ccm-manager fallback-mgcp \r
ccm-manager redundant-host 10.160.70.10 10.160.70.12\r
ccm-manager mgcp\r
ccm-manager music-on-hold\r
!\r
mgcp\r
mgcp call-agent 10.160.70.11 service-type mgcp version 0.1\r
mgcp dtmf-relay voip codec all mode out-of-band\r
mgcp bind control source-interface ".$datos['interfacelan'].".2\r
mgcp bind media source-interface ".$datos['interfacelan'].".2\r
mgcp behavior g729-variants static-pt\r
!\r
mgcp profile default\r
!\r
sccp local ".$datos['interfacelan'].".2\r
sccp ccm 10.160.70.11 identifier 3 version 4.1\r
sccp ccm 10.160.70.10 identifier 2 version 4.1\r
sccp ccm 10.160.70.12 identifier 1 version 4.1\r
sccp ip precedence 3\r
sccp\r
!\r
sccp ccm group 1\r
associate ccm 1 priority 1\r
associate ccm 2 priority 2\r
associate ccm 3 priority 3\r
associate profile 1 register CONFER_".$datos['agencia']."\r
!\r
dspfarm profile 1 conference  \r
description CONFER_".$datos['agencia']."\r
codec g711ulaw\r
codec g711alaw\r
codec g729ar8\r
codec g729abr8\r
codec g729r8\r
codec g729br8\r
maximum sessions 2\r
associate application SCCP\r
!\r
!\r
!\r
dial-peer voice 19 pots\r
trunkgroup FXO\r
description Local\r
destination-pattern 0[2-9]......\r
forward-digits 7\r
!\r
dial-peer voice 20 pots\r
trunkgroup FXO\r
description Celulares\r
destination-pattern 004[1-2]........\r
forward-digits 11\r
!\r
dial-peer voice 21 pots\r
trunkgroup FXO\r
description Nacionales\r
destination-pattern 002.........\r
forward-digits 11\r
!\r
dial-peer voice 22 pots\r
trunkgroup FXO\r
description Servicios\r
destination-pattern 00800.......\r
forward-digits 11\r
!\r
dial-peer voice 23 pots\r
trunkgroup FXO\r
description Servicios\r
destination-pattern 0171\r
forward-digits 3\r
!\r
dial-peer voice 24 pots\r
trunkgroup FXO\r
description Servicios\r
destination-pattern 0113\r
forward-digits 3\r
!\r
dial-peer voice 25 pots\r
trunkgroup FXO\r
description Servicios\r
destination-pattern 0122\r
forward-digits 3\r
!\r
!\r
dial-peer voice 100 voip\r
description ENTRANTES CCM Subscriber 1\r
preference 1\r
destination-pattern 505".$datos['nro']."99\r
session target ipv4:10.160.70.10\r
voice-class codec 1  \r
voice-class h323 1\r
dtmf-relay cisco-rtp h245-alphanumeric\r
no vad\r
ip qos dscp cs5 media\r
ip qos dscp cs3 signaling\r
!\r
dial-peer voice 101 voip\r
description ENTRANTES CCM Subscriber 2\r
preference 2\r
destination-pattern 505".$datos['nro']."99\r
session target ipv4:10.160.70.11\r
voice-class codec 1  \r
voice-class h323 1\r
dtmf-relay cisco-rtp h245-alphanumeric\r
no vad\r
ip qos dscp cs5 media\r
ip qos dscp cs3 signaling\r
!\r
dial-peer voice 102 voip\r
description ENTRANTES CCM Publisher\r
preference 3\r
destination-pattern 505".$datos['nro']."99\r
session target ipv4:10.160.70.12\r
voice-class codec 1  \r
voice-class h323 1\r
dtmf-relay cisco-rtp h245-alphanumeric\r
no vad\r
ip qos dscp cs5 media\r
ip qos dscp cs3 signaling\r
!\r
dial-peer voice 103 voip\r
description Music On Hold PSTN\r
incoming called-number .T\r
voice-class codec 1  \r
no vad\r
!\r
";
$script .= fxs(2);
$script .= "
!\r
num-exp 1. 505".$datos['nro']."1.\r
num-exp 2. 505".$datos['nro']."2.\r
num-exp 3. 505".$datos['nro']."3.\r
num-exp 4. 505".$datos['nro']."4.\r
num-exp 6. 505".$datos['nro']."6.\r
num-exp 7. 505".$datos['nro']."7.\r
num-exp 8. 505".$datos['nro']."8.\r
num-exp 9. 505".$datos['nro']."9.\r
gateway \r
timer receive-rtp 1200\r
!\r
!\r
gatekeeper\r
shutdown\r
!\r
!\r
call-manager-fallback\r
secondary-dialtone 0\r
max-conferences 4 gain -6\r
transfer-system full-consult\r
ip source-address ".$datos['ip1']." port 2000\r
max-ephones 7\r
max-dn 24 dual-line\r
system message primary SRST Activo\r
transfer-pattern ..\r
moh music-on-hold.au\r
multicast moh 239.1.1.1 port 16384\r
date-format dd-mm-yy\r
!\r";
				$name = "new_".$datos['nro'].".txt";
				break;
				case 3:
				// VERSION SWITCH ############################################################################################################
				$script ="no service pad\r
service tcp-keepalives-in\r
service timestamps debug datetime localtime\r
service timestamps log datetime localtime\r
service password-encryption\r
!\r
hostname 3560_".$region[$datos['region']]."_".$datos['agencia']."\r
!\r
boot-start-marker\r
boot-end-marker\r
!\r".'
enable secret 5 XXX/'."\r
!\r
username emergencia password 7 XXX\r
!\r
!\r
no aaa new-model\r
system mtu routing 1500\r
authentication mac-move permit\r
ip subnet-zero\r
!\r
!\r
ip domain-name XXX.com\r
!\r
mls qos map policed-dscp  24 26 46 to 0\r
mls qos srr-queue input bandwidth 90 10\r
mls qos srr-queue input threshold 1 8 16\r
mls qos srr-queue input threshold 2 34 66\r
mls qos srr-queue input buffers 67 33 \r
mls qos srr-queue input cos-map queue 1 threshold 2 1\r
mls qos srr-queue input cos-map queue 1 threshold 3 0\r
mls qos srr-queue input cos-map queue 2 threshold 1 2\r
mls qos srr-queue input cos-map queue 2 threshold 2 4 6 7\r
mls qos srr-queue input cos-map queue 2 threshold 3 3 5\r
mls qos srr-queue input dscp-map queue 1 threshold 2 9 10 11 12 13 14 15\r
mls qos srr-queue input dscp-map queue 1 threshold 3 0 1 2 3 4 5 6 7\r
mls qos srr-queue input dscp-map queue 1 threshold 3 32\r
mls qos srr-queue input dscp-map queue 2 threshold 1 16 17 18 19 20 21 22 23\r
mls qos srr-queue input dscp-map queue 2 threshold 2 33 34 35 36 37 38 39 48\r
mls qos srr-queue input dscp-map queue 2 threshold 2 49 50 51 52 53 54 55 56\r
mls qos srr-queue input dscp-map queue 2 threshold 2 57 58 59 60 61 62 63\r
mls qos srr-queue input dscp-map queue 2 threshold 3 24 25 26 27 28 29 30 31\r
mls qos srr-queue input dscp-map queue 2 threshold 3 40 41 42 43 44 45 46 47\r
mls qos srr-queue output cos-map queue 1 threshold 3 5\r
mls qos srr-queue output cos-map queue 2 threshold 3 3 6 7\r
mls qos srr-queue output cos-map queue 3 threshold 3 2 4\r
mls qos srr-queue output cos-map queue 4 threshold 2 1\r
mls qos srr-queue output cos-map queue 4 threshold 3 0\r
mls qos srr-queue output dscp-map queue 1 threshold 3 40 41 42 43 44 45 46 47\r
mls qos srr-queue output dscp-map queue 2 threshold 3 24 25 26 27 28 29 30 31\r
mls qos srr-queue output dscp-map queue 2 threshold 3 48 49 50 51 52 53 54 55\r
mls qos srr-queue output dscp-map queue 2 threshold 3 56 57 58 59 60 61 62 63\r
mls qos srr-queue output dscp-map queue 3 threshold 3 16 17 18 19 20 21 22 23\r
mls qos srr-queue output dscp-map queue 3 threshold 3 32 33 34 35 36 37 38 39\r
mls qos srr-queue output dscp-map queue 4 threshold 1 8\r
mls qos srr-queue output dscp-map queue 4 threshold 2 9 10 11 12 13 14 15\r
mls qos srr-queue output dscp-map queue 4 threshold 3 0 1 2 3 4 5 6 7\r
mls qos queue-set output 1 threshold 1 138 138 92 138\r
mls qos queue-set output 1 threshold 2 138 138 92 400\r
mls qos queue-set output 1 threshold 3 36 77 100 318\r
mls qos queue-set output 1 threshold 4 20 50 67 400\r
mls qos queue-set output 2 threshold 1 149 149 100 149\r
mls qos queue-set output 2 threshold 2 118 118 100 235\r
mls qos queue-set output 2 threshold 3 41 68 100 272\r
mls qos queue-set output 2 threshold 4 42 72 100 242\r
mls qos queue-set output 1 buffers 10 10 26 54\r
mls qos queue-set output 2 buffers 16 6 17 61\r
mls qos\r
!\r
spanning-tree mode pvst\r
spanning-tree etherchannel guard misconfig\r
spanning-tree extend system-id\r
!\r
!\r
!\r
!\r
vlan internal allocation policy ascending\r
!\r
!\r
class-map match-all AutoQoS-VoIP-RTP-Trust\r
 match ip dscp ef \r
class-map match-all AutoQoS-VoIP-Control-Trust\r
 match ip dscp cs3  af31 \r
!\r
!\r
policy-map AutoQoS-Police-CiscoPhone\r
 class AutoQoS-VoIP-RTP-Trust\r
  set dscp ef\r
  police 320000 8000 exceed-action policed-dscp-transmit\r
 class AutoQoS-VoIP-Control-Trust\r
  set dscp cs3\r
  police 32000 8000 exceed-action policed-dscp-transmit\r
!\r
\r";
$script .= pswitch($datos['pswitch']);
$script .= "\r
interface Vlan1\r
 ip address ".$datos['iplan200']." 255.255.255.0\r
 no ip route-cache\r
 no shutdown\r
!\r
ip default-gateway ".$datos['iplan1']."\r
no ip http server\r
ip http secure-server\r
ip sla enable reaction-alerts\r
logging 10.1.1.213\r
access-list 95 deny   any log\r
access-list 95 permit 10.150.14.0 0.0.0.255\r
access-list 95 permit 10.150.13.0 0.0.0.255\r
access-list 95 permit 10.1.10.0 0.0.0.255\r
access-list 95 permit 10.33.144.0 0.0.0.255\r
access-list 95 permit 10.33.147.0 0.0.0.255\r
access-list 95 permit 10.33.145.0 0.0.0.255\r
access-list 95 permit 10.99.76.0 0.0.0.255\r
access-list 95 permit 10.51.149.0 0.0.0.255\r
access-list 95 permit 10.150.65.0 0.0.0.255\r
access-list 95 permit 10.0.20.0 0.0.0.255\r
access-list 95 permit 10.16.84.0 0.0.0.255\r
access-list 95 permit 10.16.86.0 0.0.0.255\r
access-list 95 permit 10.1.15.0 0.0.0.255\r
access-list 99 permit 10.1.11.41\r
access-list 99 permit 10.1.17.49\r
access-list 99 permit 10.1.0.38\r
access-list 99 permit 10.1.36.107\r
access-list 99 permit 10.132.71.134\r
access-list 99 permit 10.150.14.35\r
access-list 99 permit 10.132.75.36\r
access-list 99 permit 10.124.7.133\r
access-list 99 deny   any log\r
snmp-server engineID local 00000009020000070EED4940\r
snmp-server community sw1tch3sB@n RO 99\r
snmp-server community XXX RO 99\r
snmp-server community XXX RO 99\r
snmp-server location Agencia ".$datos['nro']."\r
snmp-server contact Gerencia de Telecomunicaciones Tlf 5018989\r
snmp-server host 10.1.13.180 XXX \r
snmp-server host 10.1.13.182 XXX \r
snmp-server host 10.132.71.134 XXX \r
tacacs-server key 7 XXX\r
!\r
banner motd ^CCCC\r
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * *\r

^C\r
!\r
line con 0\r
 exec-timeout 5 0\r
 stopbits 1\r
line vty 0 15\r
 transport input all\r
 exec-timeout 5 0\r
 login local\r
 length 0\r
!\r
ntp source Vlan1\r
ntp server 10.7.74.1\r
ntp server 10.1.17.1\r
end\r";
				$name = "new_3560_".$datos['nro'].".txt";
				break;
				case 4:
				//VERSION RT CUCM 9 ############################################################################################################
				$script ="service password-encryption\r
hostname ".$datos['modelo']."_".$region[$datos['region']]."_".$datos['agencia']."\r
!\r
!\r
logging buffered 4096\r
logging console emergencies\r
logging monitor warnings\r
".'enable secret 5 $1$O3lJ$7JdXzzV9hUixhhnzJKfjh.'."\r
!\r
aaa new-model\r
!\r
!\r
aaa authentication login default group tacacs+ local\r
aaa authorization config-commands\r
aaa authorization exec default group tacacs+ local \r
aaa authorization commands 15 default group tacacs+ local if-authenticated \r
aaa authorization configuration default group tacacs+ \r
aaa accounting exec default start-stop group tacacs+\r
aaa accounting commands 15 default start-stop group tacacs+\r
aaa accounting system default start-stop group tacacs+\r
!\r
!\r
!\r
!\r
!\r
aaa session-id common\r
!\r
clock timezone CCS -4 30\r
!\r
no ipv6 cef\r
no ip source-route\r
ip cef\r
!\r
ip dhcp excluded-address ".$datos['ip1']." ".$datos['ip40']."\r
!\r
interface Loopback0\r
ip address ".$datos['iploop']." 255.255.255.0\r
!\r
ip dhcp pool VLAN-IPT\r
network ".$datos['ip0']." 255.255.255.0\r
default-router ".$datos['ip1']."\r
option 150 ip 10.132.69.42 10.132.69.45 10.132.69.44\r
lease 0 8\r
!\r
!\r
ip flow-cache timeout active 1\r
no ip bootp server\r
no ip domain lookup\r
ip domain name intra.XXX.com\r
!\r
!\r
!\r
!\r
!\r
!\r
!\r
trunk group FXO\r
 max-calls any 12\r
 max-retry 4\r
 hunt-scheme round-robin\r
!\r
crypto pki token default removal timeout 0\r
!\r
!\r
voice-card 0\r
 dspfarm\r
 dsp services dspfarm\r
!\r
!\r
voice call send-alert\r
voice rtp send-recv\r
!\r
voice service voip\r
 allow-connections h323 to h323\r
 supplementary-service h450.12\r
 fax protocol t38 version 0 ls-redundancy 0 hs-redundancy 0 fallback none\r
!\r
voice class codec 1\r
 codec preference 1 g711alaw\r
 codec preference 2 g711ulaw\r
 codec preference 3 g729br8\r
 codec preference 4 g729r8\r
!\r
voice class h323 1\r
  h225 timeout tcp establish 3\r
  call start fast\r
!\r
voice class dualtone-detect-params 1\r
 freq-max-power 0\r
 freq-min-power 35\r
 freq-power-twist 15\r
 freq-max-delay 40\r
 cadence-variation 8\r
!\r
voice class custom-cptone Venezuela\r
 dualtone disconnect\r
  frequency 1450\r
  cadence 500 500\r
!\r
voice class custom-cptone us-custom\r
 dualtone busy\r
  frequency 425\r
  cadence 500 500\r
 dualtone ringback\r
  frequency 425\r
  cadence 1000 4000\r
 dualtone reorder\r
  frequency 480 620\r
  cadence 250 250\r
 dualtone out-of-service\r
  frequency 950\r
  cadence 330 330\r
 dualtone number-unobtainable\r
  frequency 480 620\r
  cadence 250 250\r
 dualtone disconnect\r
  frequency 425\r
  cadence 500 500\r
!\r
application\r
global\r
service alternate default\r
!\r
".$quitado."\r
!\r
!\r
username emergencia password 7 XXX\r
!\r
redundancy\r
!\r
!\r
!\r
!\r
ip ssh time-out 5\r
ip ssh version 2\r
!\r
class-map match-all QOS-QUEUE-SILVER\r
 match ip dscp af21 \r
class-map match-all QOS-QUEUE-BRONZE\r
 match ip dscp af11 \r
class-map match-all QOS-REMARK-VOICE\r
 match protocol rtp audio \r
class-map match-any QOS-QUEUE-VOICE-SIG\r
 match ip dscp cs3 \r
class-map match-any QOS-REMARK-BRONZE\r
 match access-group name QOS-BRONZE\r
class-map match-all QOS-QUEUE-VOICE\r
 match ip dscp ef \r
class-map match-any QOS-REMARK-SILVER\r
 match access-group name QOS-SILVER\r
class-map match-any QOS-REMARK-VOICE-SIG\r
 match access-group name QOS-VOICE-SIG\r
!\r
!\r
policy-map child-1Mbps\r
 class QOS-QUEUE-VOICE\r
  priority percent 4\r
  set cos 5\r
 class QOS-QUEUE-VOICE-SIG\r
  bandwidth remaining percent 6\r
  set cos 4\r
 class QOS-QUEUE-SILVER\r
  bandwidth remaining percent 60\r
  set cos 3\r
 class QOS-QUEUE-BRONZE\r
  bandwidth remaining percent 30\r
  set cos 2\r
policy-map Parent-1Mbps\r
 class class-default\r
  shape average 1000000\r
  service-policy child-1Mbps\r
policy-map QOS-REMARK\r
 class QOS-REMARK-VOICE\r
  set dscp ef\r
 class QOS-REMARK-BRONZE\r
  set dscp af11\r
 class QOS-REMARK-SILVER\r
  set dscp af21\r
 class QOS-REMARK-VOICE-SIG\r
  set dscp cs3\r
!\r
!\r
interface ".$datos['interfacelan']."\r
 description Conexion LAN a la Agencia ".$datos['agencia']."\r
 ip address ".$datos['iplan1']." 255.255.255.0\r
 no ip redirects\r
 no ip unreachables\r
 no ip proxy-arp\r
 duplex full\r
 speed 100\r
 service-policy input QOS-REMARK\r
  no shut\r
!\r
interface ".$datos['interfacelan'].".2\r
description IPT Agencia ".$datos['agencia']."\r
encapsulation dot1Q 2\r
ip address ".$datos['ip1']." 255.255.255.0\r
h323-gateway voip interface\r
h323-gateway voip h323-id Ag_".$datos['agencia']."\r
h323-gateway voip bind srcaddr ".$datos['ip1']."\r
!\r
!\r
no ip forward-protocol nd\r
!\r
no ip http server\r
no ip http secure-server\r
ip flow-export source Loopback0\r
ip flow-export version 9\r
ip flow-export destination 10.132.71.135 9996\r
ip flow-top-talkers\r
 top 50\r
 sort-by packets\r
 cache-timeout 30000\r
!\r
ip tacacs source-interface Loopback0\r
!\r
ip access-list extended QOS-BRONZE\r
 remark WEB\r
 permit tcp any any eq 443\r
 permit tcp any eq 443 any\r
 permit udp any any eq domain\r
 permit udp any eq domain any\r
 permit tcp any any eq domain\r
 permit tcp any eq domain any\r
 permit tcp any any eq www\r
 permit tcp any eq www any\r
 remark MANAGEMENT\r
 permit ip any host 10.1.1.213\r
 permit ip host 10.1.1.213 any\r
 permit tcp any any eq telnet\r
 permit tcp any eq telnet any\r
 permit tcp any any eq 22\r
 permit tcp any eq 22 any\r
 permit tcp any any eq ftp-data\r
 permit tcp any eq ftp-data any\r
 permit tcp any any eq ftp\r
 permit tcp any eq ftp any\r
 permit udp any any eq tftp\r
 permit udp any eq tftp any\r
 remark ITM\r
 permit ip any host 10.1.0.26\r
 permit ip host 10.1.0.26 any\r
 remark TIVOLI\r
 permit ip any host 10.1.9.33\r
 permit ip host 10.1.9.33 any\r
 remark WSUS\r
 permit ip any host 10.1.37.42\r
 permit ip host 10.1.37.42 any\r
 remark ANTIVIRUS\r
 permit ip any host 10.35.217.229\r
 permit ip host 10.35.217.229 any\r
 permit ip any host 10.33.144.52\r
 permit ip host 10.33.144.52 any\r
ip access-list extended QOS-SILVER\r
 remark WINDOWS\r
 permit ip any host 10.33.144.65\r
 permit ip host 10.33.144.65 any\r
 permit ip any host 10.35.217.227\r
 permit ip host 10.35.217.227 any\r
 permit ip any host 10.1.1.58\r
 permit ip host 10.1.1.58 any\r
 remark VBANKER-EMULACION\r
 permit ip any host 10.1.34.11\r
 permit ip host 10.1.34.11 any\r
 permit ip any host 10.1.34.9\r
 permit ip host 10.1.34.9 any\r
 permit ip any host 10.1.8.50\r
 permit ip host 10.1.8.50 any\r
 permit ip any host 10.1.6.16\r
 permit ip host 10.1.6.16 any\r
 permit ip any host 10.1.6.17\r
 permit ip host 10.1.6.17 any\r
 permit ip any host 10.1.6.18\r
 permit ip host 10.1.6.18 any\r
 permit ip any host 10.1.6.19\r
 permit ip host 10.1.6.19 any\r
 permit ip any host 10.1.6.20\r
 permit ip host 10.1.6.20 any\r
 permit ip any host 10.1.6.21\r
 permit ip host 10.1.6.21 any\r
 permit ip any host 10.1.6.22\r
 permit ip host 10.1.6.22 any\r
 permit ip any host 10.1.6.23\r
 permit ip host 10.1.6.23 any\r
 permit ip any host 10.1.0.234\r
 permit ip host 10.1.0.234 any\r
 permit ip any host 10.1.0.236\r
 permit ip host 10.1.0.236 any\r
 permit ip any host 10.33.144.40\r
 permit ip host 10.33.144.40 any\r
 permit ip any host 10.33.144.58\r
 permit ip host 10.33.144.58 any\r
 permit ip any host 10.1.10.25\r
 permit ip any host 10.1.10.26\r
 permit ip any host 10.1.13.214\r
 permit ip any host 10.1.13.219\r
 permit ip any host 10.1.13.220\r
 permit ip any host 10.1.13.205\r
 permit ip any host 10.1.13.206\r
 permit ip any host 10.1.13.211\r
 permit ip any host 10.1.13.212\r
 permit ip any host 10.2.2.5\r
 permit ip any host 10.2.2.6\r
 remark TRUNCAMIENTO\r
 permit ip any host 10.150.54.127\r
 permit ip host 10.150.54.127 any\r
 permit ip any host 10.150.55.58\r
 permit ip host 10.150.55.58 any\r
 permit ip any host 10.0.87.180\r
 permit ip host 10.0.87.180 any\r
\r
ip access-list extended QOS-VOICE-SIG\r
 permit tcp any any eq 2000\r
 permit tcp any any eq 2443\r
 permit tcp any any eq 5060\r
 permit udp any any eq 5060\r
 permit tcp any any eq 5061\r
 permit udp any any eq 5061\r
 permit tcp any any eq 1718\r
 permit udp any any eq 1719\r
 permit tcp any any eq 1720\r
 permit udp any any eq 2427\r
 permit tcp any any eq 2428\r
 permit tcp any eq 2000 any\r
 permit tcp any eq 2443 any\r
 permit tcp any eq 5060 any\r
 permit udp any eq 5060 any\r
 permit tcp any eq 5061 any\r
 permit udp any eq 5061 any\r
 permit tcp any eq 1718 any\r
 permit udp any eq 1719 any\r
 permit tcp any eq 1720 any\r
 permit udp any eq 2427 any\r
 permit tcp any eq 2428 any\r
!\r
logging source-interface Loopback0\r
logging 10.1.1.213\r
access-list 69 permit 10.1.7.5\r
access-list 69 deny   any log\r
access-list 99 permit 10.1.0.38\r
access-list 99 permit 10.1.36.107\r
access-list 99 permit 10.132.71.134\r
access-list 99 permit 10.1.13.82\r
access-list 99 permit 10.150.14.35\r
access-list 99 permit 10.132.75.36\r
access-list 99 permit 10.1.8.241\r
access-list 99 permit 10.124.7.133\r
access-list 99 permit 10.1.12.242\r
access-list 99 permit 10.160.70.16\r
access-list 99 deny   any log\r
!\r
!\r
!\r
!\r
!\r
snmp-server engineID local 000000090200000A0AD05B81\r
snmp-server community XXX RO 99\r
snmp-server community XXX RO 99\r
snmp-server community m0d!FyBan RW 99\r
snmp-server ifindex persist\r
snmp-server trap-source Loopback0\r
snmp-server location Agencia ".$datos['agencia']."\r
snmp-server enable traps snmp authentication linkdown linkup coldstart warmstart\r
snmp-server enable traps tty\r
snmp-server enable traps xgcp\r
snmp-server enable traps cnpd\r
snmp-server enable traps config-copy\r
snmp-server enable traps config\r
snmp-server enable traps entity\r
snmp-server enable traps ipmulticast\r
snmp-server enable traps msdp\r
snmp-server host 10.1.0.38 XXX \r
snmp-server host 10.1.13.180 XXX \r
snmp-server host 10.1.13.182 XXX \r
snmp-server host 10.1.36.107 XXX \r
snmp-server host 10.1.7.5 XXX \r
snmp-server host 10.1.8.213 XXX \r
snmp-server host 10.132.71.134 m0d!FyBan \r
snmp-server host 10.132.71.134 XXX \r
tacacs-server host 10.1.8.105\r
tacacs-server host 10.1.9.96\r
tacacs-server host 10.0.20.63\r
tacacs-server host 10.0.20.203\r
tacacs-server timeout 3\r
tacacs-server directed-request\r
tacacs-server key 7 111D4A0944115B01163F7B\r
!\r
!\r
!\r
!\r";
$script .= fxo(1);
$script .= "\r
!\r";
$script .= fxs(1);
$script .= "\r
!\r
!\r
ccm-manager fallback-mgcp \r
ccm-manager redundant-host 10.132.69.42 10.132.69.44\r
ccm-manager mgcp\r
ccm-manager music-on-hold\r
!\r
mgcp\r
mgcp call-agent 10.132.69.40 service-type mgcp version 0.1\r
mgcp dtmf-relay voip codec all mode out-of-band\r
mgcp bind control source-interface ".$datos['interfacelan'].".2\r
mgcp bind media source-interface ".$datos['interfacelan'].".2\r
!\r
mgcp profile default\r
!\r
sccp local GigabitEthernet0/0.2\r
sccp ccm 10.132.69.42 identifier 2 version 7.0 \r
sccp ccm 10.132.69.45 identifier 1 version 7.0 \r
sccp ip precedence 2\r
sccp\r
!\r
sccp ccm group 1\r
 associate ccm 1 priority 1\r
 associate ccm 2 priority 2\r
 associate profile 1 register CONFER_".$datos['agencia']."\r
!\r
dspfarm profile 1 conference  \r
 description CONFER_".$datos['agencia']."\r
 codec g711ulaw\r
 codec g711alaw\r
 codec g729ar8\r
 codec g729abr8\r
 codec g729r8\r
 codec g729br8\r
 maximum sessions 1\r
 associate application SCCP\r
!\r
!\r
!\r
dial-peer voice 19 pots\r
 trunkgroup FXO\r
 description Local\r
 destination-pattern 0[2-9]......\r
 forward-digits 7\r
!\r
dial-peer voice 20 pots\r
 trunkgroup FXO\r
 description Celulares\r
 destination-pattern 004[1-2]........\r
 forward-digits 11\r
!\r
dial-peer voice 21 pots\r
 trunkgroup FXO\r
 description Nacionales\r
 destination-pattern 002.........\r
 forward-digits 11\r
!\r
dial-peer voice 22 pots\r
 trunkgroup FXO\r
 description Servicios\r
 destination-pattern 00800.......\r
 forward-digits 11\r
!\r
dial-peer voice 23 pots\r
 trunkgroup FXO\r
 description Servicios\r
 destination-pattern 0171\r
 forward-digits 3\r
!\r
dial-peer voice 24 pots\r
 trunkgroup FXO\r
 description Servicios\r
 destination-pattern 0113\r
 forward-digits 3\r
!\r
dial-peer voice 25 pots\r
 trunkgroup FXO\r
 description Servicios\r
 destination-pattern 0122\r
 forward-digits 3\r
!\r
!\r
dial-peer voice 100 voip\r
 description ENTRANTES CCM Subscriber 1\r
 preference 1\r
 destination-pattern 505".$datos['nro']."99\r
 session target ipv4:10.132.69.42\r
 voice-class codec 1  \r
 voice-class h323 1\r
 dtmf-relay cisco-rtp h245-alphanumeric\r
 no vad\r
   ip qos dscp cs5 media\r
 ip qos dscp cs3 signaling\r
!\r
dial-peer voice 101 voip\r
 description ENTRANTES CCM Subscriber 2\r
 preference 2\r
 destination-pattern 505".$datos['nro']."99\r
 session target ipv4:10.132.69.45\r
 voice-class codec 1  \r
 voice-class h323 1\r
 dtmf-relay cisco-rtp h245-alphanumeric\r
 no vad\r
   ip qos dscp cs5 media\r
 ip qos dscp cs3 signaling\r
!\r
dial-peer voice 102 voip\r
 description ENTRANTES CCM Publisher\r
 preference 3\r
 destination-pattern 505".$datos['nro']."99\r
 session target ipv4:10.132.69.44\r
 voice-class codec 1  \r
 voice-class h323 1\r
 dtmf-relay cisco-rtp h245-alphanumeric\r
 no vad\r
   ip qos dscp cs5 media\r
 ip qos dscp cs3 signaling\r
!\r
dial-peer voice 103 voip\r
 description Music On Hold PSTN\r
 incoming called-number .T\r
 voice-class codec 1  \r
 no vad\r
!\r";
$script .= fxs(2);
$script .= "!\r
!\r
num-exp 1. 505".$datos['nro']."1.\r
num-exp 2. 505".$datos['nro']."2.\r
num-exp 3. 505".$datos['nro']."3.\r
num-exp 4. 505".$datos['nro']."4.\r
num-exp 6. 505".$datos['nro']."6.\r
num-exp 7. 505".$datos['nro']."7.\r
num-exp 8. 505".$datos['nro']."8.\r
num-exp 9. 505".$datos['nro']."9.\r
gateway \r
timer receive-rtp 1200\r
!\r
!\r
!\r
gatekeeper\r
 shutdown\r
!\r
!\r
call-manager-fallback\r
 secondary-dialtone 0\r
 max-conferences 4 gain -6\r
 transfer-system full-consult\r
ip source-address ".$datos['ip1']." port 2000\r
 max-ephones 7\r
 max-dn 24 dual-line\r
 system message primary SRST Activo\r
 transfer-pattern ..\r
 moh music-on-hold.au\r
 multicast moh 239.1.1.1 port 16384\r
 date-format dd-mm-yy\r
!\r
!\r
!\r
banner motd ^CC\r
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * *\r
^C\r
!\r
line con 0\r
line aux 0\r
line 2\r
 no activation-character\r
 no exec\r
 transport preferred none\r
 transport input all\r
 transport output pad telnet rlogin lapb-ta mop udptn v120 ssh\r
 stopbits 1\r
line vty 0 15\r
 exec-timeout 5 0\r
 transport input ssh\r
 !CONFIRMAR LISTA DE ACCESO\r
 no access-list 22\r
!\r
scheduler allocate 20000 1000\r
ntp source Loopback0\r
ntp server 10.1.17.1\r
ntp server 10.1.17.2\r
end\n";
				$name = "new_".$datos['nro'].".txt";
				break;
			}
				force_download($name, $script);
				//echo nl2br($script);

		}
		public function acd()    {
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->database();
		$datos = array(
			'ani' => $this->input->post('ani'),
			'dnis' => $this->input->post('dnis'),
			'acd' => $this->input->post('acd'),
			'date1' => $this->input->post('date1'),
			'date2' => $this->input->post('date2')
		 );
	    $this->load->model('Awro');
		$query['row'] = $this->Awro->consultar($datos);
		$this->load->view('header');
		$this->load->view('search',$query);
		$this->load->view('footer');

	}

	}
