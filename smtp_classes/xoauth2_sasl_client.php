<?php
/*
 * xoauth2_sasl_client.php
 *
 * @(#) $Id: xoauth2_sasl_client.php,v 1.7 2022/10/29 09:38:56 mlemos Exp $
 *
 */

define('SASL_XOAUTH2_STATE_START',             0);
define('SASL_XOAUTH2_STATE_AUTHORIZATION',     1);
define('SASL_XOAUTH2_STATE_DONE',              2);

class xoauth2_sasl_client_class
{
	var $credentials=array();
	var $state=SASL_XOAUTH2_STATE_START;

	Function GetOAuthTokenResponse($token)
	{
		return 'user='.$this->credentials['user'].chr(1).'auth=Bearer '.$token.chr(1).chr(1);
	}

	Function Initialize(&$client)
	{
		return(1);
	}

	Function Start(&$client, &$message, &$interactions)
	{
		if($this->state!=SASL_XOAUTH2_STATE_START)
		{
			$client->error='XOAUTH2 authentication state is not at the start';
			return(SASL_FAIL);
		}
		$this->credentials=array(
			'user'=>'',
			'token_file'=>'',
			'token'=>'',
			'authentication_server'=>'',
			'authentication_redirect_uri'=>'',
			'authentication_scope'=>'',
			'authentication_client_id'=>'',
			'authentication_client_secret'=>'',
			'debug'=>false,
		);
		$optional_credentials=array(
			'token_file'=>'',
			'token'=>'',
			'authentication_server'=>'',
			'authentication_redirect_uri'=>'',
			'authentication_scope'=>'',
			'authentication_client_id'=>'',
			'authentication_client_secret'=>'',
			'debug'=>false,
		);
		$defaults=array();
		$status=$client->GetCredentials($this->credentials,$defaults,$interactions, $optional_credentials);
		if($status !== SASL_CONTINUE)
			return(SASL_FAIL);
		$this->state=SASL_XOAUTH2_STATE_AUTHORIZATION;
		if($this->credentials['token'] !== '')
		{
			$message = $this->GetOAuthTokenResponse($this->credentials['token']);
		}
		elseif($this->credentials['token_file'] !== '')
		{
			if($this->credentials['authentication_server'] === '')
			{
				$client->error='the XOAUTH2 authentication_server credential is missing';
				return(SASL_FAIL);
			}
			if($this->credentials['authentication_redirect_uri'] === '')
			{
				$client->error='the XOAUTH2 authentication_redirect_uri credential is missing';
				return(SASL_FAIL);
			}
			if($this->credentials['authentication_client_id'] === '')
			{
				$client->error='the XOAUTH2 authentication_client_id credential is missing';
				return(SASL_FAIL);
			}
			if($this->credentials['authentication_client_secret'] === '')
			{
				$client->error='the XOAUTH2 authentication_client_secret credential is missing';
				return(SASL_FAIL);
			}
			$oauth = new file_oauth_client_class;
			$oauth->server = $this->credentials['authentication_server'];
			$oauth->file = array(
				'name'=>$this->credentials['token_file']
			
			);
			$oauth->offline = true;
			$oauth->debug = $this->credentials['debug'];
			$oauth->debug_http = true;
			$oauth->redirect_uri = $this->credentials['authentication_redirect_uri'];

			$oauth->client_id = $this->credentials['authentication_client_id']; $application_line = __LINE__;
			$oauth->client_secret = $this->credentials['authentication_client_secret'];
			
			$oauth->scope = $this->credentials['authentication_scope'];
			if(($success = $oauth->Initialize()))
			{
				if(($success = $oauth->Process()))
				{
					if(strlen($oauth->authorization_error))
					{
						$oauth->error = $oauth->authorization_error;
						$success = false;
					}
					elseif(strlen($oauth->access_token))
					{
						$now = gmstrftime('%Y-%m-%d %H:%M:%S', time());
						if(strcmp($now, $oauth->access_token_expiry) > 0)
						{
							error_log('The OAuth token has expired.');
							switch($oauth->server)
							{
								case 'Google':
									$success = $oauth->CallAPI(
										'https://www.googleapis.com/oauth2/v1/userinfo',
										'GET', array(), array('FailOnAccessError'=>true), $user);
									break;
								default:
									$client->error='the '.__CLASS__.' class does not yet support the OAuth server '.$oauth->server.'. Please contact Manuel Lemos by sending email mlemos@acm.org to request the improvement of this class to make it support the OAuth server '.$oauth->server;
									return(SASL_FAIL);
									
							}
							if(!$success)
							{
								$client->error='the OAuth server '.$oauth->server.' failed to access the user account: '.$client->error;
								return(SASL_FAIL);
							}
						}
					}
				}
				$success = $oauth->Finalize($success);
			}
			if($oauth->exit)
			{
				$client->error='it was not possible to use the OAuth token file with XOAUTH2 authentication because the token file needs to be initialized using a Web script page';
				return(SASL_FAIL);
			}
			$message = $this->GetOAuthTokenResponse($oauth->access_token);
		}
		else
		{
			$client->error='the XOAUTH2 authentication token or the token file credentials are missing';
			return(SASL_FAIL);
		}
		return($status);
	}

	Function Step(&$client, $response, &$message, &$interactions)
	{
		switch($this->state)
		{
			case SASL_XOAUTH2_STATE_AUTHORIZATION:
				if($response === '')
					return SASL_OK;
				$oauth_response = @json_decode($response);
				if(GetType($oauth_response) !== 'object')
					$client->error = 'the OAuth token authorization response is not valid: '.strlen($response);
				else
					$client->error = 'OAuth token authorization failed with status code '.$oauth_response->status;
				return SASL_FAIL;
			case SASL_XOAUTH2_STATE_DONE:
				if($response !== '')
				{
					$client->error='XOAUTH2 authentication was finished without success with response: '.$response;
					return(SASL_FAIL);
				}
			default:
				$client->error='invalid XOAUTH2 authentication step state';
				return(SASL_FAIL);
		}
		return(SASL_CONTINUE);
	}
};

?>