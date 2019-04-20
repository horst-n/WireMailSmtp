<?php
/*
 * smtp_message.php
 *
 * @(#) $Header: /opt2/ena/metal/mimemessage/smtp_message.php,v 1.36 2011/03/09 07:48:52 mlemos Exp $
 *
 *
 */
/**
*   @horst, 19.04.2019:
*       added support for: smtp_tls_crypto_method
*
*
*/
/*
{metadocument}<?xml version="1.0" encoding="ISO-8859-1"?>
<class>

	<package>net.manuellemos.mimemessage</package>

	<name>smtp_message_class</name>
	<version>@(#) $Id: smtp_message.php,v 1.36 2011/03/09 07:48:52 mlemos Exp $</version>
	<copyright>Copyright © (C) Manuel Lemos 1999-2004</copyright>
	<title>MIME E-mail message composing and sending via SMTP</title>
	<author>Manuel Lemos</author>
	<authoraddress>mlemos-at-acm.org</authoraddress>

	<documentation>
		<idiom>en</idiom>
		<purpose>Implement an alternative message delivery method via SMTP
			protocol, overriding the method of using the PHP <tt>mail()</tt>
			function implemented by the base class.</purpose>
		<usage>This class should be used exactly the same way as the base
			class for composing and sending messages. Just create a new object of
			this class as follows and set only the necessary variables to
			configure details of the SMTP delivery.<paragraphbreak />
			<tt>require('email_message.php');<br />
			require('smtp.php');<br />
			require('smtp_message.php');<br />
			<br />
			$message_object = new smtp_message_class;<br /></tt><paragraphbreak />
			<b>- Requirements</b><paragraphbreak />
			You need the <link>
				<data>SMTP E-mail sending class</data>
				<url>http://freshmeat.net/projects/smtpclass/</url>
			</link> to perform the actual message delivery via the SMTP
			protocol.<paragraphbreak />
			<b>- SMTP connection</b><paragraphbreak />
			Before sending a message by relaying it to a given SMTP server you
			need set the <variablelink>smtp_host</variablelink> variable to that
			server address. The <variablelink>localhost</variablelink> variable
			needs to be set to the sending computer address.<paragraphbreak />
			You may also adjust the time the class will wait for establishing
			a connection by changing the <variablelink>timeout</variablelink>
			variable.<paragraphbreak />
			<b>- Secure SMTP connections with SSL</b><paragraphbreak />
			Some SMTP servers, like for instance Gmail, require secure
			connections via SSL. In that case it is necessary to set the
			<variablelink>smtp_ssl</variablelink> variable to
			<booleanvalue>1</booleanvalue>. In the case of Gmail, it is also
			necessary to set the connection port changing the
			<variablelink>smtp_port</variablelink> variable to
			<integervalue>465</integervalue>.<paragraphbreak />
			SSL support requires at least PHP 4.3.0 with OpenSSL extension
			enabled.<paragraphbreak />
			<b>- Secure SMTP connections starting TLS after connections is established</b><paragraphbreak />
			Some SMTP servers, like for instance Hotmail, require starting the
			TLS protocol after the connection is already established to exchange
			data securely. In that case it is necessary to set the
			<variablelink>smtp_start_tls</variablelink> variable to
			<booleanvalue>1</booleanvalue>.<paragraphbreak />
			Starting TLS protocol on an already established connection requires
			at least PHP 5.1.0 with OpenSSL extension enabled.<paragraphbreak />
			<b>- Authentication</b><paragraphbreak />
			Most servers only allow relaying messages sent by authorized
			users. If the SMTP server that you want to use requires
			authentication, you need to set the variables
			<variablelink>smtp_user</variablelink>,
			<variablelink>smtp_realm</variablelink> and
			<variablelink>smtp_password</variablelink>.<paragraphbreak />
			The way these values need to be set depends on the server. Usually
			the realm value is empty and only the user and password need to be
			set. If the server requires authentication via <tt>NTLM</tt>
			mechanism (Windows or Samba), you need to set the
			<variablelink>smtp_realm</variablelink> to the Windows domain name
			and also set the variable
			<variablelink>smtp_workstation</variablelink> to the user workstation
			name.<paragraphbreak />
			Some servers require that the authentication be done on a separate
			server using the POP3 protocol before connecting to the SMTP server.
			In this case you need to specify the address of the POP3 server
			setting the <variablelink>smtp_pop3_auth_host</variablelink>
			variable.<paragraphbreak />
			<b>- Sending urgent messages with direct delivery</b><paragraphbreak />
			If you need to send urgent messages or obtain immediate confirmation
			that a message is accepted by the recipient SMTP server, you can use
			the direct delivery mode setting the
			<variablelink>direct_delivery</variablelink> variable to
			<tt><booleanvalue>1</booleanvalue></tt>. This mode can be used to
			send a message to only one recipient.<paragraphbreak />
			To use this mode, it is necessary to have a way to determine the
			recipient domain SMTP server address. The class uses the PHP
			<tt>getmxrr()</tt> function, but on some systems like for instance
			under Windows, this function does not work. In this case you may
			specify an equivalent alternative by setting the
			<variablelink>smtp_getmxrr</variablelink> variable. See the SMTP
			class page for available alternatives.<paragraphbreak />
			<b>- Troubleshooting and debugging</b><paragraphbreak />
			If for some reason the delivery via SMTP is not working and the error
			messages are not self-explanatory, you may set the
			<variablelink>smtp_debug</variablelink> to
			<tt><booleanvalue>1</booleanvalue></tt> to make the class output the
			SMTP protocol dialog with the server. If you want to display this
			dialog properly formatted in an HTML page, also set the
			<variablelink>smtp_debug</variablelink> to
			<tt><booleanvalue>1</booleanvalue></tt>.<paragraphbreak />
			<b>- Optimizing the delivery of messages to many recipients</b><paragraphbreak />
			When sending messages to many recipients, this class can hinted to
			optimize its behavior by using the
			<functionlink>SetBulkMail</functionlink> function. After calling this
			function passing <booleanvalue>1</booleanvalue> to the <argumentlink>
				<function>SetBulkMail</function>
				<argument>on</argument>
			</argumentlink> argument, when the message is sent this class opens
			a TCP connection to the SMTP server but will not close it. This
			avoids the overhead of opening and closing connections.<paragraphbreak />
			When the delivery of the messages to all recipients is done, the
			connection may be closed implicitly by calling the
			<functionlink>SetBulkMail</functionlink> function again passing
			<booleanvalue>0</booleanvalue> to the <argumentlink>
				<function>SetBulkMail</function>
				<argument>on</argument>
			</argumentlink> argument.</usage>
	</documentation>

{/metadocument}
*/

class smtp_message_class extends email_message_class
{
	/* Private variables */

	var $smtp;
	var $line_break="\r\n";
	var $delivery = 0;

	/* Public variables */

	/* Allow Self Signed Certificate */
	var $smtp_certificate = 0;       // @flydev: https://processwire.com/talk/topic/5704-wiremailsmtp/page-5#entry113290

    /* @horst: Allow to define the crypto method for TLS connections */
    var $smtp_tls_crypto_method = '';

    /* @horst: Allow to define the crypto method for SSL connections */
    var $smtp_ssl_crypto_method = '';

/*
{metadocument}
	<variable>
		<name>localhost</name>
		<value></value>
		<documentation>
			<purpose>Specify the domain name of the computer sending the
				message.</purpose>
			<usage>This value is used to identify the sending machine to the
				SMTP server. When using the direct delivery mode, if this variable
				is set to a non-empty string it used to generate the
				<tt>Recieved</tt> header to show that the message passed by the
				specified host address. To prevent confusing directly delivered
				messages with spam, it is strongly recommended that you set this
				variable to you server host name.</usage>
		</documentation>
	</variable>
{/metadocument}
*/
	var $localhost="";

/*
{metadocument}
	<variable>
		<name>smtp_host</name>
		<type>STRING</type>
		<value></value>
		<documentation>
			<purpose>Specify the address of the SMTP server.</purpose>
			<usage>Set to the address of the SMTP server that will relay the
				messages. This variable is not used in direct delivery mode.</usage>
		</documentation>
	</variable>
{/metadocument}
*/
	var $smtp_host="localhost";

/*
{metadocument}
	<variable>
		<name>smtp_port</name>
		<type>INTEGER</type>
		<value>25</value>
		<documentation>
			<purpose>Specify the TCP/IP port of SMTP server to connect.</purpose>
			<usage>Most servers work on port 25 . Certain e-mail services use
				alternative ports to avoid firewall blocking. Gmail uses port
				<integervalue>465</integervalue>.</usage>
		</documentation>
	</variable>
{/metadocument}
*/
	var $smtp_port=25;

/*
{metadocument}
	<variable>
		<name>smtp_ssl</name>
		<type>BOOLEAN</type>
		<value>0</value>
		<documentation>
			<purpose>Specify whether it should use secure connections with SSL
				to connect to the SMTP server.</purpose>
			<usage>Certain e-mail services like Gmail require SSL connections.</usage>
		</documentation>
	</variable>
{/metadocument}
*/
	var $smtp_ssl=0;

/*
{metadocument}
	<variable>
		<name>smtp_start_tls</name>
		<type>BOOLEAN</type>
		<value>0</value>
		<documentation>
			<purpose>Specify whether it should use secure connections starting
				TLS protocol after connecting to the SMTP server.</purpose>
			<usage>Certain e-mail services like Hotmail require starting TLS
				protocol after the connection to the SMTP server is already
				established.</usage>
		</documentation>
	</variable>
{/metadocument}
*/
	var $smtp_start_tls=0;

/*
{metadocument}
	<variable>
		<name>smtp_http_proxy_host_name</name>
		<type>STRING</type>
		<value></value>
		<documentation>
			<purpose>Specify name of the host when the connection should be
				routed via an HTTP proxy.</purpose>
			<usage>Leave empty if no proxy should be used.</usage>
		</documentation>
	</variable>
{/metadocument}
*/
	var $smtp_http_proxy_host_name='';

/*
{metadocument}
	<variable>
		<name>smtp_http_proxy_host_port</name>
		<type>INTEGER</type>
		<value>3128</value>
		<documentation>
			<purpose>Specify proxy port when the connection should be routed via
				an HTTP proxy.</purpose>
			<usage>Change this variable if you need to use a proxy with a
				specific port.</usage>
		</documentation>
	</variable>
{/metadocument}
*/
	var $smtp_http_proxy_host_port=3128;

/*
{metadocument}
	<variable>
		<name>smtp_socks_host_name</name>
		<type>STRING</type>
		<value></value>
		<documentation>
			<purpose>Specify name of the host when the connection should be
				routed via a SOCKS protocol proxy.</purpose>
			<usage>Leave empty if no proxy should be used.</usage>
		</documentation>
	</variable>
{/metadocument}
*/
	var $smtp_socks_host_name = '';

/*
{metadocument}
	<variable>
		<name>smtp_socks_host_port</name>
		<type>INTEGER</type>
		<value>1080</value>
		<documentation>
			<purpose>Specify proxy port when the connection should be routed via
				a SOCKS protocol proxy.</purpose>
			<usage>Change this variable if you need to use a proxy with a
				specific port.</usage>
		</documentation>
	</variable>
{/metadocument}
*/
	var $smtp_socks_host_port = 1080;

/*
{metadocument}
	<variable>
		<name>smtp_socks_version</name>
		<type>STRING</type>
		<value></value>
		<documentation>
			<purpose>Specify protocol version when the connection should be
				routed via a SOCKS protocol proxy.</purpose>
			<usage>Change this variable if you need to use a proxy with a
				specific SOCKS protocol version.</usage>
		</documentation>
	</variable>
{/metadocument}
*/
	var $smtp_socks_version = '5';

/*
{metadocument}
	<variable>
		<name>smtp_direct_delivery</name>
		<type>BOOLEAN</type>
		<value>0</value>
		<documentation>
			<purpose>Boolean flag that indicates whether the message should be
				sent in direct delivery mode.</purpose>
			<usage>Set this to <tt><booleanvalue>1</booleanvalue></tt> if you
				want to send urgent messages directly to the recipient domain SMTP
				server.</usage>
		</documentation>
	</variable>
{/metadocument}
*/
	var $smtp_direct_delivery=0;

/*
{metadocument}
	<variable>
		<name>smtp_getmxrr</name>
		<type>STRING</type>
		<value>getmxrr</value>
		<documentation>
			<purpose>Specify the name of the function that is called to determine
				the SMTP server address of a given domain.</purpose>
			<usage>Change this to a working replacement of the PHP
				<tt>getmxrr()</tt> function if this is not working in your system
					and you want to send messages in direct delivery mode.</usage>
		</documentation>
	</variable>
{/metadocument}
*/
	var $smtp_getmxrr="getmxrr";

/*
{metadocument}
	<variable>
		<name>smtp_exclude_address</name>
		<type>STRING</type>
		<value></value>
		<documentation>
			<purpose>Specify an address that should be considered invalid
				when resolving host name addresses.</purpose>
			<usage>In some networks any domain name that does not exist is
				resolved as a sub-domain of the default local domain. If the DNS is
				configured in such way that it always resolves any sub-domain of
				the default local domain to a given address, it is hard to
				determine whether a given domain does not exist.<paragraphbreak />
				If your network is configured this way, you may set this variable
				to the address that all sub-domains of the default local domain
				resolves, so the class can assume that such address is invalid.</usage>
		</documentation>
	</variable>
{/metadocument}
*/
	var $smtp_exclude_address="";

/*
{metadocument}
	<variable>
		<name>smtp_user</name>
		<type>STRING</type>
		<value></value>
		<documentation>
			<purpose>Specify the user name for authentication.</purpose>
			<usage>Set this variable if you need to authenticate before sending
				a message.</usage>
		</documentation>
	</variable>
{/metadocument}
*/
	var $smtp_user="";

/*
{metadocument}
	<variable>
		<name>smtp_realm</name>
		<type>STRING</type>
		<value></value>
		<documentation>
			<purpose>Specify the user authentication realm.</purpose>
			<usage>Set this variable if you need to authenticate before sending
				a message.</usage>
		</documentation>
	</variable>
{/metadocument}
*/
	var $smtp_realm="";

/*
{metadocument}
	<variable>
		<name>smtp_workstation</name>
		<type>STRING</type>
		<value></value>
		<documentation>
			<purpose>Specify the user authentication workstation needed when
				using the <tt>NTLM</tt> authentication (Windows or Samba).</purpose>
			<usage>Set this variable if you need to authenticate before sending
				a message.</usage>
		</documentation>
	</variable>
{/metadocument}
*/
	var $smtp_workstation="";

/*
{metadocument}
	<variable>
		<name>smtp_authentication_mechanism</name>
		<type>STRING</type>
		<value></value>
		<documentation>
			<purpose>Specify the user authentication mechanism that should be
				used when authenticating with the SMTP server.</purpose>
			<usage>Set this variable if you need to force the SMTP connection to
				authenticate with a specific authentication mechanism. Leave this
				variable with an empty string if you want the authentication
				mechanism be determined automatically from the list of mechanisms
				supported by the server.</usage>
		</documentation>
	</variable>
{/metadocument}
*/
	var $smtp_authentication_mechanism="";

/*
{metadocument}
	<variable>
		<name>smtp_password</name>
		<type>STRING</type>
		<value></value>
		<documentation>
			<purpose>Specify the user authentication password.</purpose>
			<usage>Set this variable if you need to authenticate before sending
				a message.</usage>
		</documentation>
	</variable>
{/metadocument}
*/
	var $smtp_password="";

/*
{metadocument}
	<variable>
		<name>smtp_pop3_auth_host</name>
		<type>STRING</type>
		<value></value>
		<documentation>
			<purpose>Specify the server address for POP3 based authentication.</purpose>
			<usage>Set this variable to the address of the POP3 server if the
				SMTP server requires POP3 based authentication.</usage>
		</documentation>
	</variable>
{/metadocument}
*/
	var $smtp_pop3_auth_host="";

/*
{metadocument}
	<variable>
		<name>smtp_debug</name>
		<type>BOOLEAN</type>
		<value>0</value>
		<documentation>
			<purpose>Specify whether it is necessary to output SMTP connection
				debug information.</purpose>
			<usage>Set this variable to
				<tt><booleanvalue>1</booleanvalue></tt> if you need to see
				the progress of the SMTP connection and protocol dialog when you
				need to understand the reason for delivery problems.</usage>
		</documentation>
	</variable>
{/metadocument}
*/
	var $smtp_debug=0;

/*
{metadocument}
	<variable>
		<name>smtp_html_debug</name>
		<type>BOOLEAN</type>
		<value>0</value>
		<documentation>
			<purpose>Specify whether the debug information should be outputted in
				HTML format.</purpose>
			<usage>Set this variable to
				<tt><booleanvalue>1</booleanvalue></tt> if you need to see
				the debug output in a Web page.</usage>
		</documentation>
	</variable>
{/metadocument}
*/
	var $smtp_html_debug=0;

/*
{metadocument}
	<variable>
		<name>esmtp</name>
		<type>BOOLEAN</type>
		<value>1</value>
		<documentation>
			<purpose>Specify whether the class should try to use Enhanced SMTP
				protocol features.</purpose>
			<usage>It is recommended to leave this variable set to
				<tt><booleanvalue>1</booleanvalue></tt> so the class can take
				advantage of Enhanced SMTP protocol features.</usage>
		</documentation>
	</variable>
{/metadocument}
*/
	var $esmtp=1;

/*
{metadocument}
	<variable>
		<name>timeout</name>
		<type>INTEGER</type>
		<value>25</value>
		<documentation>
			<purpose>Specify the connection timeout period in seconds.</purpose>
			<usage>Change this value if for some reason the timeout period seems
				insufficient or otherwise it seems too long.</usage>
		</documentation>
	</variable>
{/metadocument}
*/
	var $timeout=25;

/*
{metadocument}
	<variable>
		<name>invalid_recipients</name>
		<type>ARRAY</type>
		<value></value>
		<documentation>
			<purpose>Return the list of recipient addresses that were not
				accepted by the SMTP server.</purpose>
			<usage>Check this variable after attempting to send a message to
				figure whether there were any recipients that were rejected by the
				SMTP server.</usage>
		</documentation>
	</variable>
{/metadocument}
*/
	var $invalid_recipients=array();

/*
{metadocument}
	<variable>
		<name>mailer_delivery</name>
		<value>smtp $Revision: 1.36 $</value>
		<documentation>
			<purpose>Specify the text that is used to identify the mail
				delivery class or sub-class. This text is appended to the
				<tt>X-Mailer</tt> header text defined by the
				mailer variable.</purpose>
			<usage>Do not change this variable.</usage>
		</documentation>
	</variable>
{/metadocument}
*/
	var $mailer_delivery='smtp $Revision: 1.36 $';

/*
{metadocument}
	<variable>
		<name>maximum_bulk_deliveries</name>
		<type>INTEGER</type>
		<value>100</value>
		<documentation>
			<purpose>Specify the number of consecutive bulk mail deliveries
				without disconnecting.</purpose>
			<usage>Lower this value if you have enabled the bulk mail mode but
				the SMTP server does not accept sending more than a number of
				messages within the same SMTP connection.<paragraphbreak />
				Set this value to <integervalue>0</integervalue> to never
				disconnect during bulk mail mode unless an error occurs.</usage>
		</documentation>
	</variable>
{/metadocument}
*/
	var $maximum_bulk_deliveries=100;

	Function SetRecipients(&$recipients,&$valid_recipients)
	{
		for($valid_recipients=$recipient=0,Reset($recipients);$recipient<count($recipients);Next($recipients),$recipient++)
		{
			$address=Key($recipients);
			if($this->smtp->SetRecipient($address))
				$valid_recipients++;
			else
				$this->invalid_recipients[$address]=$this->smtp->error;
		}
		return(1);
	}

	Function ResetConnection($error)
	{
		if(IsSet($this->smtp))
		{
			if(!$this->smtp->Disconnect()
			&& strlen($error) == 0)
				$error = $this->smtp->error;
			UnSet($this->smtp);
		}
		if(strlen($error))
			$this->OutputError($error);
		return($error);
	}

	Function StartSendingMessage()
	{
		if(function_exists("class_exists")
		&& !class_exists("smtp_class"))
			return("the smtp_class class was not included");
		if(IsSet($this->smtp))
			return("");
		$this->smtp=new smtp_class;
		$this->smtp->localhost=$this->localhost;
		$this->smtp->host_name=$this->smtp_host;
		$this->smtp->host_port=$this->smtp_port;
		$this->smtp->ssl=$this->smtp_ssl;
        $this->smtp->smtp_ssl_crypto_method=$this->smtp_ssl_crypto_method;
		$this->smtp->start_tls=$this->smtp_start_tls;
        $this->smtp->smtp_tls_crypto_method=$this->smtp_tls_crypto_method;
        $this->smtp->http_proxy_host_name=$this->smtp_http_proxy_host_name;
		$this->smtp->http_proxy_host_port=$this->smtp_http_proxy_host_port;
		$this->smtp->socks_host_name=$this->smtp_socks_host_name;
		$this->smtp->socks_host_port=$this->smtp_socks_host_port;
		$this->smtp->socks_version=$this->smtp_socks_version;
		$this->smtp->timeout=$this->timeout;
		$this->smtp->debug=$this->smtp_debug;
		$this->smtp->html_debug=$this->smtp_html_debug;
		$this->smtp->direct_delivery=$this->smtp_direct_delivery;
		$this->smtp->getmxrr=$this->smtp_getmxrr;
		$this->smtp->exclude_address=$this->smtp_exclude_address;
		$this->smtp->pop3_auth_host=$this->smtp_pop3_auth_host;
		$this->smtp->user=$this->smtp_user;
		$this->smtp->realm=$this->smtp_realm;
		$this->smtp->workstation=$this->smtp_workstation;
		$this->smtp->authentication_mechanism=$this->smtp_authentication_mechanism;
		$this->smtp->password=$this->smtp_password;
		$this->smtp->esmtp=$this->esmtp;
		$this->smtp->smtp_certificate = $this->smtp_certificate;  // @flydev: https://processwire.com/talk/topic/5704-wiremailsmtp/page-5#entry113290
		if($this->smtp->Connect())
		{
			$this->delivery = 0;
			return("");
		}
		return($this->ResetConnection($this->smtp->error));
	}

	Function SendMessageHeaders($headers)
	{
		$header_data="";
		$date=date("D, d M Y H:i:s T");
		if($this->smtp_direct_delivery
		&& strlen($this->localhost))
		{
			$local_ip=gethostbyname($this->localhost);
			$header_data.=$this->FormatHeader("Received","FROM ".$this->localhost." ([".$local_ip."]) BY ".$this->localhost." ([".$local_ip."]) WITH SMTP; ".$date)."\r\n";
		}
		for($message_id_set=$date_set=0,$header=0,$return_path=$from=$to=$recipients=array(),Reset($headers);$header<count($headers);$header++,Next($headers))
		{
			$header_name=Key($headers);
			switch(strtolower($header_name))
			{
				case "return-path":
					$return_path[$headers[$header_name]]=1;
					break;
				case "from":
					$error=$this->GetRFC822Addresses($headers[$header_name],$from);
					break;
				case "to":
					$error=$this->GetRFC822Addresses($headers[$header_name],$to);
					break;
				case "cc":
				case "bcc":
					$this->GetRFC822Addresses($headers[$header_name],$recipients);
					break;
				case "date":
					$date_set=1;
					break;
				case "message-id":
					$message_id_set=1;
					break;
			}
			if(strcmp($error,""))
				return($this->ResetConnection($error));
			if(strtolower($header_name)=="bcc")
				continue;
			$header_data.=$this->FormatHeader($header_name,$headers[$header_name])."\r\n";
		}
		if(count($from)==0)
			return($this->ResetConnection("it was not specified a valid From header"));
		Reset($return_path);
		Reset($from);
		$this->invalid_recipients=array();
		if(!$this->smtp->MailFrom(count($return_path) ? Key($return_path) : Key($from)))
			return($this->ResetConnection($this->smtp->error));
		$r = 0;
		if(count($to))
		{
			if(!$this->SetRecipients($to,$valid_recipients))
				return($this->ResetConnection($this->smtp->error));
			$r += $valid_recipients;
		}
		if(!$date_set)
			$header_data.="Date: ".$date."\r\n";
		if(!$message_id_set
		&& $this->auto_message_id)
		{
			$sender=(count($return_path) ? Key($return_path) : Key($from));
			$header_data.=$this->GenerateMessageID($sender)."\r\n";
		}
		if(count($recipients))
		{
			if(!$this->SetRecipients($recipients,$valid_recipients))
				return($this->ResetConnection($this->smtp->error));
			$r += $valid_recipients;
		}
		if($r==0)
			return($this->ResetConnection("it were not specified any valid recipients"));
		if(!$this->smtp->StartData()
		|| !$this->smtp->SendData($header_data."\r\n"))
			return($this->ResetConnection($this->smtp->error));
		return("");
	}

	Function SendMessageBody($data)
	{
		return($this->smtp->SendData($this->smtp->PrepareData($data)) ? "" : $this->ResetConnection($this->smtp->error));
	}

	Function EndSendingMessage()
	{
		return($this->smtp->EndSendingData() ? "" : $this->ResetConnection($this->smtp->error));
	}

	Function StopSendingMessage()
	{
		++$this->delivery;
		if($this->bulk_mail
		&& !$this->smtp_direct_delivery
		&& ($this->maximum_bulk_deliveries == 0
		|| $this->delivery < $this->maximum_bulk_deliveries))
			return("");
		return($this->ResetConnection(''));
	}

	Function ChangeBulkMail($on)
	{
		if($on
		|| !IsSet($this->smtp))
			return(1);
		return($this->smtp->Disconnect() ? "" : $this->ResetConnection($this->smtp->error));
	}
};

/*

{metadocument}
</class>
{/metadocument}

*/

?>
