<?php
/*******************************************************************************
  *  WireMailSmtp | hnsmtp
  *
  *  @version     -   '0.4.1'
  *  @date        -   2019/04/19
  *  @author      -   Horst Nogajski
  *  @licence     -   GNU GPL v2 - http://www.gnu.org/licenses/gpl-2.0.html
  *  @licence     -   MIT - https://processwire.com/about/license/mit/
********************************************************************************/


require_once( dirname(__FILE__) . '/smtp_classes/email_message.php' );
require_once( dirname(__FILE__) . '/smtp_classes/smtp_message.php' );
require_once( dirname(__FILE__) . '/smtp_classes/smtp.php' );
require_once( dirname(__FILE__) . '/smtp_classes/sasl.php' );



class hnsmtp {

	private $default_charset               = 'UTF-8';

	private $smtp_host                     = '';                    /* SMTP server host name                        */
	private $smtp_port                     = 25;                    /* SMTP server host port,
                                                                       usually 25 but for use with SSL or TLS 587   */
    private $smtp_ssl                      = 0;                     /* Establish secure connections using SSL       */
    private $smtp_ssl_crypto_method        = '';                    /* Define the crypto method to use with SSL     */
    private $smtp_start_tls                = 0;                     /* Establish secure connections using START_TLS */
    private $smtp_tls_crypto_method        = '';                    /* Define the crypto method to use with TLS     */

	private $localhost                     = '';                    /* this computers address                       */
	private $realm                         = '';                    /* Authentication realm or domain               */
	private $workstation                   = '';                    /* Workstation for NTLM authentication          */
	private $authentication_mechanism      = '';                    /* SASL authentication mechanism                */

	private $smtp_user                     = '';                    /* Authentication user name                     */
	private $smtp_password                 = '';                    /* Authentication password                      */

	private $smtp_debug                    = 0;                     /* Output debug information                     */
	private $smtp_html_debug               = 0;                     /* Debug information is in HTML                 */

	private $sender_name                   = '';                    // From: the senders name
	private $sender_email                  = '';                    // From: the senders email address
	private $sender_reply                  = '';                    // Reply-To: optional email address
	private $sender_errors_to              = '';                    // Errors-To: optional email address
	private $sender_signature              = '';                    // a Signature Text, like Contact Data and / or Confidentiality Notices
	private $sender_signature_html         = '';                    // a Signature Text in HTML, like Contact Data and / or Confidentiality Notices
	private $send_sender_signature         = 1;                     // when the signature should be send: with every mail | only when the default Email is the sender | only when explicitly called via the API

	private $extra_headers                 = array();               // optional Custom-Meta-Headers
	private $valid_recipients              = array();               /* SenderEmailAddresses wich are allowed to
																	   receive Messages                             */

	private $smtp_certificate              = false;                 // @flydev: https://processwire.com/talk/topic/5704-wiremailsmtp/page-5#entry113290

	private $aValidVars                    = null;
	private $emailMessage                  = null;
	private $connected                     = null;
	private $errors                        = array();


	private $from                          = '';
	private $fromName                      = '';


	public function testConnection($debug = false) {
		$res = $this->connect($debug);
		$this->close();
		return $res;
	}

	public function connect($debug = false) {
		$this->connected = (($this->errors[] = $this->emailMessage->StartSendingMessage()) == '') ? true : false;
		return $this->connected;
	}

	public function close() {
		if(!isset($this->emailMessage)) {
			return null;
		}
		$res = $this->emailMessage->ResetConnection('') == '' ? true : false;
		$this->connected = false;
		return $res;
	}

	public function getErrors() {
		$a = array();
		foreach( $this->errors as $e ) {
			if($e=='') {
				continue;
			}
			$a[] = $e;
		}
		$this->errors = $a;
		return $this->errors;
	}




	private function set_var_val( $k, $v ) {
		if(!in_array($k, $this->aValidVars)) {
			return;
		}
		switch($k) {
			case 'send_sender_signature':
			case 'smtp_port':
				$this->$k = intval($v);
				break;

			case 'smtp_certificate':
			case 'smtp_ssl':
			case 'smtp_start_tls':
			case 'smtp_debug':
			case 'smtp_html_debug':
				if(is_bool($v)) {
					$this->$k = $v==true ? 1 : 0;
				}
				elseif(is_int($v)) {
					$this->$k = $v==1 ? 1 : 0;
				}
				elseif(is_string($v) && in_array($v, array('1','on','On','ON','true','TRUE'))) {
					$this->$k = 1;
				}
				elseif(is_string($v) && in_array($v, array('0','off','Off','OFF','false','FALSE'))) {
					$this->$k = 0;
				}
				else {
					$this->$k = 0;
				}
				break;

            case 'smtp_tls_crypto_method':
                $availableTLSmethods = WireMailSmtp::getCryptoMethodsTLS();
                if(is_string($v) && isset($v, $availableTLSmethods)) {
                    $this->$k = $v;
                }
                break;

            case 'smtp_ssl_crypto_method':
                $availableSSLmethods = WireMailSmtp::getCryptoMethodsSSL();
                if(is_string($v) && isset($v, $availableSSLmethods)) {
                    $this->$k = $v;
                }
                break;

			case 'authentication_mechanism':
				$this->authentication_mechanism = $v;
				break;

			case 'valid_recipients':
			case 'extra_headers';
				$this->$k = is_array($v) || is_string($v) ? (array)$v : array();
				break;

			default:
				if(in_array($k, array('smtp_host', 'smtp_user', 'smtp_password',
				                       'localhost', 'workstation', 'realm',
				                       'sender_name', 'sender_email', 'sender_reply',
				                       'sender_errors_to', 'sender_signature', 'sender_signature_html',
				                       'default_charset'
				                       ))
				) {
					$this->$k = strval($v);
				}
		}


	}

	public function __construct($aConfig = null) {
		if(!is_array($aConfig)) {
			return;
		}

		$this->aValidVars = get_class_vars(__CLASS__);
		foreach($aConfig as $k => $v) {
			$this->set_var_val($k, $v);
		}

		foreach($this->valid_recipients as $k=>$v) {
			$this->valid_recipients[$k] = str_replace(array('<','>'), '', strtolower(trim($v)));
		}

		// start SMTP-Mail
		$this->emailMessage = new smtp_message_class();

		// SMTP Server Authentication
		$this->emailMessage->default_charset           = $this->default_charset;
		$this->emailMessage->localhost                 = $this->localhost;
		$this->emailMessage->smtp_host                 = $this->smtp_host;
		$this->emailMessage->smtp_port                 = $this->smtp_port;
		$this->emailMessage->smtp_ssl                  = $this->smtp_ssl;
        $this->emailMessage->smtp_ssl_crypto_method    = $this->smtp_ssl_crypto_method;
		$this->emailMessage->smtp_start_tls            = $this->smtp_start_tls;
        $this->emailMessage->smtp_tls_crypto_method    = $this->smtp_tls_crypto_method;
        $this->emailMessage->smtp_user                 = $this->smtp_user;
		$this->emailMessage->smtp_password             = $this->smtp_password;
		$this->emailMessage->smtp_certificate          = $this->smtp_certificate;

		// advanced SMTP Server Settings
		$this->emailMessage->realm                     = $this->realm;
		$this->emailMessage->workstation               = $this->workstation;
		$this->emailMessage->authentication_mechanism  = $this->authentication_mechanism;

		// Debug on / off
		$this->emailMessage->smtp_debug                = $this->smtp_debug;
		$this->emailMessage->smtp_html_debug           = $this->smtp_html_debug;

	}

	public function __destruct() {
		if( $this->connected ) {
			$this->close();
		}
		unset($this->emailMessage);
	}




	private function logError($msg) {
		if(!isset($this->module)) $this->module = wire('modules')->get('WireMailSmtp');
		$this->module->logError($msg);
	}

	private function logActivity($msg) {
		if(!isset($this->module)) $this->module = wire('modules')->get('WireMailSmtp');
		$this->module->logActivity($msg);
	}





	public function setSender($from='', $fromName='') {
        $genericEmail = isset($this->localhost) ? 'processwire@' . $this->localhost : false;
		$sender = strlen($from)>0 ? $from : $this->sender_email;
		if(empty($sender) && false!==$genericEmail) {
			$sender = $genericEmail;  // fallback to a generic email address
		}
        $this->isValidEmailadress($sender); // if it is not a valid Emailaddress a Error is thrown

		$senderName = strlen($fromName)>0 ? $fromName : $this->sender_name;

        $this->from = $sender;
        $this->fromName = $senderName;

        if($sender==$this->sender_email) {
			// we use the defaults from module config
			$replyTo = isset($this->sender_reply) && strlen($this->sender_reply)>0 ? $this->sender_reply : $this->sender_email;
			$errorsTo = isset($this->sender_errors_to) && strlen($this->sender_errors_to)>0 ? $this->sender_errors_to : $this->sender_email;
        }
        else {
			$replyTo = $genericEmail!=$sender ? $sender : ''; // we don't want get replys to the generic emailaddress
			$errorsTo = '';
        }
		$this->setEmailHeader('from', $sender, $senderName);
		if(''!=$replyTo) $this->setEmailHeader('reply', $replyTo);
		if(''!=$errorsTo) $this->setEmailHeader('errors', $errorsTo);
	}


	public function setCustomHeader($header) {
		$extra_headers = (isset($this->extra_headers) && is_array($this->extra_headers) && 0<count($this->extra_headers)) ? $this->extra_headers : array();
		$headers = array_merge($extra_headers, $header);
		foreach($headers as $k=>$v) {
			$this->setHeader($k, $v);
		}
	}


	public function setTextBody($text, $addSignature, $wrapText=true, &$maildata) {
		if($addSignature===true && isset($this->sender_signature) && is_string($this->sender_signature) && strlen(trim($this->sender_signature))>0) {
			$text .= "\r\n\r\n" . $this->sender_signature;
		}
		$text = $wrapText ? $this->emailMessage->WrapText($text) : (string)$text;
		$maildata = $text;
		$ret = $this->emailMessage->AddQuotedPrintableTextPart($text);
		if($ret=='') {
			return true;
		}
		$this->logError('Error in '.__CLASS__.'::'.__FUNCTION__.' : ' . $ret);
		return false;
	}


	public function setTextAndHtmlBody($text, $html, $addSignature, $wrapText=true, &$maildata1, &$maildata2) {
		if($addSignature===true && isset($this->sender_signature) && is_string($this->sender_signature) && strlen(trim($this->sender_signature))>0) {
			$text .= "\r\n\r\n--\r\n" . $this->sender_signature;
		}
		if($addSignature===true && isset($this->sender_signature_html) && is_string($this->sender_signature_html) && strlen(trim($this->sender_signature_html))>0) {
            // we first need to check if there is a </body> end tag in the html-markup
            if(preg_match('</body>', $html)) {
                $html = str_replace("</body>", "\r\n\r\n" . $this->sender_signature_html . "\r\n</body>", $html);
            } else {
                $html .= "\r\n\r\n" . $this->sender_signature_html . "\r\n";
            }
		}

		$maildata1 = $text = $wrapText ? $this->emailMessage->WrapText($text) : (string)$text;
		$maildata2 = $html = $wrapText ? $this->emailMessage->WrapText($html) : (string)$html;

        // create Alternative-Multipart
		$html_part = $text_part = $alternative_part = 0;
		$this->emailMessage->CreateQuotedPrintableTextPart($text, 'UTF-8', $text_part);
		$this->emailMessage->CreateQuotedPrintableHTMLPart($html, 'UTF-8', $html_part);
		$alternative_parts = array( $text_part, $html_part );
		#$this->emailMessage->CreateAlternativeMultipart($alternative_parts, $alternative_part);
		$ret = $this->emailMessage->AddAlternativeMultipart($alternative_parts);

		if($ret=='') {
			return true;
		}
		$this->logError('Error in '.__CLASS__.'::'.__FUNCTION__.' : ' . $ret);
		return false;
	}


	public function addAttachment($attachment) {
		$ret = $this->emailMessage->AddFilePart($attachment);
		if($ret=='') {
			return true;
		}
		$this->logError('Error in '.__CLASS__.'::'.__FUNCTION__.' : ' . $ret);
		return false;
	}


	public function setNotification(&$maildata) {
		if(!isset($this->from) || strlen(trim($this->from))==0) {
			return false;
		}
		$email = $this->bundleEmailAndName($this->from, $this->fromName);
		$maildata = $email;
		return $this->setHeader('Disposition-Notification-To', $email);
	}


	public function setPriority($priority=3) {
		$priority = intval($priority);
		if(!in_array($priority, array(1, 2, 3, 4, 5))) {
			$this->logError('Error in '.__CLASS__.'::'.__FUNCTION__.' : ' . "($priority)");
			return false;
		}
		$priorities = array(
			 5 => array('5 (Lowest)',  'Low',    'Low'),
			 4 => array('4 (Low)',     'Low',    'Low'),
			 3 => array('3 (Normal)',  'Normal', 'Normal'),
			 2 => array('2 (High)',    'High',   'High'),
			 1 => array('1 (Highest)', 'High',   'High')
		);
		$ret = 0;
		$ret += $this->setHeader('X-Priority', $priorities[$priority][0]) ? 1 : 0;
		$ret += $this->setHeader('X-MSMail-Priority', $priorities[$priority][1]) ? 1 : 0;
		$ret += $this->setHeader('Importance', $priorities[$priority][2]) ? 1 : 0;
		return 3==$ret ? true : false;
	}




	public function send($debugServer=false, $htmlDebug=false, &$maildata) {
        if($debugServer) $this->emailMessage->smtp_debug = 1;
        if($htmlDebug) $this->emailMessage->smtp_html_debug = 1;
		if(!$this->connect()) {
			$this->logError('Error in '.__CLASS__.'::'.__FUNCTION__.' : cannot connect to smtp-server!');
			return false;
		}
		$this->emailMessage->SetHeader("Date", gmdate("D, j M Y H:i:s \G\M\T"));
		$ret = $this->emailMessage->Send();
		if($ret=='') {
			$maildata = 'success';
			return true;
		}
		$this->logError('Error in '.__CLASS__.'::'.__FUNCTION__.' : ' . $ret);
		$maildata = (string)$ret;
		return false;
	}




	public function setEmailHeader($type, $address, $name='') {
		$address = str_replace(array('<','>'), '', $address);
		if(!$this->isValidEmailadress($address)) {
			$this->logError('Error in '.__CLASS__.'::'.__FUNCTION__.' : No valid E-mailadress: '.$address);
			return false;
		}
		$type = strtolower($type);
		$valid_types = array('to'=>'To','from'=>'From','cc'=>'CC','bcc'=>'BCC','reply-to'=>'Reply-To','reply'=>'Reply-To','errors-to'=>'Errors-To','errors'=>'Errors-To','error'=>'Errors-To');
		if(!in_array($type,array_keys($valid_types))) {
			$this->logError('Error in '.__CLASS__.'::'.__FUNCTION__.' : No valid Headertype: '.$type);
			return false;
		}
		if(strpos($type,'reply')!==false) {
			$this->emailMessage->SetHeader('Return-Path',$address);
		}
		$ret = $this->emailMessage->SetEncodedEmailHeader($valid_types[$type], $address, $name);
		if($ret!='') {
			$this->logError('Error in '.__CLASS__.'::'.__FUNCTION__.' : ' . $ret);
			return false;
		}
		return true;
	}


	public function setHeader($headername,$content) {
		$ret = $this->emailMessage->SetEncodedHeader($headername,$content);
		if($ret!='') {
			$this->logError('Error in '.__CLASS__.'::'.__FUNCTION__.' : ' . $ret);
			return false;
		}
		return true;
	}


	public function isValidEmailadress($email) {
		$email = strtolower(trim($email));
		$clean = wire('sanitizer')->email($email);
		if($email != $clean) throw new WireException("Invalid email address");
		return true;
	}


	private function bundleEmailAndName($email, $name) {
		$email = strtolower(trim($email));
		$clean = wire('sanitizer')->email($email);
		if(!strlen($name)) return $email;
		$name = wire('sanitizer')->emailHeader($name);
		if(strpos($name, ',') !== false) {
			// name contains a comma, so quote the value
			$name = str_replace('"', '', $name); // remove existing quotes
			$name = '"' . $name . '"'; // surround w/quotes
		}
		return "$name <$email>";
	}


	public function setBulkMail($bulk) {
		return $this->emailMessage->setBulkMail($bulk);
	}


} // END class hnsmtp

