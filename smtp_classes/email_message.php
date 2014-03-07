<?php
/*
 * email_message.php
 *
 * @(#) $Id: email_message.php,v 1.99 2013/09/08 22:44:46 mlemos Exp $
 *
 *
 */

/*
{metadocument}<?xml version="1.0" encoding="ISO-8859-1"?>
<class>

	<package>net.manuellemos.mimemessage</package>

	<version>@(#) $Id: email_message.php,v 1.99 2013/09/08 22:44:46 mlemos Exp $</version>
	<copyright>Copyright © (C) Manuel Lemos 1999-2004</copyright>
	<title>MIME E-mail message composing and sending</title>
	<author>Manuel Lemos</author>
	<authoraddress>mlemos-at-acm.org</authoraddress>

	<documentation>
		<idiom>en</idiom>
		<purpose>Compose and send e-mail messages according to the MIME
			standards.</purpose>
		<translation>If you are interested in translating the documentation of
			this class to your own idiom, please <link>
				<data>contact the author</data>
				<url>mailto:<getclassproperty>authoraddress</getclassproperty></url>
			</link>.</translation>
		<support>Technical support for using this class may be obtained in the
			<tt>mimemessage-dev</tt> mailing list. Just go to the mailing list
			page to browse the list archives, learn how to to join and post
			support request messages:<paragraphbreak />
			<link>
				<data>http://groups-beta.google.com/group/mimemessage-dev</data>
				<url>http://groups-beta.google.com/group/mimemessage-dev</url>
			</link></support>
		<usage>To used this class just create a new object as follows, set any
			variables to configure its behavior and call the functions you need
			to compose and send your messages.<paragraphbreak />
			<tt>require('email_message.php');<br />
			<br />
			$message_object = new email_message_class;<br /></tt><paragraphbreak />
			<b>- Set the sender and recipients</b><paragraphbreak />
			You can set the message sender and one or more recipient addresses
			using the <functionlink>SetHeader</functionlink> or the
			<functionlink>SetEncodedEmailHeader</functionlink> functions
			specifying the addresses for the <tt>From</tt>, <tt>To</tt>,
			<tt>Cc</tt> and <tt>Bcc</tt> headers.<paragraphbreak />

			<b>- Formatting text messages</b><paragraphbreak />
			You can use the <functionlink>WrapText</functionlink> to assure that
			a text message does not have more than 75 columns by breaking the
			longer lines between words.<paragraphbreak />
			<paragraphbreak />
			If you are composing a reply to another text message, you can use the
			<functionlink>QuoteText</functionlink> function to conveniently mark
			the text quoted from the original message.<paragraphbreak />

			<b>- Add a plain text message body</b><paragraphbreak />
			If the text of the message that you want to send only contains ASCII
			characters (7 bits), use the
			<functionlink>AddPlainTextPart</functionlink> function to add the
			text to the message.<paragraphbreak />

			<b>- Add a text message with non-ASCII characters</b><paragraphbreak />
			If your message text may contains non-ASCII characters (8 bits or
			more), use the
			<functionlink>AddQuotedPrintableTextPart</functionlink> function
			to add the text to the message.<paragraphbreak />
			<paragraphbreak />
			If the text uses a character set other than
			<i>ISO-8859-1</i> (ISO Latin 1), set the
			<variablelink>default_charset</variablelink> variable to change the
			default character set.<paragraphbreak />

			<b>- Setting the error message bounce address</b><paragraphbreak />
			This class provides a means to specify the address where error
			messages should be bounced in case it is not possible to deliver a
			message. That can be done by setting the header <tt>Return-Path</tt>
			with the <functionlink>SetHeader</functionlink>
			function.<paragraphbreak />

			<b>- Request message receipt notification</b><paragraphbreak />
			If you would like to be receive an notification when a message that
			is sent is received, just use the
			<functionlink>SetHeader</functionlink> function with the
			<tt>Disposition-Notification-To</tt> header to specify the address to
			where you want to receive the notification message.<paragraphbreak />
			Keep in mind that this header just expresses that you want to get a
			receipt notification, but it may be denied or ignored by the
			recipient, which does not mean the message was not
			received.<paragraphbreak />

			<b>- Avoding temporary delivery failure warning messages</b><paragraphbreak />
			Sometimes it is not possible to deliver a message immediately due
			to a networking failure or some other problem. In that case, the mail
			transfer system usually leaves the message in a queue and keeps
			retrying to deliver the message until it succeeds or it has reached
			the limit number of days before it gives up. When it gives up the
			the message is bounced to the return-path address.<paragraphbreak />
			However some systems send a warning message to the original sender
			when it is not delivered after the first few hours. This may be an
			useful notification when the message is sent by a human but it maybe
			inconvinient when you are sending messages to many users like for
			instance newsletters or messages to subscribers of mailing lists.<paragraphbreak />
			If you want to hint the mail transfer system to not send temporary
			delivery failure warning messages, just use the
			<functionlink>SetHeader</functionlink> function to set the
			<tt>Precedence</tt> header to <tt>bulk</tt>.<paragraphbreak />
			Setting this header this way is a convention used by mailing list
			manager programs precisely for this purpose. It may also hint some
			mail receiving systems to not send auto-response messages, for
			instance when the recipient user is away on vaction. However, not all
			systems are aware of this convention and still send auto-response
			messages when you set this header.<paragraphbreak />

			<b>- Send the message</b><paragraphbreak />
			Once you have set the message sender, the recipients and added the
			message text, use the <functionlink>Send</functionlink> function
			to send the message. This class uses the PHP function <tt>mail()</tt>
			to send messages.<paragraphbreak />
			<paragraphbreak />
			If for some reason you need to use a different message delivery
			method, you may use one of the existing sub-classes that are
			specialized in delivering messages by connecting to an SMTP server or
			using directly the programs sendmail and qmail.<paragraphbreak />

			<b>- Add an HTML message body</b><paragraphbreak />
			If you want to send an HTML message you can use the
			<functionlink>AddHTMLPart</functionlink> function if it contains
			only ASCII characters. If it contains non-ASCII characters, you
			should the <functionlink>AddQuotedPrintableHTMLPart</functionlink>
			function instead.<paragraphbreak />

			<b>- Add alternative text body for HTML messages</b><paragraphbreak />
			Not every e-mail program can display HTML messages. Therefore, when
			you send an HTML message, you should also include an alternative text
			part to be displayed by programs that do not support HTML
			messages.<paragraphbreak />
			<paragraphbreak />
			This is achieved by composing <tt>multipart/alternative</tt>
			messages. This type of message is composed by creating the HTML
			message part with the <functionlink>CreateHTMLPart</functionlink> or
			the <functionlink>CreateQuotedPrintableHTMLPart</functionlink>
			functions, then create the alternative text part with the
			<functionlink>CreatePlainTextPart</functionlink> or the
			<functionlink>CreateQuotedPrintableTextPart</functionlink>
			functions, and finally use the
			<functionlink>AddAlternativeMultipart</functionlink> function to add
			an assembly of both message parts.<paragraphbreak />
			Note that the text part should be the first to be specified in the
			array of parts passed to the
			<functionlink>AddAlternativeMultipart</functionlink> function, or
			else it will not appear correctly.<paragraphbreak />
			Despite this procedure adds a little complexity to the process of
			sending HTML messages, it is the same procedure that is followed by
			e-mail programs that are used by most people to send HTML
			messages.<paragraphbreak />
			Therefore, you are strongly recommended to follow the same procedure
			because some of the modern spam filter programs discard HTML messages
			without an alternative plain text part, as it constitutes a pattern
			that identifies messages composed by some of the spam sending
			programs.<paragraphbreak />

			<b><link>
				<data>- Embed images in HTML messages</data>
				<anchor>embed-image</anchor>
			</link></b><paragraphbreak />
			One way to show an image in an HTML message is to use
			<tt>&lt;img&gt;</tt> tag with <tt>src</tt> attribute set to the
			remote site URL of the image that is meant to be displayed.
			However, since the message recipient user may not be online when
			they will check their e-mail, an image referenced this way may not
			appear.<paragraphbreak />
			Alternatively, an image file can be embedded in an HTML message using
			<tt>multipart/related</tt> message parts. This type of message part
			is composed by creating the image file part with the
			<functionlink>CreateFilePart</functionlink> function.<paragraphbreak />
			Then use the <functionlink>GetPartContentID</functionlink> function
			the image part identifier text. Prepend the string
			<stringvalue>cid:</stringvalue> to this identifier to form a special
			URL that should be used in the HTML part to reference the image part
			like this:<paragraphbreak />
			<tt>$image_tag = <stringvalue>&lt;img src="cid:</stringvalue> .
			$message_object->GetPartContentID($image_part) .
			<stringvalue>"></stringvalue> ;</tt><paragraphbreak />
			When you have composed the whole HTML document, create the HTML
			message part with the <functionlink>CreateHTMLPart</functionlink> or
			the <functionlink>CreateQuotedPrintableHTMLPart</functionlink>
			functions, and finally use the
			<functionlink>CreateRelatedMultipart</functionlink> function to
			create a message part that can be added to the message with the
			function <functionlink>AddAlternativeMultipart</functionlink> like
			HTML messages with alternative text parts described
			before.<paragraphbreak />
			Note that the HTML part must be the first listed in the parts array
			argument that is passed to the function
			<functionlink>CreateRelatedMultipart</functionlink>, or else the
			message may not appear correctly.<paragraphbreak />
			Note also that when you are composing an HTML message with embedded
			images and an alternative text part, first you need to create the
			<tt>multipart/alternative</tt> part with the HTML and the text parts
			using the <functionlink>CreateAlternativeMultipart</functionlink>
			function, and then you add the <tt>multipart/related</tt> part to
			the message with the
			<functionlink>AddRelatedMultipart</functionlink> function,
			passing an array of parts that lists first the
			<tt>multipart/alternative</tt> part and then the image part created
			before.<paragraphbreak />

			<b>- Attach files to messages</b><paragraphbreak />
			To send a message with attached files, it is necessary to compose a
			<tt>multipart/mixed</tt> message. This is a type of message made by a
			text or HTML part followed by one or more file
			parts.<paragraphbreak />
			If you add multiple parts to a message, this class implicitly turns
			it into a <tt>multipart/mixed</tt> message. Therefore you only need
			to use the function <functionlink>AddFilePart</functionlink> for each
			file that you want to attach and the class will automatically
			generate the message treating any parts added after the first as
			attachments.<paragraphbreak />

			<b>- Forward received messages</b><paragraphbreak />
			To forward an e-mail message received from somewhere, just use the
			function <functionlink>AddMessagePart</functionlink> passing the
			message complete with the original headers and body data. The message
			is forwarded as an attachment that most mail programs can
			display.<paragraphbreak />

			<b>- Sending messages to many recipients (mass or bulk mailing)</b><paragraphbreak />
			Sending messages to many recipients is an activity also known as
			mass or bulk mailing. There are several alternatives for mass
			mailing. One way consists on specifying all recipient addresses
			with the <tt>Bcc</tt> header, separating the addresses with commas
			(,), or using the
			<functionlink>SetMultipleEncodedEmailHeader</functionlink> function.
			This way you only need to send one message that is distributed to all
			recipients by your mail transfer system.<paragraphbreak />
			Unfortunately, many mail account providers like Hotmail, tend to
			consider messages sent this way as spam because the real recipients
			addresses are not visible in <tt>To</tt> of <tt>Cc</tt> headers.
			So, this method is no longer a good solution these
			days.<paragraphbreak />
			The alternative is to send a separate message to each recipient by
			iteratively setting the <tt>To</tt> header with each recipient
			address and calling the <functionlink>Send</functionlink> function.
			This way tends to take too much time and CPU as the number of
			recipients grow.<paragraphbreak />
			When sending messages to many recipients, call the
			<functionlink>SetBulkMail</functionlink> function to hint the class
			to optimize the way it works to make the delivery of the messages
			more efficient and eventually faster.<paragraphbreak />
			The actual optimizations that are performed depend on the delivery
			method that is used by this class or any of its subclasses
			specialized on the different delivery methods that are supported.
			Check the documentation of the subclass that you use to learn about
			the optimizations that are performed, if any.<paragraphbreak />
			If you intend to send messages with the same body to all recipients,
			the class can optimize the generation of the messages and reduce
			significantly the composition time if you set the
			<variablelink>cache_body</variablelink> variable to
			<tt><booleanvalue>1</booleanvalue></tt>.<paragraphbreak />
			If you really need to personalize the content of a message part with
			different text, HTML or file to each recipient, you should use the
			<functionlink>ReplacePart</functionlink> function to avoid as much
			as possible the overhead of composing a new message to each of the
			recipients of the mailing.<paragraphbreak />
			If you are sending personalized messages to multiple recipients but
			the messages include attached or embedded files that are the same
			for all recipients, you should also set the
			<stringvalue>Cached</stringvalue> option of the <argumentlink>
				<argument>file</argument>
				<function>CreateFilePart</function>
			</argumentlink> parameter of the
			<functionlink>CreateFilePart</functionlink> function.<paragraphbreak />
			Other than that, take a look at the documentation of the this class
			sub-classes that may be used in your PHP environment, as these may
			provide more efficient delivery solutions for mass mailing.<paragraphbreak />

			<b>- Error handling</b><paragraphbreak />
			Most of the functions of this class that may fail, return an error
			message string that describes the error that has occurred. If there
			was no error, the functions return an empty string.<paragraphbreak />
			Verifying the return value of all the functions to determine
			whether there was an error is a tedious task to implement for most
			developers. To avoid this problem, this class supports <i>cumulative
			error checking</i>.<paragraphbreak />
			Cumulative error checking means that when an error occurs, the class
			stores the error message in the <variablelink>error</variablelink>
			variable. Then, when another function that may fail is called, it
			does nothing and immediately returns the same error
			message.<paragraphbreak />
			This way, the developers only need to check the return value of the
			last function that is called, which is usually the
			<functionlink>Send</functionlink> function.
		</usage>
	</documentation>

{/metadocument}
*/

class email_message_class
{
	/* Private variables */

	var $headers=array("To"=>"","Subject"=>"");
	var $body=-1;
	var $body_parts=0;
	var $parts=array();
	var $total_parts=0;
	var $free_parts=array();
	var $total_free_parts=0;
	var $delivery=array("State"=>"");
	var $next_token="";
	var $php_version=0;
	var $mailings=array();
	var $last_mailing=0;
	var $header_length_limit=512;
	var $auto_message_id=1;
	var $mailing_path="";
	var $body_cache=array();
	var $line_break="\n";
	var $line_length=76;
	var $ruler="_";
	var $email_address_pattern="([-!#\$%&'*+./0-9=?A-Z^_`a-z{|}~])+@([-!#\$%&'*+/0-9=?A-Z^_`a-z{|}~]+\\.)+[a-zA-Z]{2,6}";
	var $bulk_mail=0;

	/* Public variables */

/*
{metadocument}
	<variable>
		<name>email_regular_expression</name>
		<type>STRING</type>
		<value>^([-!#$%&amp;'*+./0-9=?A-Z^_`a-z{|}~])+@([-!#$%&amp;'*+/0-9=?A-Z^_`a-z{|}~]+\.)+[a-zA-Z]{2,6}$</value>
		<documentation>
			<purpose>Specify the regular expression that is used by the
				<functionlink>ValidateEmailAddress</functionlink> function to
				verify whether a given e-mail address may be valid.</purpose>
			<usage>Do not change this variable unless you have reason to believe
				that it is rejecting existing e-mail addresses that are known to be
				valid.</usage>
		</documentation>
	</variable>
{/metadocument}
*/
	var $email_regular_expression="^([-!#\$%&'*+./0-9=?A-Z^_`a-z{|}~])+@([-!#\$%&'*+/0-9=?A-Z^_`a-z{|}~]+\\.)+[a-zA-Z]{2,6}\$";

/*
{metadocument}
	<variable>
		<name>mailer</name>
		<type>STRING</type>
		<value>http://www.phpclasses.org/mimemessage $Revision: 1.99 $</value>
		<documentation>
			<purpose>Specify the base text that is used identify the name and the
				version of the class that is used to send the message by setting an
				implicit the <tt>X-Mailer</tt> message header. This is meant
				mostly to assist on the debugging of delivery problems.</purpose>
			<usage>Change this to set another mailer identification string or
				leave it to an empty string to prevent that the <tt>X-Mailer</tt>
				header be added to the message.</usage>
		</documentation>
	</variable>
{/metadocument}
*/
	var $mailer='';

/*
{metadocument}
	<variable>
		<name>mailer_delivery</name>
		<type>STRING</type>
		<value>mail</value>
		<documentation>
			<purpose>Specify the text that is used to identify the mail
				delivery class or sub-class. This text is appended to the
				<tt>X-Mailer</tt> header text defined by the
				<variablelink>mailer</variablelink> variable.</purpose>
			<usage>This variable should only be redefined by the different mail
				delivery sub-classes.</usage>
		</documentation>
	</variable>
{/metadocument}
*/
	var $mailer_delivery='mail';

/*
{metadocument}
	<variable>
		<name>default_charset</name>
		<type>STRING</type>
		<value>ISO-8859-1</value>
		<documentation>
			<purpose>Specify the default character set to be assumed for the
				message headers and body text.</purpose>
			<usage>Change this variable to the correct character set name if it
				is different than the default.</usage>
		</documentation>
	</variable>
{/metadocument}
*/
	var $default_charset="ISO-8859-1";

/*
{metadocument}
	<variable>
		<name>line_quote_prefix</name>
		<type>STRING</type>
		<value>&gt; </value>
		<documentation>
			<purpose>Specify the default line quote prefix text used by the
				<functionlink>QuoteText</functionlink> function.</purpose>
			<usage>Change it only if you prefer to quote lines marking them with
				a different line prefix.</usage>
		</documentation>
	</variable>
{/metadocument}
*/
	var $line_quote_prefix="> ";

/*
{metadocument}
	<variable>
		<name>break_long_lines</name>
		<type>BOOLEAN</type>
		<value>1</value>
		<documentation>
			<purpose>Determine whether lines exceeding the length limit will be
				broken by the line break character when using the
				<functionlink>WrapText</functionlink> function.</purpose>
			<usage>Change it only if you want to avoid breaking long lines
				without any space characters, like for instance of messages with
				long URLs.</usage>
		</documentation>
	</variable>
{/metadocument}
*/
	var $break_long_lines=1;

/*
{metadocument}
	<variable>
		<name>file_buffer_length</name>
		<type>INTEGER</type>
		<value>8000</value>
		<documentation>
			<purpose>Specify the length of the buffer that is used to read
				files in chunks of limited size.</purpose>
			<usage>The default value may be increased if you have plenty of
				memory and want to benefit from additional speed when processing
				the files that are used to compose messages.</usage>
		</documentation>
	</variable>
{/metadocument}
*/
	var $file_buffer_length=8000;

/*
{metadocument}
	<variable>
		<name>debug</name>
		<type>STRING</type>
		<value></value>
		<documentation>
			<purpose>Specify the name of a function that is called whenever an
				error occurs.</purpose>
			<usage>If you need to track the errors that may happen during the use
				of the class, set this variable to the name of a callback function.
				It should take only one argument that is the error message. When
				this variable is set to an empty string, no debug callback function
				is called.</usage>
		</documentation>
	</variable>
{/metadocument}
*/
	var $debug="";

/*
{metadocument}
	<variable>
		<name>cache_body</name>
		<type>BOOLEAN</type>
		<value>0</value>
		<documentation>
			<purpose>Specify whether the message bodies that are generated by the
				class before sending, should be cached in memory to be reused on
				the next message delivery.</purpose>
			<usage>Set this variable to <tt><booleanvalue>1</booleanvalue></tt>
				if you intend to send the a message with the same body to many
				recipients, so the class avoids the overhead of regenerating
				messages with the same content.</usage>
		</documentation>
	</variable>
{/metadocument}
*/
	var $cache_body=0;

/*
{metadocument}
	<variable>
		<name>error</name>
		<type>STRING</type>
		<value></value>
		<documentation>
			<purpose>Store the last error return by any function that may fail
				due to some error.</purpose>
			<usage>Do not change this variable value unless you intend to clear
				the error status by setting it to an empty string.</usage>
		</documentation>
	</variable>
{/metadocument}
*/
	var $error="";

/*
{metadocument}
	<variable>
		<name>localhost</name>
		<type>STRING</type>
		<value></value>
		<documentation>
			<purpose>Specify the domain name of the computer sending the
				message.</purpose>
			<usage>This value is used as default domain of the sender e-mail
				address when generating automatic <tt>Message-Id</tt>
				headers.</usage>
		</documentation>
	</variable>
{/metadocument}
*/
	var $localhost="";

	/* Private methods */

	Function Tokenize($string,$separator="")
	{
		if(!strcmp($separator,""))
		{
			$separator=$string;
			$string=$this->next_token;
		}
		for($character=0;$character<strlen($separator);++$character)
		{
			if(GetType($position=strpos($string,$separator[$character]))=="integer")
				$found=(IsSet($found) ? min($found,$position) : $position);
		}
		if(IsSet($found))
		{
			$this->next_token=substr($string,$found+1);
			return(substr($string,0,$found));
		}
		else
		{
			$this->next_token="";
			return($string);
		}
	}

	Function GetFilenameExtension($filename)
	{
		return(GetType($dot=strrpos($filename,"."))=="integer" ? substr($filename,$dot) : "");
	}

	Function OutputError($error)
	{
		if(strcmp($function=$this->debug,"")
		&& strcmp($error,""))
			$function($error);
		return($this->error=$error);
	}

	Function OutputPHPError($error, &$php_error_message)
	{
		if(IsSet($php_error_message)
		&& strlen($php_error_message))
			$error.=": ".$php_error_message;
		return($this->OutputError($error));
	}

	Function GetPHPVersion()
	{
		if($this->php_version==0)
		{
			$version=explode(".",function_exists("phpversion") ? phpversion() : "3.0.7");
			$this->php_version=$version[0]*1000000+$version[1]*1000+$version[2];
		}
		return($this->php_version);
	}

	Function EscapePattern($pattern)
	{
		return('/'.str_replace('/', '\\/', $pattern).'/');
	}

	Function GetRFC822Addresses($address,&$addresses)
	{
		if(function_exists("imap_rfc822_parse_adrlist"))
		{
			if(GetType($parsed_addresses=@imap_rfc822_parse_adrlist($address,$this->localhost))!="array")
				return("it was not specified a valid address list");
			for($entry=0;$entry<count($parsed_addresses);++$entry)
			{
				if(!IsSet($parsed_addresses[$entry]->host)
				|| $parsed_addresses[$entry]->host==".SYNTAX-ERROR.")
					return($parsed_addresses[$entry]->mailbox." .SYNTAX-ERROR.");
				$parsed_address=$parsed_addresses[$entry]->mailbox."@".$parsed_addresses[$entry]->host;
				if(IsSet($addresses[$parsed_address]))
					++$addresses[$parsed_address];
				else
					$addresses[$parsed_address]=1;
			}
		}
		else
		{
			$length=strlen($address);
			for($position=0;$position<$length;)
			{
				$match=preg_split($this->EscapePattern($this->email_address_pattern),strtolower(substr($address,$position)),2);
				if(count($match)<2)
					break;
				$position+=strlen($match[0]);
				$next_position=$length-strlen($match[1]);
				$found=substr($address,$position,$next_position-$position);
				if(!strcmp($found,""))
					break;
				if(IsSet($addresses[$found]))
					++$addresses[$found];
				else
					$addresses[$found]=1;
				$position=$next_position;
			}
		}
		return("");
	}

	Function FormatHeader($header_name,$header_value)
	{
		$length=strlen($header_value);
		for($header_data="",$header_line=$header_name.": ",$line_length=strlen($header_line),$position=0;$position<$length;)
		{
			for($space=$position,$line_length=strlen($header_line);$space<$length;)
			{
				if(GetType($next=strpos($header_value," ",$space+1))!="integer")
					$next=$length;
				if($next-$position+$line_length>$this->header_length_limit)
				{
					if($space==$position)
						$space=$next;
					break;
				}
				$space=$next;
			}
			$header_data.=$header_line.substr($header_value,$position,$space-$position);
			if($space<$length)
				$header_line="";
			$position=$space;
			if($position<$length)
				$header_data.=$this->line_break;
		}
		return($header_data);
	}

	Function GenerateMessageID($sender)
	{
		$micros=$this->Tokenize(microtime()," ");
		$seconds=$this->Tokenize("");
		$local=$this->Tokenize($sender,"@");
		$host=$this->Tokenize(" @");
		if(strlen($host)
		&& $host[strlen($host)-1]=="-")
			$host=substr($host,0,strlen($host)-1);
		return($this->FormatHeader("Message-ID", "<".strftime("%Y%m%d%H%M%S", $seconds).substr($micros,1,5).".".preg_replace('/[^A-Za-z]/', '-', $local)."@".preg_replace('/[^.A-Za-z_-]/', '', $host).">"));
	}

	Function SendMail($to, $subject, $body, $headers, $return_path)
	{
		if(!function_exists("mail"))
			return($this->OutputError("the mail() function is not available in this PHP installation"));
		if(strlen($return_path))
		{
			if(!defined("PHP_OS"))
				return($this->OutputError("it is not possible to set the Return-Path header with your PHP version"));
			if(!strcmp(substr(PHP_OS,0,3),"WIN"))
				return($this->OutputError("it is not possible to set the Return-Path header directly from a PHP script on Windows"));
			if($this->GetPHPVersion()<4000005)
				return($this->OutputError("it is not possible to set the Return-Path header in PHP version older than 4.0.5"));
			if(function_exists("ini_get")
			&& ini_get("safe_mode"))
				return($this->OutputError("it is not possible to set the Return-Path header due to PHP safe mode restrictions"));
			$success=@mail($to,$subject,$body,$headers,"-f".$return_path);
		}
		else
			$success=@mail($to,$subject,$body,$headers);
		return($success ? "" : $this->OutputPHPError("it was not possible to send e-mail message", $php_errormsg));
	}

	Function StartSendingMessage()
	{
		if(strcmp($this->delivery["State"],""))
			return($this->OutputError("the message was already started to be sent"));
		$this->delivery=array("State"=>"SendingHeaders");
		return("");
	}

	Function SendMessageHeaders($headers)
	{
		if(strcmp($this->delivery["State"],"SendingHeaders"))
		{
			if(!strcmp($this->delivery["State"],""))
				return($this->OutputError("the message was not yet started to be sent"));
			else
				return($this->OutputError("the message headers were already sent"));
		}
		$this->delivery["Headers"]=$headers;
		$this->delivery["State"]="SendingBody";
		return("");
	}

	Function SendMessageBody($data)
	{
		if(strcmp($this->delivery["State"],"SendingBody"))
			return($this->OutputError("the message headers were not yet sent"));
		if(IsSet($this->delivery["Body"]))
			$this->delivery["Body"].=$data;
		else
			$this->delivery["Body"]=$data;
		return("");
	}

	Function EndSendingMessage()
	{
		if(strcmp($this->delivery["State"],"SendingBody"))
			return($this->OutputError("the message body data was not yet sent"));
		if(!IsSet($this->delivery["Headers"])
		|| count($this->delivery["Headers"])==0)
			return($this->OutputError("message has no headers"));
		$line_break=((defined("PHP_OS") && !strcmp(substr(PHP_OS,0,3),"WIN")) ? "\r\n" : $this->line_break);
		$headers=$this->delivery["Headers"];
		for($has=array(),$headers_text="",$header=0,Reset($headers);$header<count($headers);Next($headers),++$header)
		{
			$header_name=Key($headers);
			switch(strtolower($header_name))
			{
				case "to":
				case "subject":
					$has[strtolower($header_name)]=$headers[$header_name];
					break;
				case "cc":
				case "bcc":
				case "from":
				case "return-path":
				case "message-id":
					$has[strtolower($header_name)]=$headers[$header_name];
				default:
					$header_line=$header_name.": ".$headers[$header_name];
					if(strlen($headers_text))
						$headers_text.=$this->line_break.$header_line;
					else
						$headers_text=$header_line;
			}
		}
		if(strlen($has["to"])==0
		&& !IsSet($has["cc"])
		&& !IsSet($has["bcc"]))
			return($this->OutputError("it were not specified a valid To:, Cc: or Bcc: headers"));
		if(!IsSet($has["subject"]))
			return($this->OutputError("it was not specified a valid Subject: header"));
		if(!IsSet($has["message-id"])
		&& $this->auto_message_id)
		{
			$sender = array();
			if(IsSet($has["return-path"]))
				$sender[] = $has["return-path"];
			if(IsSet($has["from"]))
				$sender[] = $has["from"];
			$sender[] = $has["to"];
			$ts = count($sender);
			for($s = 0; $s < $ts; ++$s)
			{
				$error = $this->GetRFC822Addresses($sender[$s], $senders);
				if(strlen($error) == 0
				&& count($senders))
					break;
			}
			if(count($senders) == 0)
				return('it was not specified a valid sender address'.(strlen($error) ? ': '.$error : ''));
			Reset($senders);
			$sender=Key($senders);
			$header_line=$this->GenerateMessageID($sender);
			if(strlen($headers_text))
				$headers_text.=$this->line_break.$header_line;
			else
				$headers_text=$header_line;
		}
		if(strcmp($error=$this->SendMail(strlen($has["to"]) ? $has["to"] : (IsSet($has["cc"]) ? "" : "undisclosed-recipients: ;"), $has["subject"], $this->delivery["Body"], $headers_text, IsSet($has["return-path"]) ? $has["return-path"] : ""),""))
			return($error);
		$this->delivery=array("State"=>"");
		return("");
	}

	Function StopSendingMessage()
	{
		$this->delivery=array("State"=>"");
		return("");
	}

	Function GetPartBoundary($part)
	{
		if(!IsSet($this->parts[$part]["BOUNDARY"]))
			$this->parts[$part]["BOUNDARY"]=md5(uniqid($part.time()));
	}

	Function GetPartHeaders(&$headers,$part)
	{
		if(IsSet($this->parts[$part]['CachedHeaders']))
		{
			$headers = $this->parts[$part]['CachedHeaders'];
			return('');
		}
		if(!IsSet($this->parts[$part]["Content-Type"]))
			return($this->OutputError("it was added a part without Content-Type: defined"));
		$type=$this->Tokenize($full_type=strtolower($this->parts[$part]["Content-Type"]),"/");
		$sub_type=$this->Tokenize("");
		switch($type)
		{
			case "text":
			case "image":
			case "audio":
			case "video":
			case "application":
			case "message":
				if(IsSet($this->parts[$part]["NAME"]))
					$filename = $this->QuotedPrintableEncode($this->parts[$part]["NAME"], $this->default_charset, 1, 1);
				$headers["Content-Type"]=$full_type.(IsSet($this->parts[$part]["CHARSET"]) ? "; charset=".$this->parts[$part]["CHARSET"] : "").(IsSet($this->parts[$part]["NAME"]) ? "; name=\"".$filename."\"" : "");
				if(IsSet($this->parts[$part]["Content-Transfer-Encoding"]))
					$headers["Content-Transfer-Encoding"]=$this->parts[$part]["Content-Transfer-Encoding"];
				if(IsSet($this->parts[$part]["DISPOSITION"])
				&& strlen($this->parts[$part]["DISPOSITION"]))
					$headers["Content-Disposition"]=$this->parts[$part]["DISPOSITION"].(IsSet($this->parts[$part]["NAME"]) ? "; filename=\"".$filename."\"" : "");
				break;
			case "multipart":
				switch($sub_type)
				{
					case "alternative":
					case "related":
					case "mixed":
					case "parallel":
						$this->GetPartBoundary($part);
						$headers["Content-Type"]=$full_type."; boundary=\"".$this->parts[$part]["BOUNDARY"]."\"";
						break;
					default:
						return($this->OutputError("multipart Content-Type sub_type $sub_type not yet supported"));
				}
				break;
			default:
				return($this->OutputError("Content-Type: $full_type not yet supported"));
		}
		if(IsSet($this->parts[$part]["Content-ID"]))
			$headers["Content-ID"]="<".$this->parts[$part]["Content-ID"].">";
		if(IsSet($this->parts[$part]['Cache'])
		&& $this->parts[$part]['Cache'])
			$this->parts[$part]['CachedHeaders'] = $headers;
		return("");
	}

	Function GetPartBody(&$body,$part)
	{
		if(IsSet($this->parts[$part]['CachedBody']))
		{
			$body = $this->parts[$part]['CachedBody'];
			return('');
		}
		if(!IsSet($this->parts[$part]["Content-Type"]))
			return($this->OutputError("it was added a part without Content-Type: defined"));
		$type=$this->Tokenize($full_type=strtolower($this->parts[$part]["Content-Type"]),"/");
		$sub_type=$this->Tokenize("");
		$body="";
		switch($type)
		{
			case "text":
			case "image":
			case "audio":
			case "video":
			case "application":
			case "message":
				if(IsSet($this->parts[$part]["FILENAME"]))
				{
					$size=@filesize($this->parts[$part]["FILENAME"]);
					if(!($file=@fopen($this->parts[$part]["FILENAME"],"rb")))
						return($this->OutputPHPError("could not open part file ".$this->parts[$part]["FILENAME"], $php_errormsg));
					while(!feof($file))
					{
						if(GetType($block=@fread($file,$this->file_buffer_length))!="string")
						{
							fclose($file);
							return($this->OutputPHPError("could not read part file", $php_errormsg));
						}
						$body.=$block;
					}
					fclose($file);
					if((GetType($size)=="integer"
					&& strlen($body)>$size)
					|| (function_exists("get_magic_quotes_runtime")
					&& get_magic_quotes_runtime()))
						$body=StripSlashes($body);
					if(GetType($size)=="integer"
					&& strlen($body)!=$size)
						return($this->OutputError("the length of the file that was read does not match the size of the part file ".$this->parts[$part]["FILENAME"]." due to possible data corruption"));
				}
				else
				{
					if(!IsSet($this->parts[$part]["DATA"]))
						return($this->OutputError("it was added a part without a body PART"));
					$body=$this->parts[$part]["DATA"];
				}
				$encoding=(IsSet($this->parts[$part]["Content-Transfer-Encoding"]) ? strtolower($this->parts[$part]["Content-Transfer-Encoding"]) : "");
				switch($encoding)
				{
					case "base64":
						$body=chunk_split(base64_encode($body), $this->line_length, $this->line_break);
						break;
					case "":
					case "quoted-printable":
					case "7bit":
						break;
					default:
						return($this->OutputError($encoding." is not yet a supported encoding type"));
				}
				break;
			case "multipart":
				switch($sub_type)
				{
					case "alternative":
					case "related":
					case "mixed":
					case "parallel":
						$this->GetPartBoundary($part);
						$boundary="--".$this->parts[$part]["BOUNDARY"];
						$parts=count($this->parts[$part]["PARTS"]);
						$b = $this->line_break;
						$lb = strlen($b);
						for($multipart=0;$multipart<$parts;$multipart++)
						{
							if(strlen($body) >= $lb
							&& strcmp(substr($body, -$lb), $b))
								$body.=$b;
							$body.=$boundary.$this->line_break;
							$part_headers=array();
							$sub_part=$this->parts[$part]["PARTS"][$multipart];
							if(strlen($error=$this->GetPartHeaders($part_headers,$sub_part)))
								return($error);
							for($part_header=0,Reset($part_headers);$part_header<count($part_headers);$part_header++,Next($part_headers))
							{
								$header=Key($part_headers);
								$body.=$header.": ".$part_headers[$header].$b;
							}
							$body.=$b;
							if(strlen($error=$this->GetPartBody($part_body,$sub_part)))
								return($error);
							$body.=$part_body;
						}
						if(strlen($body) >= $lb
						&& strcmp(substr($body, -$lb), $b))
							$body.=$b;
						$body.=$boundary."--".$b;
						break;
					default:
						return($this->OutputError("multipart Content-Type sub_type $sub_type not yet supported"));
				}
				break;
			default:
				return($this->OutputError("Content-Type: $full_type not yet supported"));
		}
		if(IsSet($this->parts[$part]['Cache'])
		&& $this->parts[$part]['Cache'])
			$this->parts[$part]['CachedBody'] = $body;
		return("");
	}

	/* Public functions */

/*
{metadocument}
	<function>
		<name>ValidateEmailAddress</name>
		<type>BOOLEAN</type>
		<documentation>
			<purpose>Determine whether a given e-mail address may be
				valid.</purpose>
			<usage>Just pass the e-mail <argumentlink>
					<function>ValidateEmailAddress</function>
					<argument>address</argument>
				</argumentlink> to be checked as function argument. This function
				uses the regular expression defined by the
				<variablelink>email_regular_expression</variablelink> variable to
				check the address.</usage>
			<returnvalue>The function returns
				<tt><booleanvalue>1</booleanvalue></tt> if the specified address
				may be valid.</returnvalue>
		</documentation>
		<argument>
			<name>address</name>
			<type>STRING</type>
			<documentation>
				<purpose>Specify the e-mail address to be validated.</purpose>
			</documentation>
		</argument>
		<do>
{/metadocument}
*/
	Function ValidateEmailAddress($address)
	{
		return(preg_match('/'.str_replace('/', '\\/', $this->email_regular_expression).'/i',$address));
	}
/*
{metadocument}
		</do>
	</function>
{/metadocument}
*/

	Function EncodeCharacter($matches)
	{
		return sprintf('=%02X', Ord($matches[1]));
	}

	Function QuotedPrintableEncode($text, $header_charset='', $break_lines=1, $email_header = 0)
	{
		$ln=strlen($text);
		$h=(strlen($header_charset)>0);
		if($h)
		{
			$encode = array(
				'='=>1,
				'?'=>1,
				'_'=>1,
				'('=>1,
				')'=>1,
				'<'=>1,
				'>'=>1,
				'@'=>1,
				','=>1,
				';'=>1,
				'"'=>1,
				'\\'=>1,
				'['=>1,
				']'=>1,
				':'=>1,
/*
				'/'=>1,
				'.'=>1,
*/
			);
			$s=($email_header ? $encode : array());
			$b=$space=$break_lines=0;
			for($i=0; $i<$ln; ++$i)
			{
				$c = $text[$i];
				if(IsSet($s[$c]))
				{
					$b=1;
					break;
				}
				switch($o=Ord($c))
				{
					case 9:
					case 32:
						$space=$i+1;
						$b=1;
						break 2;
					case 10:
					case 13:
						break 2;
					default:
						if($o<32
						|| $o>127)
						{
							$b=1;
							$s = $encode;
							break 2;
						}
				}
			}
			if($i==$ln)
				return($text);
			if($space>0)
				return(substr($text,0,$space).($space<$ln ? $this->QuotedPrintableEncode(substr($text,$space), $header_charset, $break_lines, $email_header) : ""));
		}
		elseif(function_exists('quoted_printable_encode'))
		{
			$different = strcmp($this->line_break, "\r\n");
			if($different)
				$text = str_replace($this->line_break, "\r\n", str_replace("\r\n", $this->line_break, $text));
			$encoded = preg_replace_callback('/^(f|F|\\.)/m', array($this, 'EncodeCharacter'), quoted_printable_encode($text));
			if($different)
				$encoded = str_replace("\r\n", $this->line_break, $encoded);
			return $encoded;
		}
		for($w=$e='',$n=0, $l=0,$i=0;$i<$ln; ++$i)
		{
			$c = $text[$i];
			$o=Ord($c);
			$en=0;
			switch($o)
			{
				case 9:
				case 32:
					if(!$h)
					{
						$w=$c;
						$c='';
					}
					else
					{
						if($b)
						{
							if($o==32)
								$c='_';
							else
								$en=1;
						}
					}
					break;
				case 10:
				case 13:
					if(strlen($w))
					{
						if($break_lines
						&& $l+3>75)
						{
							$e.='='.$this->line_break;
							$l=0;
						}
						$e.=sprintf('=%02X',Ord($w));
						$l+=3;
						$w='';
					}
					$e.=$c;
					if($h)
						$e.="\t";
					$l=0;
					continue 2;
				case 46:
				case 70:
				case 102:
					$en=(!$h && ($l==0 || $l+1>75));
					break;
				default:
					if($o>127
					|| $o<32
					|| !strcmp($c,'='))
						$en=1;
					elseif($h
					&& IsSet($s[$c]))
						$en=1;
					break;
			}
			if(strlen($w))
			{
				if($break_lines
				&& $l+1>75)
				{
					$e.='='.$this->line_break;
					$l=0;
				}
				$e.=$w;
				++$l;
				$w='';
			}
			if(strlen($c))
			{
				if($en)
				{
					$c=sprintf('=%02X',$o);
					$el=3;
					$n=1;
					$b=1;
				}
				else
					$el=1;
				if($break_lines
				&& $l+$el>75)
				{
					$e.='='.$this->line_break;
					$l=0;
				}
				$e.=$c;
				$l+=$el;
			}
		}
		if(strlen($w))
		{
			if($break_lines
			&& $l+3>75)
				$e.='='.$this->line_break;
			$e.=sprintf('=%02X',Ord($w));
		}
		if($h
		&& $n)
			return('=?'.$header_charset.'?q?'.$e.'?=');
		else
			return($e);
	}

/*
{metadocument}
	<function>
		<name>WrapText</name>
		<type>STRING</type>
		<documentation>
			<purpose>Split a text in lines that do not exceed the length limit
				avoiding to break it in the middle of any words.</purpose>
			<usage>Just pass the <argumentlink>
					<function>WrapText</function>
					<argument>text</argument>
				</argumentlink> to be wrapped.</usage>
			<returnvalue>The wrapped text eventually broken in multiple lines
				that do not exceed the line length limit.</returnvalue>
		</documentation>
		<argument>
			<name>text</name>
			<type>STRING</type>
			<documentation>
				<purpose>Text to be wrapped.</purpose>
			</documentation>
		</argument>
		<argument>
			<name>line_length</name>
			<type>INTEGER</type>
			<defaultvalue>0</defaultvalue>
			<documentation>
				<purpose>Line length limit. Pass a value different than
					<tt><integervalue>0</integervalue></tt> to use a line length
					limit other than the default of 75 characters.</purpose>
			</documentation>
		</argument>
		<argument>
			<name>line_break</name>
			<type>STRING</type>
			<defaultvalue></defaultvalue>
			<documentation>
				<purpose>Character sequence that is used to break the lines longer
					than the length limit. Pass a non-empty to use a line breaking
					sequence other than the default
					<tt><stringvalue>&#10;</stringvalue></tt>.</purpose>
			</documentation>
		</argument>
		<argument>
			<name>line_prefix</name>
			<type>STRING</type>
			<defaultvalue></defaultvalue>
			<documentation>
				<purpose>Character sequence that is used to insert in the beginning
					of all lines.</purpose>
			</documentation>
		</argument>
		<do>
{/metadocument}
*/
	Function WrapText($text,$line_length=0,$line_break="",$line_prefix="")
	{
		if(strlen($line_break)==0)
			$line_break=$this->line_break;
		if($line_length==0)
			$line_length=$this->line_length;
		$lines=explode("\n",str_replace("\r","\n",str_replace("\r\n","\n",$text)));
		for($wrapped="",$line=0;$line<count($lines);++$line)
		{
			if(strlen($text_line=$lines[$line]))
			{
				for(;strlen($text_line=$line_prefix.$text_line)>$line_length;)
				{
					if(GetType($cut=strrpos(substr($text_line,0,$line_length)," "))!="integer"
					|| $cut<strlen($line_prefix))
					{
						if($this->break_long_lines)
						{
							$wrapped.=substr($text_line,0,$line_length).$line_break;
							$cut=$line_length;
						}
						elseif(GetType($cut=strpos($text_line," ",$line_length))=="integer")
						{
							$wrapped.=substr($text_line, 0, $cut).$line_break;
							++$cut;
						}
						else
						{
							$wrapped.=$text_line.$line_break;
							$cut=strlen($text_line);
						}
					}
					else
					{
						$wrapped.=substr($text_line,0,$cut).$line_break;
						++$cut;
					}
					$text_line=substr($text_line,$cut);
				}
			}
			$wrapped.=$text_line.$line_break;
		}
		return($wrapped);
	}
/*
{metadocument}
		</do>
	</function>
{/metadocument}
*/

/*
{metadocument}
	<function>
		<name>CenterText</name>
		<type>STRING</type>
		<documentation>
			<purpose>Center a text in the middle of line.</purpose>
			<usage>Just pass the <argumentlink>
					<function>CenterText</function>
					<argument>text</argument>
				</argumentlink> to be centered.</usage>
			<returnvalue>The centered text.</returnvalue>
		</documentation>
		<argument>
			<name>text</name>
			<type>STRING</type>
			<documentation>
				<purpose>Text to be centered.</purpose>
			</documentation>
		</argument>
		<argument>
			<name>line_length</name>
			<type>INTEGER</type>
			<defaultvalue>0</defaultvalue>
			<documentation>
				<purpose>Line length limit. Pass a value different than
					<tt><integervalue>0</integervalue></tt> to use a line length
					limit other than the default of 75 characters.</purpose>
			</documentation>
		</argument>
		<do>
{/metadocument}
*/
	Function CenterText($text, $line_length=0)
	{
		if($line_length==0)
			$line_length=$this->line_length;
		$length = strlen($text);
		if($length<$line_length)
			$text = str_repeat(' ', ($line_length-$length)/2).$text;
		return($text);
	}
/*
{metadocument}
		</do>
	</function>
{/metadocument}
*/

/*
{metadocument}
	<function>
		<name>Ruler</name>
		<type>STRING</type>
		<documentation>
			<purpose>Generate a line with characters that can be displayed as a
				separator ruler in a text message.</purpose>
			<returnvalue>The ruler line string.</returnvalue>
		</documentation>
		<argument>
			<name>line_length</name>
			<type>INTEGER</type>
			<defaultvalue>0</defaultvalue>
			<documentation>
				<purpose>Line length limit. Pass a value different than
					<tt><integervalue>0</integervalue></tt> to use a line length
					limit other than the default of 75 characters.</purpose>
			</documentation>
		</argument>
		<do>
{/metadocument}
*/
	Function Ruler($line_length=0)
	{
		if($line_length==0)
			$line_length=$this->line_length;
		return(str_repeat($this->ruler, $line_length));
	}
/*
{metadocument}
		</do>
	</function>
{/metadocument}
*/

/*
{metadocument}
	<function>
		<name>QuoteText</name>
		<type>STRING</type>
		<documentation>
			<purpose>Mark a text block to appear like in reply messages composed
				with common e-mail programs that include text from the original
				message being replied.</purpose>
			<usage>Just pass the <argumentlink>
					<function>QuoteText</function>
					<argument>text</argument>
				</argumentlink> to be marked as a quote.</usage>
			<returnvalue>The quoted text with all lines prefixed with a quote
				prefix mark.</returnvalue>
		</documentation>
		<argument>
			<name>text</name>
			<type>STRING</type>
			<documentation>
				<purpose>Text to be quoted.</purpose>
			</documentation>
		</argument>
		<argument>
			<name>quote_prefix</name>
			<type>STRING</type>
			<defaultvalue></defaultvalue>
			<documentation>
				<purpose>Character sequence that is inserted in the beginning of
					all lines as a quote mark. Set to an empty string to tell the
					function to use the default specified by the
					<variablelink>line_quote_prefix</variablelink> variable.</purpose>
			</documentation>
		</argument>
		<do>
{/metadocument}
*/
	Function QuoteText($text,$quote_prefix="")
	{
		if(strlen($quote_prefix)==0)
			$quote_prefix=$this->line_quote_prefix;
		return($this->WrapText($text,$line_length=0,$line_break="",$quote_prefix));
	}
/*
{metadocument}
		</do>
	</function>
{/metadocument}
*/

/*
{metadocument}
	<function>
		<name>SetHeader</name>
		<type>STRING</type>
		<documentation>
			<purpose>Set the value of a message header.</purpose>
			<usage>Use this function to set the values of the headers of the
				message that may be needed. There are some message headers that are
				automatically set by the class when the message is sent. Others
				must be defined before sending. Here follows the list of the names
				of the headers that must be set before sending:<paragraphbreak />
				<paragraphbreak />
				<b>Message subject</b> - <tt>Subject</tt><paragraphbreak />
				<b>Sender address</b> - <tt>From</tt><paragraphbreak />
				<b>Recipient addresses</b> - <tt>To</tt>, <tt>Cc</tt> and
				<tt>Bcc</tt><paragraphbreak />
				Each of the recipient address headers may contain one or more
				addresses. Multiple addresses must be separated by a comma and a
				space.<paragraphbreak />
				<b>Return path address</b> - <tt>Return-Path</tt><paragraphbreak />
				Optional header to specify the address where the message should be
				bounced in case it is not possible to deliver it.<paragraphbreak />
				In reality this is a virtual header. This means that adding this
				header to a message will not do anything by itself. However, this
				class looks for this header to adjust the message delivery
				procedure in such way that the Message Transfer Agent (MTA) system
				is hinted to direct any bounced messages to the address specified
				by this header.<paragraphbreak />
				Note that under some systems there is no way to set the return path
				address programmatically. This is the case when using the PHP
				<tt>mail()</tt> function under Windows where the return path
				address should be set in the <tt>php.ini</tt> configuration
				file.<paragraphbreak />
				Keep in mind that even when it is possible to set the return path
				address, the systems of some e-mail account providers may ignore
				this address and send bounced messages to the sender address. This
				is a bug of those systems. There is nothing that can be done other
				than complain.</usage>
			<returnvalue>An error message in case there was an error or an empty
				string otherwise. This return value may be safely ignored if the
				function parameters are set correctly.</returnvalue>
		</documentation>
		<argument>
			<name>header</name>
			<type>STRING</type>
			<documentation>
				<purpose>Name of the header.</purpose>
			</documentation>
		</argument>
		<argument>
			<name>value</name>
			<type>STRING</type>
			<documentation>
				<purpose>Text value for the header.</purpose>
			</documentation>
		</argument>
		<argument>
			<name>encoding_charset</name>
			<type>STRING</type>
			<defaultvalue></defaultvalue>
			<documentation>
				<purpose>Character set used in the header value. If it is set to an
					empty string, it is assumed the character set defined by the
					<variablelink>default_charset</variablelink> variable.</purpose>
			</documentation>
		</argument>
		<do>
{/metadocument}
*/
	Function SetHeader($header, $value, $encoding_charset="")
	{
		if(strlen($this->error))
			return($this->error);
		$this->headers[strval($header)]=(!strcmp($encoding_charset,"") ? strval($value) : $this->QuotedPrintableEncode($value, $encoding_charset, 1, 0));
		return("");
	}
/*
{metadocument}
		</do>
	</function>
{/metadocument}
*/

/*
{metadocument}
	<function>
		<name>SetEncodedHeader</name>
		<type>STRING</type>
		<documentation>
			<purpose>The same as the <functionlink>SetHeader</functionlink>
				function assuming the default character set specified by the
				<variablelink>default_charset</variablelink> variable.</purpose>
			<usage>See the <functionlink>SetHeader</functionlink> function.</usage>
			<returnvalue>An error message in case there was an error or an empty
				string otherwise. This return value may be safely ignored if the
				function parameters are set correctly.</returnvalue>
		</documentation>
		<argument>
			<name>header</name>
			<type>STRING</type>
			<documentation>
				<purpose>Name of the header.</purpose>
			</documentation>
		</argument>
		<argument>
			<name>value</name>
			<type>STRING</type>
			<documentation>
				<purpose>Text value for the header.</purpose>
			</documentation>
		</argument>
		<argument>
			<name>encoding_charset</name>
			<type>STRING</type>
			<defaultvalue></defaultvalue>
			<documentation>
				<purpose>Character set used in the header value. If it is set to an
					empty string, it is assumed the character set defined by the
					<variablelink>default_charset</variablelink> variable.</purpose>
			</documentation>
		</argument>
		<do>
{/metadocument}
*/
	Function SetEncodedHeader($header,$value, $encoding_charset = '')
	{
		return($this->SetHeader($header,$value,strlen($encoding_charset) ? $encoding_charset : $this->default_charset));
	}
/*
{metadocument}
		</do>
	</function>
{/metadocument}
*/

/*
{metadocument}
	<function>
		<name>SetEncodedEmailHeader</name>
		<type>STRING</type>
		<documentation>
			<purpose>Set the value of an header that is meant to represent the
				e-mail address of a person or entity with a known name. This is
				meant mostly to set the <tt>From</tt>, <tt>To</tt>, <tt>Cc</tt> and
				<tt>Bcc</tt> headers.</purpose>
			<usage>Use this function like the
				<functionlink>SetHeader</functionlink> specifying the e-mail
				<argumentlink>
					<function>SetEncodedEmailHeader</function>
					<argument>address</argument>
				</argumentlink> as header value and also specifying the
				<argumentlink>
					<function>SetEncodedEmailHeader</function>
					<argument>name</argument>
				</argumentlink> of the known person or entity.</usage>
			<returnvalue>An error message in case there was an error or an empty
				string otherwise. This return value may be safely ignored if the
				function parameters are set correctly.</returnvalue>
		</documentation>
		<argument>
			<name>header</name>
			<type>STRING</type>
			<documentation>
				<purpose>Name of the header.</purpose>
			</documentation>
		</argument>
		<argument>
			<name>address</name>
			<type>STRING</type>
			<documentation>
				<purpose>E-mail address value.</purpose>
			</documentation>
		</argument>
		<argument>
			<name>name</name>
			<type>STRING</type>
			<documentation>
				<purpose>Person or entity name associated with the specified e-mail
					address.</purpose>
			</documentation>
		</argument>
		<argument>
			<name>encoding_charset</name>
			<type>STRING</type>
			<defaultvalue></defaultvalue>
			<documentation>
				<purpose>Character set used in the header value. If it is set to an
					empty string, it is assumed the character set defined by the
					<variablelink>default_charset</variablelink> variable.</purpose>
			</documentation>
		</argument>
		<do>
{/metadocument}
*/
	Function SetEncodedEmailHeader($header, $address, $name, $encoding_charset = '')
	{
		return($this->SetHeader($header,$this->QuotedPrintableEncode($name, strlen($encoding_charset) ? $encoding_charset : $this->default_charset, 1, 1).' <'.$address.'>'));
	}
/*
{metadocument}
		</do>
	</function>
{/metadocument}
*/

/*
{metadocument}
	<function>
		<name>SetMultipleEncodedEmailHeader</name>
		<type>STRING</type>
		<documentation>
			<purpose>Set the value of an header that is meant to represent a list
				of e-mail addresses of names of people or entities. This is meant
				mostly to set the <tt>To</tt>, <tt>Cc</tt> and <tt>Bcc</tt>
				headers.</purpose>
			<usage>Use this function specifying the <argumentlink>
					<function>SetMultipleEncodedEmailHeader</function>
					<argument>header</argument>
				</argumentlink> and all the <argumentlink>
					<function>SetMultipleEncodedEmailHeader</function>
					<argument>addresses</argument>
				</argumentlink> in an associative array that should have
				the email addresses as entry indexes and the name of the respective
				people or entities as entry values.</usage>
			<returnvalue>An error message in case there was an error or an empty
				string otherwise. This return value may be safely ignored if the
				function parameters are set correctly.</returnvalue>
			<example><pre>$message_object->SetMultipleEncodedEmailHeader('Bcc', array(
  'peter@gabriel.org' =&gt; 'Peter Gabriel',
  'paul@simon.net' =&gt; 'Paul Simon',
  'mary@chain.com' =&gt; 'Mary Chain'
));</pre></example>
		</documentation>
		<argument>
			<name>header</name>
			<type>STRING</type>
			<documentation>
				<purpose>Name of the header.</purpose>
			</documentation>
		</argument>
		<argument>
			<name>addresses</name>
			<type>HASH</type>
			<documentation>
				<purpose>List of all email addresses and associated person or
					entity names.</purpose>
			</documentation>
		</argument>
		<argument>
			<name>encoding_charset</name>
			<type>STRING</type>
			<defaultvalue></defaultvalue>
			<documentation>
				<purpose>Character set used in the header value. If it is set to an
					empty string, it is assumed the character set defined by the
					<variablelink>default_charset</variablelink> variable.</purpose>
			</documentation>
		</argument>
		<do>
{/metadocument}
*/
	Function SetMultipleEncodedEmailHeader($header,$addresses, $encoding_charset = '')
	{
		Reset($addresses);
		$end=(GetType($address=Key($addresses))!="string");
		for($value="";!$end;)
		{
			if(strlen($value))
				$value.=", ";
			$value.=$this->QuotedPrintableEncode($addresses[$address], strlen($encoding_charset) ? $encoding_charset : $this->default_charset, 1, 1).' <'.$address.'>';
			Next($addresses);
			$end=(GetType($address=Key($addresses))!="string");
		}
		return($this->SetHeader($header,$value));
	}
/*
{metadocument}
		</do>
	</function>
{/metadocument}
*/

/*
{metadocument}
	<function>
		<name>ResetMessage</name>
		<type>VOID</type>
		<documentation>
			<purpose>Restore the content of the message to the initial state when
				the class object is created, i.e. without any headers or body
				parts.</purpose>
			<usage>Use this function if you want to start composing a completely
				new message.</usage>
		</documentation>
		<do>
{/metadocument}
*/
	Function ResetMessage()
	{
		$this->headers=array();
		$this->body=-1;
		$this->body_parts=0;
		$this->parts=array();
		$this->total_parts=0;
		$this->free_parts=array();
		$this->total_free_parts=0;
		$this->delivery=array("State"=>"");
		$this->error="";
	}
/*
{metadocument}
		</do>
	</function>
{/metadocument}
*/

	Function CreatePart(&$definition,&$part)
	{
		$part=-1;
		if(strlen($this->error))
			return($this->error);
		if($this->total_free_parts)
		{
			$this->total_free_parts--;
			$part=$this->free_parts[$this->total_free_parts];
			Unset($this->free_parts[$this->total_free_parts]);
		}
		else
		{
			$part=$this->total_parts;
			++$this->total_parts;
		}
		$this->parts[$part]=$definition;
		return("");
	}

/*
{metadocument}
	<function>
		<name>AddPart</name>
		<type>STRING</type>
		<documentation>
			<purpose>Add a previously created part to the message.</purpose>
			<usage>Use any of the functions to create standalone message parts
				and then use this function to add them to the message.</usage>
			<returnvalue>An error message in case there was an error or an empty
				string otherwise. This return value may be safely ignored if the
				function parameters are set correctly.</returnvalue>
		</documentation>
		<argument>
			<name>part</name>
			<type>INTEGER</type>
			<documentation>
				<purpose>Number of the part as returned by the function that
					originally created it.</purpose>
			</documentation>
		</argument>
		<do>
{/metadocument}
*/
	Function AddPart($part)
	{
		if(strlen($this->error))
			return($this->error);
		switch($this->body_parts)
		{
			case 0;
				$this->body=$part;
				break;
			case 1:
				$parts=array(
					$this->body,
					$part
				);
				if(strlen($error=$this->CreateMixedMultipart($parts,$body)))
					return($error);
				$this->body=$body;
				break;
			default:
				$this->parts[$this->body]["PARTS"][]=$part;
				break;
		}
		++$this->body_parts;
		return("");
	}
/*
{metadocument}
		</do>
	</function>
{/metadocument}
*/

/*
{metadocument}
	<function>
		<name>ReplacePart</name>
		<type>STRING</type>
		<documentation>
			<purpose>Replace a message part already added to the message with a
				newly created part. The replaced part gets the definition of the
				replacing part. The replacing part is discarded and its part number
				becomes free for creation of a new part.</purpose>
			<usage>Use one of the functions to create message parts and then pass
				the returned part numbers to this function.</usage>
			<returnvalue>An error message in case there was an error or an empty
				string otherwise. This return value may be safely ignored if the
				function parameters are set correctly.</returnvalue>
		</documentation>
		<argument>
			<name>old_part</name>
			<type>INTEGER</type>
			<documentation>
				<purpose>Number of the previously added part.</purpose>
			</documentation>
		</argument>
		<argument>
			<name>new_part</name>
			<type>INTEGER</type>
			<documentation>
				<purpose>Number of the replacing part.</purpose>
			</documentation>
		</argument>
		<do>
{/metadocument}
*/
	Function ReplacePart($old_part,$new_part)
	{
		if(strlen($this->error))
			return($this->error);
		if(!IsSet($this->parts[$old_part]))
			return($this->error="it was attempted to replace an invalid message part");
		if(IsSet($this->parts[$old_part]["FREE"]))
			return($this->error="it was attempted to replace a message part that is no longer valid");
		if(!IsSet($this->parts[$new_part]))
			return($this->error="it was attempted to use an invalid message replacecement part");
		if(IsSet($this->parts[$new_part]["FREE"]))
			return($this->error="it was attempted to use a message replacecement part that is no longer valid");
		$this->parts[$old_part]=$this->parts[$new_part];
		$this->parts[$new_part]=array("FREE"=>1);
		$this->free_parts[$this->total_free_parts]=$new_part;
		++$this->total_free_parts;
		return("");
	}
/*
{metadocument}
		</do>
	</function>
{/metadocument}
*/

	Function CreateAndAddPart(&$definition)
	{
		if(strlen($error=$this->CreatePart($definition,$part))
		|| strlen($error=$this->AddPart($part)))
			return($error);
		return("");
	}

/*
{metadocument}
	<function>
		<name>CreatePlainTextPart</name>
		<type>STRING</type>
		<documentation>
			<purpose>Create a plain text message part.</purpose>
			<usage>Pass an ASCII (7 bits) <argumentlink>
					<function>CreatePlainTextPart</function>
					<argument>text</argument>
				</argumentlink> string and get the created part number in the
				<argumentlink>
					<function>CreatePlainTextPart</function>
					<argument>part</argument>
				</argumentlink> that is returned by reference.</usage>
			<returnvalue>An error message in case there was an error or an empty
				string otherwise. This return value may be safely ignored if the
				function parameters are set correctly.</returnvalue>
		</documentation>
		<argument>
			<name>text</name>
			<type>STRING</type>
			<documentation>
				<purpose>Text of the message part to create.</purpose>
			</documentation>
		</argument>
		<argument>
			<name>charset</name>
			<type>STRING</type>
			<documentation>
				<purpose>Character set used in the part text. If it is set to an
					empty string, it is assumed the character set defined by the
					<variablelink>default_charset</variablelink> variable.</purpose>
			</documentation>
		</argument>
		<argument>
			<name>part</name>
			<type>INTEGER</type>
			<out />
			<documentation>
				<purpose>Number of the created part that is returned by reference.</purpose>
			</documentation>
		</argument>
		<do>
{/metadocument}
*/
	Function CreatePlainTextPart($text,$charset,&$part)
	{
		if(!strcmp($charset,""))
			$charset=$this->default_charset;
		$definition=array(
			"Content-Type"=>"text/plain",
			"DATA"=>$text
		);
		if(strcmp(strtoupper($charset),"ASCII"))
			$definition["CHARSET"]=$charset;
		return($this->CreatePart($definition,$part));
	}
/*
{metadocument}
		</do>
	</function>
{/metadocument}
*/

/*
{metadocument}
	<function>
		<name>AddPlainTextPart</name>
		<type>STRING</type>
		<documentation>
			<purpose>Add a plain text part to the message.</purpose>
			<usage>Pass an ASCII (7 bits) <argumentlink>
					<function>AddPlainTextPart</function>
					<argument>text</argument>
				</argumentlink> string.</usage>
			<returnvalue>An error message in case there was an error or an empty
				string otherwise. This return value may be safely ignored if the
				function parameters are set correctly.</returnvalue>
		</documentation>
		<argument>
			<name>text</name>
			<type>STRING</type>
			<documentation>
				<purpose>Text of the message part to add.</purpose>
			</documentation>
		</argument>
		<argument>
			<name>charset</name>
			<type>STRING</type>
			<defaultvalue></defaultvalue>
			<documentation>
				<purpose>Character set used in the part text. If it is set to an
					empty string, it is assumed the character set defined by the
					<variablelink>default_charset</variablelink> variable.</purpose>
			</documentation>
		</argument>
		<do>
{/metadocument}
*/
	Function AddPlainTextPart($text,$charset="")
	{
		if(strlen($error=$this->CreatePlainTextPart($text,$charset,$part))
		|| strlen($error=$this->AddPart($part)))
			return($error);
		return("");
	}
/*
{metadocument}
		</do>
	</function>
{/metadocument}
*/

	Function CreateEncodedQuotedPrintableTextPart($text,$charset,&$part)
	{
		if(!strcmp($charset,""))
			$charset=$this->default_charset;
		$definition=array(
			"Content-Type"=>"text/plain",
			"Content-Transfer-Encoding"=>"quoted-printable",
			"CHARSET"=>$charset,
			"DATA"=>$text
		);
		return($this->CreatePart($definition,$part));
	}

	Function AddEncodedQuotedPrintableTextPart($text,$charset="")
	{
		if(strlen($error=$this->CreateEncodedQuotedPrintableTextPart($text,$charset,$part))
		|| strlen($error=$this->AddPart($part)))
			return($error);
		return("");
	}

/*
{metadocument}
	<function>
		<name>CreateQuotedPrintableTextPart</name>
		<type>STRING</type>
		<documentation>
			<purpose>Create a text message part that may contain non-ASCII
				characters (8 bits or more).</purpose>
			<usage>Pass a <argumentlink>
					<function>CreateQuotedPrintableTextPart</function>
					<argument>text</argument>
				</argumentlink> string and get the created part number in the
				<argumentlink>
					<function>CreateQuotedPrintableTextPart</function>
					<argument>part</argument>
				</argumentlink> that is returned by reference.</usage>
			<returnvalue>An error message in case there was an error or an empty
				string otherwise. This return value may be safely ignored if the
				function parameters are set correctly.</returnvalue>
		</documentation>
		<argument>
			<name>text</name>
			<type>STRING</type>
			<documentation>
				<purpose>Text of the message part to create.</purpose>
			</documentation>
		</argument>
		<argument>
			<name>charset</name>
			<type>STRING</type>
			<documentation>
				<purpose>Character set used in the part text. If it is set to an
					empty string, it is assumed the character set defined by the
					<variablelink>default_charset</variablelink> variable.</purpose>
			</documentation>
		</argument>
		<argument>
			<name>part</name>
			<type>INTEGER</type>
			<out />
			<documentation>
				<purpose>Number of the created part that is returned by reference.</purpose>
			</documentation>
		</argument>
		<do>
{/metadocument}
*/
	Function CreateQuotedPrintableTextPart($text,$charset,&$part)
	{
		return($this->CreateEncodedQuotedPrintableTextPart($this->QuotedPrintableEncode($text),$charset,$part));
	}
/*
{metadocument}
		</do>
	</function>
{/metadocument}
*/

/*
{metadocument}
	<function>
		<name>AddQuotedPrintableTextPart</name>
		<type>STRING</type>
		<documentation>
			<purpose>Add a text part to the message that may contain non-ASCII
				characters (8 bits or more).</purpose>
			<usage>Pass a <argumentlink>
					<function>AddQuotedPrintableTextPart</function>
					<argument>text</argument>
				</argumentlink> string.</usage>
			<returnvalue>An error message in case there was an error or an empty
				string otherwise. This return value may be safely ignored if the
				function parameters are set correctly.</returnvalue>
		</documentation>
		<argument>
			<name>text</name>
			<type>STRING</type>
			<documentation>
				<purpose>Text of the message part to create.</purpose>
			</documentation>
		</argument>
		<argument>
			<name>charset</name>
			<type>STRING</type>
			<defaultvalue></defaultvalue>
			<documentation>
				<purpose>Character set used in the part text. If it is set to an
					empty string, it is assumed the character set defined by the
					<variablelink>default_charset</variablelink> variable.</purpose>
			</documentation>
		</argument>
		<do>
{/metadocument}
*/
	Function AddQuotedPrintableTextPart($text,$charset="")
	{
		return($this->AddEncodedQuotedPrintableTextPart($this->QuotedPrintableEncode($text),$charset));
	}
/*
{metadocument}
		</do>
	</function>
{/metadocument}
*/

/*
{metadocument}
	<function>
		<name>CreateHTMLPart</name>
		<type>STRING</type>
		<documentation>
			<purpose>Create an HTML message part only with ASCII characters (7 bit).</purpose>
			<usage>Pass an ASCII (7 bits) <argumentlink>
					<function>CreateHTMLPart</function>
					<argument>html</argument>
				</argumentlink> text string and get the created part number in the
				<argumentlink>
					<function>CreateHTMLPart</function>
					<argument>part</argument>
				</argumentlink> that is returned by reference.</usage>
			<returnvalue>An error message in case there was an error or an empty
				string otherwise. This return value may be safely ignored if the
				function parameters are set correctly.</returnvalue>
		</documentation>
		<argument>
			<name>html</name>
			<type>STRING</type>
			<documentation>
				<purpose>HTML of the message part to create.</purpose>
			</documentation>
		</argument>
		<argument>
			<name>charset</name>
			<type>STRING</type>
			<documentation>
				<purpose>Character set used in the part text. If it is set to an
					empty string, it is assumed the character set defined by the
					<variablelink>default_charset</variablelink> variable.</purpose>
			</documentation>
		</argument>
		<argument>
			<name>part</name>
			<type>INTEGER</type>
			<out />
			<documentation>
				<purpose>Number of the created part that is returned by reference.</purpose>
			</documentation>
		</argument>
		<do>
{/metadocument}
*/
	Function CreateHTMLPart($html,$charset,&$part)
	{
		if(!strcmp($charset,""))
			$charset=$this->default_charset;
		$definition=array(
			"Content-Type"=>"text/html",
			"CHARSET"=>$charset,
			"DATA"=>$html
		);
		return($this->CreatePart($definition,$part));
	}
/*
{metadocument}
		</do>
	</function>
{/metadocument}
*/

/*
{metadocument}
	<function>
		<name>AddHTMLPart</name>
		<type>STRING</type>
		<documentation>
			<purpose>Add an HTML part to the message only with ASCII characters.</purpose>
			<usage>Pass an <argumentlink>
					<function>AddHTMLPart</function>
					<argument>html</argument>
				</argumentlink> text string.</usage>
			<returnvalue>An error message in case there was an error or an empty
				string otherwise. This return value may be safely ignored if the
				function parameters are set correctly.</returnvalue>
		</documentation>
		<argument>
			<name>html</name>
			<type>STRING</type>
			<documentation>
				<purpose>HTML of the message part to create.</purpose>
			</documentation>
		</argument>
		<argument>
			<name>charset</name>
			<type>STRING</type>
			<defaultvalue></defaultvalue>
			<documentation>
				<purpose>Character set used in the part text. If it is set to an
					empty string, it is assumed the character set defined by the
					<variablelink>default_charset</variablelink> variable.</purpose>
			</documentation>
		</argument>
		<do>
{/metadocument}
*/
	Function AddHTMLPart($html,$charset="")
	{
		if(strlen($error=$this->CreateHTMLPart($html,$charset,$part))
		|| strlen($error=$this->AddPart($part)))
			return($error);
		return("");
	}
/*
{metadocument}
		</do>
	</function>
{/metadocument}
*/

	Function CreateEncodedQuotedPrintableHTMLPart($html,$charset,&$part)
	{
		if(!strcmp($charset,""))
			$charset=$this->default_charset;
		$definition=array(
			"Content-Type"=>"text/html",
			"Content-Transfer-Encoding"=>"quoted-printable",
			"CHARSET"=>$charset,
			"DATA"=>$html
		);
		return($this->CreatePart($definition,$part));
	}

	Function AddEncodedQuotedPrintableHTMLPart($html,$charset="")
	{
		if(strlen($error=$this->CreateEncodedQuotedPrintableHTMLPart($html,$charset,$part))
		|| strlen($error=$this->AddPart($part)))
			return($error);
		return("");
	}

/*
{metadocument}
	<function>
		<name>CreateQuotedPrintableHTMLPart</name>
		<type>STRING</type>
		<documentation>
			<purpose>Create an HTML message part that may contain non-ASCII
				characters (8 bits or more).</purpose>
			<usage>Pass a <argumentlink>
					<function>CreateQuotedPrintableHTMLPart</function>
					<argument>html</argument>
				</argumentlink> text string and get the created part number in the
				<argumentlink>
					<function>CreateQuotedPrintableHTMLPart</function>
					<argument>part</argument>
				</argumentlink> that is returned by reference.</usage>
			<returnvalue>An error message in case there was an error or an empty
				string otherwise. This return value may be safely ignored if the
				function parameters are set correctly.</returnvalue>
		</documentation>
		<argument>
			<name>html</name>
			<type>STRING</type>
			<documentation>
				<purpose>HTML of the message part to create.</purpose>
			</documentation>
		</argument>
		<argument>
			<name>charset</name>
			<type>STRING</type>
			<documentation>
				<purpose>Character set used in the part text. If it is set to an
					empty string, it is assumed the character set defined by the
					<variablelink>default_charset</variablelink> variable.</purpose>
			</documentation>
		</argument>
		<argument>
			<name>part</name>
			<type>INTEGER</type>
			<out />
			<documentation>
				<purpose>Number of the created part that is returned by reference.</purpose>
			</documentation>
		</argument>
		<do>
{/metadocument}
*/
	Function CreateQuotedPrintableHTMLPart($html,$charset,&$part)
	{
		return($this->CreateEncodedQuotedPrintableHTMLPart($this->QuotedPrintableEncode($html),$charset,$part));
	}
/*
{metadocument}
		</do>
	</function>
{/metadocument}
*/


/*
{metadocument}
	<function>
		<name>AddQuotedPrintableHTMLPart</name>
		<type>STRING</type>
		<documentation>
			<purpose>Add an HTML part to the message that may contain non-ASCII
				characters (8 bits or more).</purpose>
			<usage>Pass a <argumentlink>
					<function>AddQuotedPrintableHTMLPart</function>
					<argument>html</argument>
				</argumentlink> text string.</usage>
			<returnvalue>An error message in case there was an error or an empty
				string otherwise. This return value may be safely ignored if the
				function parameters are set correctly.</returnvalue>
		</documentation>
		<argument>
			<name>html</name>
			<type>STRING</type>
			<documentation>
				<purpose>HTML of the message part to create.</purpose>
			</documentation>
		</argument>
		<argument>
			<name>charset</name>
			<type>STRING</type>
			<defaultvalue></defaultvalue>
			<documentation>
				<purpose>Character set used in the part text. If it is set to an
					empty string, it is assumed the character set defined by the
					<variablelink>default_charset</variablelink> variable.</purpose>
			</documentation>
		</argument>
		<do>
{/metadocument}
*/
	Function AddQuotedPrintableHTMLPart($html,$charset="")
	{
		return($this->AddEncodedQuotedPrintableHTMLPart($this->QuotedPrintableEncode($html),$charset));
	}
/*
{metadocument}
		</do>
	</function>
{/metadocument}
*/

	Function GetFileDefinition($file, &$definition, $require_name=1)
	{
		if(strlen($this->error))
			return($this->error);
		$name="";
		if(IsSet($file["FileName"]))
			$name=basename($file["FileName"]);
		else
		{
			if(!IsSet($file["Data"]))
				return($this->OutputError("it was not specified the file part file name"));
		}
		if(IsSet($file["Name"]))
			$name=$file["Name"];
		if($require_name
		&& strlen($name)==0)
			return($this->OutputError("it was not specified the file part name"));
		$encoding="base64";
		if(IsSet($file["Content-Type"]))
		{
			$content_type=$file["Content-Type"];
			$type=$this->Tokenize(strtolower($content_type),"/");
			$sub_type=$this->Tokenize("");
			switch($type)
			{
				case "text":
				case "image":
				case "audio":
				case "video":
				case "application":
					break;
				case "message":
					$encoding="7bit";
					break;
				case "automatic":
					switch($sub_type)
					{
						case "name":
							if(strlen($name)==0)
								return($this->OutputError("it is not possible to determine content type from the name"));
							switch(strtolower($this->GetFilenameExtension($name)))
							{
								case ".xls":
									$content_type="application/excel";
									break;
								case ".hqx":
									$content_type="application/macbinhex40";
									break;
								case ".doc":
								case ".dot":
								case ".wrd":
									$content_type="application/msword";
									break;
								case ".pdf":
									$content_type="application/pdf";
									break;
								case ".pgp":
									$content_type="application/pgp";
									break;
								case ".ps":
								case ".eps":
								case ".ai":
									$content_type="application/postscript";
									break;
								case ".ppt":
									$content_type="application/powerpoint";
									break;
								case ".rtf":
									$content_type="application/rtf";
									break;
								case ".tgz":
								case ".gtar":
									$content_type="application/x-gtar";
									break;
								case ".gz":
									$content_type="application/x-gzip";
									break;
								case ".php":
								case ".php3":
									$content_type="application/x-httpd-php";
									break;
								case ".js":
									$content_type="application/x-javascript";
									break;
								case ".ppd":
								case ".psd":
									$content_type="application/x-photoshop";
									break;
								case ".swf":
								case ".swc":
								case ".rf":
									$content_type="application/x-shockwave-flash";
									break;
								case ".tar":
									$content_type="application/x-tar";
									break;
								case ".zip":
									$content_type="application/zip";
									break;
								case ".mid":
								case ".midi":
								case ".kar":
									$content_type="audio/midi";
									break;
								case ".mp2":
								case ".mp3":
								case ".mpga":
									$content_type="audio/mpeg";
									break;
								case ".ra":
									$content_type="audio/x-realaudio";
									break;
								case ".wav":
									$content_type="audio/wav";
									break;
								case ".bmp":
									$content_type="image/bitmap";
									break;
								case ".gif":
									$content_type="image/gif";
									break;
								case ".iff":
									$content_type="image/iff";
									break;
								case ".jb2":
									$content_type="image/jb2";
									break;
								case ".jpg":
								case ".jpe":
								case ".jpeg":
									$content_type="image/jpeg";
									break;
								case ".jpx":
									$content_type="image/jpx";
									break;
								case ".png":
									$content_type="image/png";
									break;
								case ".tif":
								case ".tiff":
									$content_type="image/tiff";
									break;
								case ".wbmp":
									$content_type="image/vnd.wap.wbmp";
									break;
								case ".xbm":
									$content_type="image/xbm";
									break;
								case ".css":
									$content_type="text/css";
									break;
								case ".txt":
									$content_type="text/plain";
									break;
								case ".htm":
								case ".html":
									$content_type="text/html";
									break;
								case ".xml":
									$content_type="text/xml";
									break;
								case ".mpg":
								case ".mpe":
								case ".mpeg":
									$content_type="video/mpeg";
									break;
								case ".qt":
								case ".mov":
									$content_type="video/quicktime";
									break;
								case ".avi":
									$content_type="video/x-ms-video";
									break;
								case ".eml":
									$content_type="message/rfc822";
									$encoding="7bit";
									break;
								default:
									$content_type="application/octet-stream";
									break;
							}
							break;
						default:
							return($this->OutputError($content_type." is not a supported automatic content type detection method"));
					}
					break;
				default:
					return($this->OutputError($content_type." is not a supported file content type"));
			}
		}
		else
			$content_type="application/octet-stream";
		$definition=array(
			"Content-Type"=>$content_type,
			"Content-Transfer-Encoding"=>$encoding,
			"NAME"=>$name
		);
		if(IsSet($file["Disposition"]))
		{
			switch(strtolower($file["Disposition"]))
			{
				case "inline":
				case "attachment":
					break;
				default:
					return($this->OutputError($file["Disposition"]." is not a supported message part content disposition"));
			}
			$definition["DISPOSITION"]=$file["Disposition"];
		}
		if(IsSet($file["FileName"]))
			$definition["FILENAME"]=$file["FileName"];
		else
		{
			if(IsSet($file["Data"]))
				$definition["DATA"]=$file["Data"];
		}
		if(IsSet($file['Cache'])
		&& $file['Cache'])
			$definition['Cache'] = 1;
		return("");
	}

/*
{metadocument}
	<function>
		<name>CreateFilePart</name>
		<type>STRING</type>
		<documentation>
			<purpose>Create a message part to be handled as a file.</purpose>
			<usage>Pass a <argumentlink>
					<function>CreateFilePart</function>
					<argument>file</argument>
				</argumentlink> definition associative array and get the created
				part number in the <argumentlink>
					<function>CreateFilePart</function>
					<argument>part</argument>
				</argumentlink> that is returned by reference.</usage>
			<returnvalue>An error message in case there was an error or an empty
				string otherwise. This return value may be safely ignored if the
				function parameters are set correctly.</returnvalue>
		</documentation>
		<argument>
			<name>file</name>
			<type>HASH</type>
			<documentation>
				<purpose>Associative array to specify parameters that describe the
					file part. Here follows the list of supported parameters that
					should be used as indexes of the array:<paragraphbreak />
					<tt>FileName</tt><paragraphbreak />
					Name of the file from which the part data will be read when the
					message is generated. It may be a remote URL as long as your PHP
					installation is configured to allow accessing remote files with
					the <tt>fopen()</tt> function.<paragraphbreak />
					<tt>Data</tt><paragraphbreak />
					String that specifies the data of the file. This should be used
					as alternative data source to <tt>FileName</tt> for passing data
					available in memory, like for instance files stored in a database
					that was queried dynamically and the file contents was fetched
					into a string variable.<paragraphbreak />
					<tt>Name</tt><paragraphbreak />
					Name of the file that will appear in the message. If this
					parameter is missing the base name of the <tt>FileName</tt>
					parameter is used, if present.<paragraphbreak />
					<tt>Content-Type</tt><paragraphbreak />
					Content type of the part: <tt>text/plain</tt> for text,
					<tt>text/html</tt> for HTML, <tt>image/gif</tt> for GIF images,
					etc..<paragraphbreak />
					There is one special type named <tt>automatic/name</tt> that may
					be used to tell the class to try to guess the content type from
					the file name. Many file types are recognized from the file name
					extension. If the file name extension is not recognized, the
					default for binary data <tt>application/octet-stream</tt> is
					assumed.<paragraphbreak />
					<tt>Disposition</tt><paragraphbreak />
					Information to whether this file part is meant to be used as a
					file <tt>attachment</tt> or as a part meant to be displayed
					<tt>inline</tt>, eventually integrated with another related
					part.<paragraphbreak />
					<tt>Cache</tt><paragraphbreak />
					Boolean flag that indicates that this message part should be
					cached when generating the message body. Use only when sending
					many messages to multiple recipients, but this part does not
					change between each of the messages that are sent.<paragraphbreak />
					Note that it is also not worth using this option when setting the
					<variablelink>cache_body</variablelink>, as that variable makes
					the class cache the whole message body and the internal message
					parts will not be rebuilt.</purpose>
			</documentation>
		</argument>
		<argument>
			<name>part</name>
			<type>INTEGER</type>
			<out />
			<documentation>
				<purpose>Number of the created part that is returned by reference.</purpose>
			</documentation>
		</argument>
		<do>
{/metadocument}
*/
	Function CreateFilePart(&$file,&$part)
	{
		if(strlen($this->GetFileDefinition($file,$definition)))
			return($this->error);
		return($this->CreatePart($definition,$part));
	}
/*
{metadocument}
		</do>
	</function>
{/metadocument}
*/

/*
{metadocument}
	<function>
		<name>AddFilePart</name>
		<type>STRING</type>
		<documentation>
			<purpose>Add a message part to be handled as a file.</purpose>
			<usage>Pass a <argumentlink>
					<function>AddFilePart</function>
					<argument>file</argument>
				</argumentlink> definition associative array.</usage>
			<returnvalue>An error message in case there was an error or an empty
				string otherwise. This return value may be safely ignored if the
				function parameters are set correctly.</returnvalue>
		</documentation>
		<argument>
			<name>file</name>
			<type>HASH</type>
			<documentation>
				<purpose>Associative array to specify parameters that describe the
					file part. See the <argumentlink>
						<function>CreateFilePart</function>
						<argument>file</argument>
					</argumentlink> argument description of the
					<functionlink>CreateFilePart</functionlink> function for an
					explanation about the supported file parameters.</purpose>
			</documentation>
		</argument>
		<do>
{/metadocument}
*/
	Function AddFilePart(&$file)
	{
		if(strlen($error=$this->CreateFilePart($file,$part))
		|| strlen($error=$this->AddPart($part)))
			return($error);
		return("");
	}
/*
{metadocument}
		</do>
	</function>
{/metadocument}
*/

/*
{metadocument}
	<function>
		<name>CreateMessagePart</name>
		<type>STRING</type>
		<documentation>
			<purpose>Create a message part to encapsulate another message. This
				is usually meant to create an attachment that contains a message
				that was received and is being forwarded intact with the original
				the headers and body data.</purpose>
			<usage>This function should be used like the
				<functionlink>CreateFilePart</functionlink> function, passing the
				same parameters to the <argumentlink>
					<function>CreateMessagePart</function>
					<argument>message</argument>
				</argumentlink> argument.<paragraphbreak />
				The message to be encapsulated can be specified either as an
				existing file with the <tt>FileName</tt> parameter, or as string
				of data in memory with the <tt>Data</tt>
				parameter.<paragraphbreak />
				The <tt>Content-Type</tt> and <tt>Disposition</tt> file parameters
				do not need to be specified because they are overridden by this
				function.</usage>
			<returnvalue>An error message in case there was an error or an empty
				string otherwise. This return value may be safely ignored if the
				function parameters are set correctly.</returnvalue>
		</documentation>
		<argument>
			<name>message</name>
			<type>HASH</type>
			<documentation>
				<purpose>Associative array that specifies definition parameters of
					the message file part.</purpose>
			</documentation>
		</argument>
		<argument>
			<name>part</name>
			<type>INTEGER</type>
			<out />
			<documentation>
				<purpose>Number of the created part that is returned by reference.</purpose>
			</documentation>
		</argument>
		<do>
{/metadocument}
*/
	Function CreateMessagePart(&$message,&$part)
	{
		$message["Content-Type"]="message/rfc822";
		$message["Disposition"]="inline";
		if(strlen($this->GetFileDefinition($message,$definition)))
			return($this->error);
		return($this->CreatePart($definition,$part));
	}
/*
{metadocument}
		</do>
	</function>
{/metadocument}
*/

/*
{metadocument}
	<function>
		<name>AddMessagePart</name>
		<type>STRING</type>
		<documentation>
			<purpose>Add a message part that encapsulates another message. This
				is usually meant to add an attachment that contains a message that
				was received and is being forwarded intact with the original the
				headers and body data.</purpose>
			<usage>This function should be used like the
				<functionlink>AddFilePart</functionlink> function, passing the
				same parameters to the <argumentlink>
					<function>AddMessagePart</function>
					<argument>message</argument>
				</argumentlink> argument. See the
				<functionlink>CreateFilePart</functionlink> function for more
				details.</usage>
			<returnvalue>An error message in case there was an error or an empty
				string otherwise. This return value may be safely ignored if the
				function parameters are set correctly.</returnvalue>
		</documentation>
		<argument>
			<name>message</name>
			<type>HASH</type>
			<documentation>
				<purpose>Associative array that specifies definition parameters of
					the message file part.</purpose>
			</documentation>
		</argument>
		<do>
{/metadocument}
*/
	Function AddMessagePart(&$message)
	{
		if(strlen($error=$this->CreateMessagePart($message,$part))
		|| strlen($error=$this->AddPart($part)))
			return($error);
		return("");
	}
/*
{metadocument}
		</do>
	</function>
{/metadocument}
*/

	Function CreateMultipart(&$parts,&$part,$type)
	{
		$definition=array(
			"Content-Type"=>"multipart/".$type,
			"PARTS"=>$parts
		);
		return($this->CreatePart($definition,$part));
	}

	Function AddMultipart(&$parts,$type)
	{
		if(strlen($error=$this->CreateMultipart($parts,$part,$type))
		|| strlen($error=$this->AddPart($part)))
			return($error);
		return("");
	}

/*
{metadocument}
	<function>
		<name>CreateAlternativeMultipart</name>
		<type>STRING</type>
		<documentation>
			<purpose>Create a message part composed of multiple parts that can be
				displayed by the recipient e-mail program in alternative
				formats.<paragraphbreak />
				This is usually meant to create HTML messages with an alternative
				text part to be displayed by programs that cannot display HTML
				messages.</purpose>
			<usage>Create all the alternative message parts that are going to be
				sent and pass their numbers to the <argumentlink>
					<function>CreateAlternativeMultipart</function>
					<argument>parts</argument>
				</argumentlink> array argument.<paragraphbreak />
				The least sophisticated part, usually the text part, should appear
				first in the parts array because the e-mail programs that support
				displaying more sophisticated message parts will pick the last part
				in the message that is supported.</usage>
			<returnvalue>An error message in case there was an error or an empty
				string otherwise. This return value may be safely ignored if the
				function parameters are set correctly.</returnvalue>
		</documentation>
		<argument>
			<name>parts</name>
			<type>ARRAY</type>
			<documentation>
				<purpose>Array with the numbers with all the alternative parts.</purpose>
			</documentation>
		</argument>
		<argument>
			<name>part</name>
			<type>INTEGER</type>
			<out />
			<documentation>
				<purpose>Number of the created part that is returned by reference.</purpose>
			</documentation>
		</argument>
		<do>
{/metadocument}
*/
	Function CreateAlternativeMultipart(&$parts,&$part)
	{
		return($this->CreateMultiPart($parts,$part,"alternative"));
	}
/*
{metadocument}
		</do>
	</function>
{/metadocument}
*/

/*
{metadocument}
	<function>
		<name>AddAlternativeMultipart</name>
		<type>STRING</type>
		<documentation>
			<purpose>Add a message part composed of multiple parts that can be
				displayed by the recipient e-mail program in alternative
				formats.<paragraphbreak />
				This is usually meant to create HTML messages with an alternative
				text part to be displayed by programs that cannot display HTML
				messages.</purpose>
			<usage>Create all the alternative message parts that are going to be
				sent and pass their numbers to the <argumentlink>
					<function>AddAlternativeMultipart</function>
					<argument>parts</argument>
				</argumentlink> array argument.<paragraphbreak />
				The least sophisticated part, usually the text part, should appear
				first in the parts array because the e-mail programs that support
				displaying more sophisticated message parts will pick the last part
				in the message that is supported.</usage>
			<returnvalue>An error message in case there was an error or an empty
				string otherwise. This return value may be safely ignored if the
				function parameters are set correctly.</returnvalue>
		</documentation>
		<argument>
			<name>parts</name>
			<type>ARRAY</type>
			<documentation>
				<purpose>Array with the numbers with all the alternative parts.</purpose>
			</documentation>
		</argument>
		<do>
{/metadocument}
*/
	Function AddAlternativeMultipart(&$parts)
	{
		return($this->AddMultipart($parts,"alternative"));
	}
/*
{metadocument}
		</do>
	</function>
{/metadocument}
*/

/*
{metadocument}
	<function>
		<name>CreateRelatedMultipart</name>
		<type>STRING</type>
		<documentation>
			<purpose>Create a message part that groups several related
				parts.<paragraphbreak />
				This is usually meant to group an HTML message part with images or
				other types of files that should be embedded in the same message
				and be displayed as a single part by the recipient e-mail
				program.</purpose>
			<usage>Create all the related message parts that are going to be
				sent and pass their numbers to the <argumentlink>
					<function>CreateRelatedMultipart</function>
					<argument>parts</argument>
				</argumentlink> array argument.<paragraphbreak />
				When using this function to group an HTML message with embedded
				images or other related files, make sure that the HTML part number
				is the first listed in the <argumentlink>
					<function>CreateRelatedMultipart</function>
					<argument>parts</argument>
				</argumentlink> array argument, or else the message may not appear
				correctly.</usage>
			<returnvalue>An error message in case there was an error or an empty
				string otherwise. This return value may be safely ignored if the
				function parameters are set correctly.</returnvalue>
		</documentation>
		<argument>
			<name>parts</name>
			<type>ARRAY</type>
			<documentation>
				<purpose>Array with the numbers with all the related parts.</purpose>
			</documentation>
		</argument>
		<argument>
			<name>part</name>
			<type>INTEGER</type>
			<out />
			<documentation>
				<purpose>Number of the created part that is returned by reference.</purpose>
			</documentation>
		</argument>
		<do>
{/metadocument}
*/
	Function CreateRelatedMultipart(&$parts,&$part)
	{
		return($this->CreateMultipart($parts,$part,"related"));
	}
/*
{metadocument}
		</do>
	</function>
{/metadocument}
*/

/*
{metadocument}
	<function>
		<name>AddRelatedMultipart</name>
		<type>STRING</type>
		<documentation>
			<purpose>Add a message part that groups several related
				parts.<paragraphbreak />
				This is usually meant to group an HTML message part with images or
				other types of files that should be embedded in the same message
				and be displayed as a single part by the recipient e-mail
				program.</purpose>
			<usage>Create all the related message parts that are going to be
				sent and pass their numbers to the <argumentlink>
					<function>AddRelatedMultipart</function>
					<argument>parts</argument>
				</argumentlink> array argument.<paragraphbreak />
				When using this function to group an HTML message with embedded
				images or other related files, make sure that the HTML part number
				is the first listed in the <argumentlink>
					<function>AddRelatedMultipart</function>
					<argument>parts</argument>
				</argumentlink> array argument, or else the message may not appear
				correctly.</usage>
			<returnvalue>An error message in case there was an error or an empty
				string otherwise. This return value may be safely ignored if the
				function parameters are set correctly.</returnvalue>
		</documentation>
		<argument>
			<name>parts</name>
			<type>ARRAY</type>
			<documentation>
				<purpose>Array with the numbers with all the related parts.</purpose>
			</documentation>
		</argument>
		<do>
{/metadocument}
*/
	Function AddRelatedMultipart(&$parts)
	{
		return($this->AddMultipart($parts,"related"));
	}
/*
{metadocument}
		</do>
	</function>
{/metadocument}
*/

/*
{metadocument}
	<function>
		<name>CreateMixedMultipart</name>
		<type>STRING</type>
		<documentation>
			<purpose>Create a message part that groups several independent
				parts.<paragraphbreak />
				Usually this is meant compose messages with one or more file
				attachments. However, it is not necessary to use this function as
				the class implicitly creates a <tt>multipart/mixed</tt> message
				when more than one part is added to the message.</purpose>
			<usage>Create all the independent message parts that are going to be
				sent and pass their numbers to the <argumentlink>
					<function>CreateMixedMultipart</function>
					<argument>parts</argument>
				</argumentlink> array argument.</usage>
			<returnvalue>An error message in case there was an error or an empty
				string otherwise. This return value may be safely ignored if the
				function parameters are set correctly.</returnvalue>
		</documentation>
		<argument>
			<name>parts</name>
			<type>ARRAY</type>
			<documentation>
				<purpose>Array with the numbers with all the related parts.</purpose>
			</documentation>
		</argument>
		<argument>
			<name>part</name>
			<type>INTEGER</type>
			<out />
			<documentation>
				<purpose>Number of the created part that is returned by reference.</purpose>
			</documentation>
		</argument>
		<do>
{/metadocument}
*/
	Function CreateMixedMultipart(&$parts,&$part)
	{
		return($this->CreateMultipart($parts,$part,"mixed"));
	}
/*
{metadocument}
		</do>
	</function>
{/metadocument}
*/

/*
{metadocument}
	<function>
		<name>AddMixedMultipart</name>
		<type>STRING</type>
		<documentation>
			<purpose>Add a message part that groups several independent
				parts.<paragraphbreak />
				Usually this is meant compose messages with one or more file
				attachments. However, it is not necessary to use this function as
				the class implicitly creates a <tt>multipart/mixed</tt> message
				when more than one part is added to the message.</purpose>
			<usage>Create all the independent message parts that are going to be
				sent and pass their numbers to the <argumentlink>
					<function>AddMixedMultipart</function>
					<argument>parts</argument>
				</argumentlink> array argument.</usage>
			<returnvalue>An error message in case there was an error or an empty
				string otherwise. This return value may be safely ignored if the
				function parameters are set correctly.</returnvalue>
		</documentation>
		<argument>
			<name>parts</name>
			<type>ARRAY</type>
			<documentation>
				<purpose>Array with the numbers with all the related parts.</purpose>
			</documentation>
		</argument>
		<do>
{/metadocument}
*/
	Function AddMixedMultipart(&$parts)
	{
		return($this->AddMultipart($parts,"mixed"));
	}
/*
{metadocument}
		</do>
	</function>
{/metadocument}
*/

	Function CreateParallelMultipart(&$parts,&$part)
	{
		return($this->CreateMultipart($parts,$part,"paralell"));
	}

	Function AddParalellMultipart(&$parts)
	{
		return($this->AddMultipart($parts,"paralell"));
	}

/*
{metadocument}
	<function>
		<name>GetPartContentID</name>
		<type>STRING</type>
		<documentation>
			<purpose>Retrieve the content identifier associated to a given
				message part.</purpose>
			<usage>Create a message part and pass its number to the <argumentlink>
					<function>GetPartContentID</function>
					<argument>part</argument>
				</argumentlink> argument.<paragraphbreak />
				This function is usually meant to create an URL that can be used
				in an HTML message part to reference related parts like images, CSS
				(Cascaded Style Sheets), or any other type of files related to the
				HTML part that are embedded in the same message as part of a
				<tt>multipart/related</tt> composite part.<paragraphbreak />
				To use the part content identifier returned by this function you
				need to prepend the string <tt><stringvalue>cid:</stringvalue></tt>
				to form a special URL that can be used in the HTML document this
				part file.<paragraphbreak />
				You may read more about using this function in the class usage
				section about <link>
					<data>embedding images in HTML messages</data>
					<name>embed-image</name>
				</link>.</usage>
			<returnvalue>The content identifier text string.<paragraphbreak />
				If it is specified an invalid message part, this function returns
				an empty string.</returnvalue>
		</documentation>
		<argument>
			<name>part</name>
			<type>INTEGER</type>
			<documentation>
				<purpose>Number of the part as returned by the function that
					originally created it.</purpose>
			</documentation>
		</argument>
		<do>
{/metadocument}
*/
	Function GetPartContentID($part)
	{
		if(!IsSet($this->parts[$part]))
			return("");
		if(!IsSet($this->parts[$part]["Content-ID"]))
		{
			$extension=(IsSet($this->parts[$part]["NAME"]) ? $this->GetFilenameExtension($this->parts[$part]["NAME"]) : "");
			$this->parts[$part]["Content-ID"]=md5(uniqid($part.time())).$extension;
		}
		return($this->parts[$part]["Content-ID"]);
	}
/*
{metadocument}
		</do>
	</function>
{/metadocument}
*/

/*
{metadocument}
	<function>
		<name>GetDataURL</name>
		<type>STRING</type>
		<documentation>
			<purpose>Generate a <tt>data:</tt> URL according to the <link>
					<data>RFC 2397</data>
					<url>http://www.ietf.org/rfc/rfc2397.txt</url>
				</link> suitable for using in HTML messages to represent an image
				or other type of file on which the data is directly embedded in the
				HTML code instead of being fetched from a separate file or remote
				URL.<paragraphbreak />
				Note that not all e-mail programs are capable of displaying images
				or other types of files embedded in HTML messages this way.</purpose>
			<usage>Pass a <argumentlink>
					<function>GetDataURL</function>
					<argument>file</argument>
				</argumentlink> part definition array like for the
				<functionlink>CreateFilePart</functionlink> function.</usage>
			<returnvalue>The <tt>data:</tt> representing the described file or an
				empty string in case there was an error.</returnvalue>
		</documentation>
		<argument>
			<name>file</name>
			<type>HASH</type>
			<documentation>
				<purpose>File definition.</purpose>
			</documentation>
		</argument>
		<do>
{/metadocument}
*/
	Function GetDataURL($file)
	{
		if(strlen($this->GetFileDefinition($file,$definition,0)))
			return($this->error);
		if(IsSet($definition["FILENAME"]))
		{
			$size=@filesize($definition["FILENAME"]);
			if(!($file=@fopen($definition["FILENAME"],"rb")))
				return($this->OutputPHPError("could not open data file ".$definition["FILENAME"], $php_errormsg));
			for($body="";!feof($file);)
			{
				if(GetType($block=@fread($file,$this->file_buffer_length))!="string")
				{
					$this->OutputPHPError("could not read data file", $php_errormsg);
					fclose($file);
					return("");
				}
				$body.=$block;
			}
			fclose($file);
			if(GetType($size)=="integer"
			&& strlen($body)!=$size)
			{
				$this->OutputError("the length of the file that was read does not match the size of the part file ".$definition["FILENAME"]." due to possible data corruption");
				return("");
			}
			if(function_exists("ini_get")
			&& ini_get("magic_quotes_runtime"))
				$body=StripSlashes($body);
			$body=chunk_split(base64_encode($body), $this->line_length, $this->line_break);
		}
		else
		{
			if(!IsSet($definition["DATA"]))
			{
				$this->OutputError("it was not specified a file or data block");
				return("");
			}
			$body=chunk_split(base64_encode($definition["DATA"]), $this->line_length, $this->line_break);
		}
		return("data:".$definition["Content-Type"].";base64,".$body);
	}
/*
{metadocument}
		</do>
	</function>
{/metadocument}
*/

	Function GetHeadersAndBody(&$headers, &$body)
	{
		$headers=$this->headers;
		if(strcmp($this->mailer,""))
		{
			$headers["X-Mailer"]=$this->mailer;
			if(strlen($this->mailer_delivery))
				$headers["X-Mailer"].=' ('.$this->mailer_delivery.')';
		}
		$headers["MIME-Version"]="1.0";
		if($this->body_parts==0)
			return($this->OutputError("message has no body parts"));
		if(strlen($error=$this->GetPartHeaders($headers,$this->body)))
			return($error);
		if($this->cache_body
		&& IsSet($this->body_cache[$this->body]))
			$body=$this->body_cache[$this->body];
		else
		{
			if(strlen($error=$this->GetPartBody($body,$this->body)))
				return($error);
			if($this->cache_body)
				$this->body_cache[$this->body]=$body;
		}
		return("");
	}

/*
{metadocument}
	<function>
		<name>Send</name>
		<type>STRING</type>
		<documentation>
			<purpose>Send a composed message.</purpose>
			<usage>Use this function after you have set the necessary message
				headers and added the message body parts.</usage>
			<returnvalue>An error message in case there was an error or an empty
				string otherwise. This return value may be safely ignored if the
				function parameters are set correctly.</returnvalue>
		</documentation>
		<do>
{/metadocument}
*/
	Function Send()
	{
		if(strlen($this->error))
			return($this->error);
		if(strlen($error=$this->GetHeadersAndBody($headers, $body)))
			return($error);
		if(strcmp($error=$this->StartSendingMessage(),""))
			return($error);
		if(strlen($error=$this->SendMessageHeaders($headers))==0
		&& strlen($error=$this->SendMessageBody($body))==0)
			$error=$this->EndSendingMessage();
		$this->StopSendingMessage();
		return($error);
	}
/*
{metadocument}
		</do>
	</function>
{/metadocument}
*/

/*
{metadocument}
	<function>
		<name>GetMessage</name>
		<type>STRING</type>
		<documentation>
			<purpose>Get the whole message headers and body.</purpose>
			<usage>Use this function to retrieve the message headers and body
				without sending it.</usage>
			<returnvalue>An error message in case there was an error or an empty
				string otherwise. This return value may be safely ignored if the
				function parameters are set correctly.</returnvalue>
		</documentation>
		<argument>
			<name>message</name>
			<type>STRING</type>
			<out />
			<documentation>
				<purpose>Reference to a string variable to store the text of the
					message headers and body.</purpose>
			</documentation>
		</argument>
		<do>
{/metadocument}
*/
	Function GetMessage(&$message)
	{
		if(strlen($this->error))
			return($this->error);
		if(strlen($error=$this->GetHeadersAndBody($headers, $body)))
			return($error);
		for($message="", $h=0, Reset($headers); $h<count($headers); ++$h, Next($headers))
		{
			$name=Key($headers);
			$message.=$name.": ".$headers[$name].$this->line_break;
		}
		$message.=$this->line_break;
		$message.=$body;
		return("");
	}
/*
{metadocument}
		</do>
	</function>
{/metadocument}
*/

/*
{metadocument}
	<function>
		<name>GetMessageSize</name>
		<type>STRING</type>
		<documentation>
			<purpose>Get the size of the whole message headers and body.</purpose>
			<usage>Use this function to retrieve the size in bytes of the
				message headers and body without sending it.</usage>
			<returnvalue>An error message in case there was an error or an empty
				string otherwise. This return value may be safely ignored if the
				function parameters are set correctly.</returnvalue>
		</documentation>
		<argument>
			<name>message</name>
			<type>STRING</type>
			<out />
			<documentation>
				<purpose>Reference to an integer variable to store the size of the
					message headers and body.</purpose>
			</documentation>
		</argument>
		<do>
{/metadocument}
*/
	Function GetMessageSize(&$size)
	{
		if(strlen($error=$this->GetMessage($message)))
			return($error);
		$size=strlen($message);
		return("");
	}
/*
{metadocument}
		</do>
	</function>
{/metadocument}
*/

/*
{metadocument}
	<function>
		<name>Mail</name>
		<type>BOOLEAN</type>
		<documentation>
			<purpose>Emulate the PHP <tt>mail()</tt> function by composing and
				sending a message given the same arguments.<paragraphbreak />
				This is mostly meant to provide a solution for sending messages
				with alternative delivery methods provided by this class
				sub-classes. It uses the same arguments as the PHP <tt>mail()</tt>
				function. Developers willing to use this alternative do not need to
				change much their scripts that already use the <tt>mail()</tt>
				function.</purpose>
			<usage>Use this function passing the same arguments as to PHP
				<tt><link>
					<data>mail()</data>
					<url>http://www.php.net/manual/en/function.mail.php</url>
				</link></tt> function.</usage>
			<returnvalue>If this function succeeds, it returns
				<tt><booleanvalue>1</booleanvalue></tt>.</returnvalue>
		</documentation>
		<argument>
			<name>to</name>
			<type>STRING</type>
			<documentation>
				<purpose>Recipient e-mail address.</purpose>
			</documentation>
		</argument>
		<argument>
			<name>subject</name>
			<type>STRING</type>
			<documentation>
				<purpose>Message subject.</purpose>
			</documentation>
		</argument>
		<argument>
			<name>message</name>
			<type>STRING</type>
			<documentation>
				<purpose>Message body.</purpose>
			</documentation>
		</argument>
		<argument>
			<name>additional_headers</name>
			<type>STRING</type>
			<defaultvalue></defaultvalue>
			<documentation>
				<purpose>Text string headers and the respective values. There
					should be one header and value per line with line breaks
					separating each line.</purpose>
			</documentation>
		</argument>
		<argument>
			<name>additional_parameters</name>
			<type>STRING</type>
			<defaultvalue></defaultvalue>
			<documentation>
				<purpose>Text string with additional parameters. In the original
					PHP <tt>mail()</tt> function these were actual switches to be
					passed in the sendmail program invocation command line. This
					function only supports the <tt>-f</tt> switch followed by an
					e-mail address meant to specify the message bounce return path
					address.</purpose>
			</documentation>
		</argument>
		<do>
{/metadocument}
*/
	Function Mail($to, $subject, $message, $additional_headers="", $additional_parameters="")
	{
		$this->ResetMessage();
		$this->headers=array("To"=>$to,"Subject"=>$subject);
		$content_type="";
		while(strlen($additional_headers))
		{
			preg_match("/([^\r\n]+)(\r?\n)?(.*)\$/",$additional_headers,$matches);
			$header=$matches[1];
			$additional_headers=$matches[3];
			if(!preg_match("/^([^:]+):[ \t]+(.+)\$/",$header,$matches))
			{
				$this->error="invalid header \"$header\"";
				return(0);
			}
			if(strtolower($matches[1])=="content-type")
			{
				if(strlen($content_type))
				{
					$this->error="the content-type header was specified more than once.";
					return(0);
				}
				$content_type=$matches[2];
			}
			else
				$this->SetHeader($matches[1],$matches[2]);
		}
		if(strlen($additional_parameters))
		{
			if(preg_match("/^[ \t]*-f[ \t]*([^@]+@[^ \t]+)[ \t]*(.*)\$/", $additional_parameters, $matches))
			{
				if(!preg_match('/'.str_replace('/', '\\/', $this->email_regular_expression).'/i', $matches[1]))
				{
					$this->error="it was specified an invalid e-mail address for the additional parameter -f";
					return(0);
				}
				if(strlen($matches[2]))
				{
					$this->error="it were specified some additional parameters after -f e-mail address parameter that are not supported";
					return(0);
				}
				$this->SetHeader("Return-Path",$matches[1]);
			}
			else
			{
				$this->error="the additional parameters that were specified are not supported";
				return(0);
			}
		}
		if(strlen($content_type)==0)
			$content_type="text/plain";
		$definition=array(
			"Content-Type"=>$content_type,
			"DATA"=>$message
		);
		$this->CreateAndAddPart($definition);
		$this->Send();
		return(strlen($this->error)==0);
	}
/*
{metadocument}
		</do>
	</function>
{/metadocument}
*/

	Function ChangeBulkMail($on)
	{
		return(1);
	}

/*
{metadocument}
	<function>
		<name>SetBulkMail</name>
		<type>BOOLEAN</type>
		<documentation>
			<purpose>Hint the class to adjust itself in order to send individual
				messages to many recipients more efficiently.</purpose>
			<usage>Call this function before starting sending messages to many
				recipients passing <booleanvalue>1</booleanvalue> to the
				<argumentlink>
					<function>SetBulkMail</function>
					<argument>on</argument>
				</argumentlink> argument. Then call this function again after the
				bulk mailing delivery has ended passing passing
				<booleanvalue>1</booleanvalue> to the <argumentlink>
					<function>SetBulkMail</function>
					<argument>on</argument>
				</argumentlink> argument.</usage>
			<returnvalue>If this function succeeds, it returns
				<tt><booleanvalue>1</booleanvalue></tt>.</returnvalue>
		</documentation>
		<argument>
			<name>on</name>
			<type>BOOLEAN</type>
			<documentation>
				<purpose>Boolean flag that indicates whether a bulk delivery is
					going to start if set to <booleanvalue>1</booleanvalue> or that
					the bulk delivery has ended if set to
					<booleanvalue>0</booleanvalue>.</purpose>
			</documentation>
		</argument>
		<do>
{/metadocument}
*/
	Function SetBulkMail($on)
	{
		if(strlen($this->error))
			return(0);
		if(!$this->bulk_mail==!$on)
			return(1);
		if(!$this->ChangeBulkMail($on))
			return(0);
		$this->bulk_mail=!!$on;
		return(1);
	}
/*
{metadocument}
		</do>
	</function>
{/metadocument}
*/

	Function OpenMailing(&$mailing,&$mailing_properties)
	{
		if(strlen($this->error))
			return($this->error);
		if(!IsSet($mailing_properties["Name"])
		|| strlen($mailing_properties["Name"])==0)
			return($this->OutputError("it was not specified a valid mailing Name"));
		if(!IsSet($mailing_properties["Return-Path"])
		|| strlen($mailing_properties["Return-Path"])==0)
			return($this->OutputError("it was not specified a valid mailing Return-Path"));
		$separator="";
		$directory_separator=(defined("DIRECTORY_SEPARATOR") ? DIRECTORY_SEPARATOR : ((defined("PHP_OS") && !strcmp(substr(PHP_OS,0,3),"WIN")) ? "\\" : "/"));
		$length=strlen($this->mailing_path);
		if($length)
		{
			if($this->mailing_path[$length-1]!=$directory_separator)
				$separator=$directory_separator;
		}
		$base_path=$this->mailing_path.$separator.$mailing_properties["Name"];
		if($this->body_parts==0)
			return($this->OutputError("message has no body parts"));
		$line_break="\n";
		$headers=$this->headers;
		if(strlen($this->mailer))
			$headers["X-Mailer"]=$this->mailer;
		$headers["MIME-Version"]="1.0";
		if(strlen($error=$this->GetPartHeaders($headers,$this->body)))
			return($error);
		if(!($header_file=@fopen($base_path.".h","wb")))
			return($this->OutputPHPError("could not open mailing headers file ".$base_path.".h", $php_errormsg));
		for($header=0,Reset($headers);$header<count($headers);Next($headers),++$header)
		{
			$header_name=Key($headers);
			if(!@fwrite($header_file,$header_name.": ".$headers[$header_name].$line_break))
			{
				fclose($header_file);
				return($this->OutputPHPError("could not write to the mailing headers file ".$base_path.".h", $php_errormsg));
			}
		}
		if(!@fflush($header_file))
		{
			fclose($header_file);
			@unlink($base_path.".h");
			return($this->OutputPHPError("could not write to the mailing headers file ".$base_path.".h", $php_errormsg));
		}
		fclose($header_file);
		if(strlen($error=$this->GetPartBody($body,$this->body)))
		{
			@unlink($base_path.".h");
			return($error);
		}
		if(!($body_file=@fopen($base_path.".b","wb")))
		{
			@unlink($base_path.".h");
			return($this->OutputPHPError("could not open mailing body file ".$base_path.".b", $php_errormsg));
		}
		if(!@fwrite($body_file,$body)
		|| !@fflush($body_file))
		{
			fclose($body_file);
			@unlink($base_path.".b");
			@unlink($base_path.".h");
			return($this->OutputPHPError("could not write to the mailing body file ".$base_path.".b", $php_errormsg));
		}
		fclose($body_file);
		if(!($envelope=@fopen($base_path.".e","wb")))
		{
			@unlink($base_path.".b");
			@unlink($base_path.".h");
			return($this->OutputPHPError("could not open mailing envelope file ".$base_path.".e", $php_errormsg));
		}
		if(!@fwrite($envelope,"F".$mailing_properties["Return-Path"].chr(0))
		|| !@fflush($envelope))
		{
			@fclose($envelope);
			@unlink($base_path.".e");
			@unlink($base_path.".b");
			@unlink($base_path.".h");
			return($this->OutputPHPError("could not write to the return path to the mailing envelope file ".$base_path.".e", $php_errormsg));
		}
		$mailing=++$this->last_mailing;
		$this->mailings[$mailing]=array(
			"Envelope"=>$envelope,
			"BasePath"=>$base_path
		);
		return("");
	}

	Function AddMailingRecipient($mailing,&$recipient_properties)
	{
		if(strlen($this->error))
			return($this->error);
		if(!IsSet($this->mailings[$mailing]))
			return($this->OutputError("it was not specified a valid mailing"));
		if(!IsSet($recipient_properties["Address"])
		|| strlen($recipient_properties["Address"])==0)
			return($this->OutputError("it was not specified a valid mailing recipient Address"));
		if(!@fwrite($this->mailings[$mailing]["Envelope"],"T".$recipient_properties["Address"].chr(0)))
			return($this->OutputPHPError("could not write recipient address to the mailing envelope file", $php_errormsg));
		return("");
	}

	Function EndMailing($mailing)
	{
		if(strlen($this->error))
			return($this->error);
		if(!IsSet($this->mailings[$mailing]))
			return($this->OutputError("it was not specified a valid mailing"));
		if(!IsSet($this->mailings[$mailing]["Envelope"]))
			return($this->OutputError("the mailing was already ended"));
		if(!@fwrite($this->mailings[$mailing]["Envelope"],chr(0))
		|| !@fflush($this->mailings[$mailing]["Envelope"]))
			return($this->OutputPHPError("could not end writing to the mailing envelope file", $php_errormsg));
		fclose($this->mailings[$mailing]["Envelope"]);
		Unset($this->mailings[$mailing]["Envelope"]);
		return("");
	}

	Function SendMailing($mailing)
	{
		if(strlen($this->error))
			return($this->error);
		if(!IsSet($this->mailings[$mailing]))
			return($this->OutputError("it was not specified a valid mailing"));
		if(IsSet($this->mailings[$mailing]["Envelope"]))
			return($this->OutputError("the mailing was not yet ended"));
		$this->ResetMessage();
		$base_path=$this->mailings[$mailing]["BasePath"];
		if(GetType($header_lines=@File($base_path.".h"))!="array")
			return($this->OutputPHPError("could not read the mailing headers file ".$base_path.".h", $php_errormsg));
		for($line=0;$line<count($header_lines);++$line)
		{
			$header_name=$this->Tokenize($header_lines[$line],": ");
			$this->headers[$header_name]=trim($this->Tokenize("\n"));
		}
		if(!($envelope_file=@fopen($base_path.".e","rb")))
			return($this->OutputPHPError("could not open the mailing envelope file ".$base_path.".e", $php_errormsg));
		for($bcc=$data="",$position=0;!feof($envelope_file) || strlen($data);)
		{
			if(GetType($break=strpos($data,chr(0),$position))!="integer")
			{
				if(GetType($chunk=@fread($envelope_file,$this->file_buffer_length))!="string")
				{
					fclose($envelope_file);
					return($this->OutputPHPError("could not read the mailing envelop file ".$base_path.".e", $php_errormsg));
				}
				$data=substr($data,$position).$chunk;
				$position=0;
				continue;
			}
			if($break==$position)
				break;
			switch($data[$position])
			{
				case "F":
					$this->headers["Return-Path"]=substr($data,$position+1,$break-$position-1);
					break;
				case "T":
					$bcc.=(strlen($bcc)==0 ? "" : ", ").substr($data,$position+1,$break-$position-1);
					break;
				default:
					return($this->OutputError("invalid mailing envelope file ".$base_path.".e"));
			}
			$position=$break+1;
		}
		fclose($envelope_file);
		if(strlen($bcc)==0)
			return($this->OutputError("the mailing envelop file ".$base_path.".e does not contain any recipients"));
		$this->headers["Bcc"]=$bcc;
		if(!($body_file=@fopen($base_path.".b","rb")))
			return($this->OutputPHPError("could not open the mailing body file ".$base_path.".b", $php_errormsg));
		for($data="";!feof($body_file);)
		{
			if(GetType($chunk=@fread($body_file,$this->file_buffer_length))!="string")
			{
				fclose($body_file);
				return($this->OutputPHPError("could not read the mailing body file ".$base_path.".b", $php_errormsg));
			}
			$data.=$chunk;
		}
		fclose($body_file);
		if(strlen($error=$this->StartSendingMessage()))
			return($error);
		if(strlen($error=$this->SendMessageHeaders($this->headers))==0
		&& strlen($error=$this->SendMessageBody($data))==0)
			$error=$this->EndSendingMessage();
		$this->StopSendingMessage();
		return($error);
	}
};

/*

{metadocument}
</class>
{/metadocument}

*/

?>
