<?php
/*******************************************************************************
  *  WireMailSmtp | WireMailSmtpConfig
  * ---------------------------------------------------------------------------
  *  @version     -   '0.4.1'
  *  @author      -   Horst Nogajski
  *  @licence     -   GNU GPL v2 - http://www.gnu.org/licenses/gpl-2.0.html
*******************************************************************************/



class WireMailSmtpConfig extends Wire {

    public function getConfig(array $data) {

        // check that they have the required PW version
        if(version_compare(wire('config')->version, '2.4.1', '<')) {
            $this->error(' requires ProcessWire 2.4.1 or newer. Please update.');
        }

        $siteconfig = is_array(wire('config')->wiremailsmtp) ? wire('config')->wiremailsmtp : array();
        $modules = wire('modules');
        $form = new InputfieldWrapper();

        // LOCALHOST
        $field = $modules->get('InputfieldText');
        $field->attr('name', 'localhost');
        $field->attr('value', $data['localhost']);
        $field->label = $this->_('Local Hostname');
        $field->description = $this->_('Hostname of this computer');
        if(isset($siteconfig['localhost'])) {
            $field->notes = $this->attentionMessage($siteconfig['localhost']);
            $field->attr('tabindex', '-1');
        } else {
            $field->required = true;
        }
        $field->icon = 'desktop';
        $form->add($field);

        // WRAPPER SMTP SERVER
        $fieldset = $modules->get('InputfieldFieldset');
        $fieldset->label = $this->_('SMTP Server');
        $fieldset->attr('name', '_smtp');
        $fieldset->collapsed = Inputfield::collapsedNo;
        $fieldset->icon = 'server';

            // SMTP HOST
            $field = $modules->get('InputfieldText');
            $field->attr('name', 'smtp_host');
            $field->attr('value', $data['smtp_host']);
            $field->label = $this->_('SMTP Hostname');
            $field->description = $this->_('Set to the host name of the SMTP server to which you want to relay the messages');
            $field->columnWidth = 50;
            if(isset($siteconfig['smtp_host'])) {
                $field->notes = $this->attentionMessage($siteconfig['smtp_host']);
                $field->attr('tabindex', '-1');
            } else {
                $field->required = true;
            }
            $field->icon = 'server';
            $fieldset->add($field);

            // SMTP PORT
            $field = $modules->get('InputfieldInteger');
            $field->attr('name', 'smtp_port');
            $field->attr('value', $data['smtp_port']);
            $field->label = $this->_('SMTP Port');
            $field->description = $this->_('Set to the TCP port of the SMTP server host to connect');
            if(isset($siteconfig['smtp_port'])) {
                $field->notes = $this->attentionMessage($siteconfig['smtp_port']);
                $field->attr('tabindex', '-1');
            } else {
                $field->notes = $this->_("default: 25\ndefault for TLS / SSL: 587 or 465");
                $field->required = true;
            }
            $field->columnWidth = 50;
            $field->icon = 'sign-out';
            $fieldset->add($field);

            // SMTP USER
            $field = $modules->get('InputfieldText');
            $field->attr('name', 'smtp_user');
            $field->attr('value', $data['smtp_user']);
            $field->label = $this->_('SMTP User');
            $field->description = $this->_('Set this variable to the user name when the SMTP server requires authentication');
            if(isset($siteconfig['smtp_user'])) {
                $field->notes = $this->attentionMessage($siteconfig['smtp_user']);
                $field->attr('tabindex', '-1');
            }
            $field->columnWidth = 50;
            $field->icon = 'user';
            $fieldset->add($field);

            // SMTP PASSWORD
            $field = $modules->get('InputfieldText');
            $field->attr('name', 'smtp_password');
            $field->attr('value', $data['smtp_password']);
            $field->attr('type', 'password');
            $field->label = $this->_('SMTP Password');
            $field->description = $this->_('Set this variable to the user password when the SMTP server requires authentication');
            if(isset($siteconfig['smtp_password'])) {
                $field->notes = $this->attentionMessage('*******');
                $field->attr('tabindex', '-1');
            } else {
                $field->notes = $this->_('**Note**: Password is stored as plain text in database.');
            }
            $field->columnWidth = 50;
            $field->icon = 'asterisk';
            $fieldset->add($field);

            // SMTP STARTTLS
            $field = $modules->get('InputfieldCheckbox');
            $field->attr('name', 'smtp_start_tls');
            $field->attr('id', 'smtp_start_tls');
            $field->attr('value', 1);
            $field->attr('checked', $data['smtp_start_tls'] ? 'checked' : '');
            $field->columnWidth = 50;
            $field->label = $this->_('Use START-TLS');
            $field->description = $this->_('Check if the connection to the SMTP server should use encryption after the connection is established using TLS protocol');
            if(isset($siteconfig['smtp_start_tls'])) {
                $field->notes = $this->attentionMessage($siteconfig['smtp_start_tls']);
                $field->attr('tabindex', '-1');
            }
            $field->icon = 'lock';
            $field->showOnlyIf = 'smtp_ssl=0';
            $fieldset->add($field);

            // TLS crypto method
            $field = $modules->get('InputfieldSelect');
            $field->attr('name', 'smtp_tls_crypto_method');
            $field->attr('value', $data['smtp_tls_crypto_method']);
            $field->label = $this->_('Select a crypto method for TLS');
                $availableTLSmethods = WireMailSmtp::getCryptoMethodsTLS();
                array_unshift($availableTLSmethods, array(''));
                $field->addOptions($availableTLSmethods);
            $field->description = $this->_("Select the crypto method that should be used for TLS connections. If you don't know what to select, try with the highest and strongest entry first!");
            if(isset($siteconfig['smtp_tls_crypto_method'])) {
                $field->notes = $this->attentionMessage($siteconfig['smtp_tls_crypto_method']);
                $field->attr('tabindex', '-1');
            }
            $field->columnWidth = 50;
            $field->icon = 'lock';
            $fieldset->add($field);

            // SMTP SSL
            $field = $modules->get('InputfieldCheckbox');
            $field->attr('name', 'smtp_ssl');
            $field->attr('id', 'smtp_ssl');
            $field->attr('value', 1);
            $field->attr('checked', $data['smtp_ssl'] ? 'checked' : '');
            $field->columnWidth = 100;
            $field->label = $this->_('Use SSL');
            $field->description = $this->_('Check if the SMTP server requires secure connections using SSL protocol');
            if(isset($siteconfig['smtp_ssl'])) {
                $field->notes = $this->attentionMessage($siteconfig['smtp_ssl']);
                $field->attr('tabindex', '-1');
            }
            $field->icon = 'lock';
            $field->showOnlyIf = 'smtp_start_tls=0';
            $fieldset->add($field);
            // SSL crypto method
//            $field = $modules->get('InputfieldSelect');
//            $field->attr('name', 'smtp_ssl_crypto_method');
//            $field->attr('value', $data['smtp_ssl_crypto_method']);
//            $field->label = $this->_('Select a crypto method for SSL');
//                $availableSSLmethods = WireMailSmtp::getCryptoMethodsSSL();
//                array_unshift($availableSSLmethods, array(''));
//                $field->addOptions($availableSSLmethods);
//            $field->description = $this->_("Select the crypto method that should be used for SSL connections. If you don't know what to select, try with the highest and strongest entry first!");
//            if(isset($siteconfig['smtp_ssl_crypto_method'])) {
//                $field->notes = $this->attentionMessage($siteconfig['smtp_ssl_crypto_method']);
//                $field->attr('tabindex', '-1');
//            }
//            $field->columnWidth = 50;
//            $field->icon = 'lock';
//            $fieldset->add($field);

            // SMTP CERTIFICATE
            // @flydev: https://processwire.com/talk/topic/5704-wiremailsmtp/page-5#entry113290
            if(version_compare(phpversion(), '5.6.0', '>=')) {
                $field = $modules->get('InputfieldCheckbox');
                $field->attr('name', 'smtp_certificate');
                $field->label = $this->_('PHP >= 5.6 - Allow self signed certificate');
                $field->attr('value', $data['smtp_certificate']);
                $field->attr('checked', $data['smtp_certificate'] ? 'checked' : '');
                $field->columnWidth = 100;
                $field->icon = 'certificate';
                $fieldset->add($field);
            }

            // WRAPPER ADVANCED SMTP SETTINGS
            $fieldset2 = $modules->get('InputfieldFieldset');
            $fieldset2->label = $this->_('Advanced SMTP');
            $fieldset2->attr('name', '_advanced_SMTP');
            $fieldset2->collapsed = Inputfield::collapsedYes;
            $fieldset2->icon = 'cogs';

                // AUTHENTICATION MECHANISM
                $field = $modules->get('InputfieldText');
                $field->attr('name', 'authentication_mechanism');
                $field->attr('value', $data['authentication_mechanism']);
                $field->label = $this->_('Authentication Mechanism');
                $field->description = $this->_('Force the use of a specific authentication mechanism.');
                if(isset($siteconfig['authentication_mechanism'])) {
                    $field->notes = $this->attentionMessage($siteconfig['authentication_mechanism']);
                    $field->attr('tabindex', '-1');
                } else {
                    $field->notes = $this->_('Default: empty');
                }
                $field->columnWidth = 33;
                $field->icon = 'unlock';
                $fieldset2->add($field);

                // REALM
                $field = $modules->get('InputfieldText');
                $field->attr('name', 'realm');
                $field->attr('value', $data['realm']);
                $field->label = $this->_('Realm');
                $field->description = $this->_('Set this variable when the SMTP server requires authentication and if more than one authentication realm is supported');
                if(isset($siteconfig['realm'])) {
                    $field->notes = $this->attentionMessage($siteconfig['realm']);
                    $field->attr('tabindex', '-1');
                } else {
                    $field->notes = $this->_('Default: empty');
                }
                $field->columnWidth = 34;
                $field->icon = 'map-signs';
                $fieldset2->add($field);

                // WORKSTATION
                $field = $modules->get('InputfieldText');
                $field->attr('name', 'workstation');
                $field->attr('value', $data['workstation']);
                $field->label = $this->_('Workstation');
                $field->description = $this->_('Set this variable to the client workstation when the SMTP server requires authentication identifiying the origin workstation name');
                if(isset($siteconfig['workstation'])) {
                    $field->notes = $this->attentionMessage($siteconfig['workstation']);
                    $field->attr('tabindex', '-1');
                } else {
                    $field->notes = $this->_('Default: empty');
                }
                $field->columnWidth = 33;
                $field->icon = 'building';
                $fieldset2->add($field);

            $fieldset->add($fieldset2);

        $form->add($fieldset);


        // WRAPPER SENDER
        $fieldset = $modules->get('InputfieldFieldset');
        $fieldset->label = $this->_('Sender');
        $fieldset->attr('name', '_sender');
        $fieldset->collapsed = Inputfield::collapsedNo;
        $fieldset->icon = 'address-book';

            // SENDER EMAIL
            $field = $modules->get('InputfieldText');
            $field->attr('name', 'sender_email');
            $field->attr('value', $data['sender_email']);
            $field->label = $this->_('Sender Email Address');
            if(isset($siteconfig['sender_email'])) {
                $field->notes = $this->attentionMessage($siteconfig['sender_email']);
                $field->attr('tabindex', '-1');
            }
            $field->columnWidth = 50;
            $field->icon = 'at';
            $fieldset->add($field);

            // SENDER NAME
            $field = $modules->get('InputfieldText');
            $field->attr('name', 'sender_name');
            $field->attr('value', $data['sender_name']);
            $field->label = $this->_('Sender Name');
            if(isset($siteconfig['sender_name'])) {
                $field->notes = $this->attentionMessage($siteconfig['sender_name']);
                $field->attr('tabindex', '-1');
            }
            $field->columnWidth = 50;
            $field->icon = 'user';
            $fieldset->add($field);

            // SENDER REPLY EMAIL
            $field = $modules->get('InputfieldText');
            $field->attr('name', 'sender_reply');
            $field->attr('value', $data['sender_reply']);
            $field->label = $this->_('Reply Email Address');
            $field->description = $this->_('if is empty, Sender Emailaddress is used');
            if(isset($siteconfig['sender_reply'])) {
                $field->notes = $this->attentionMessage($siteconfig['sender_reply']);
                $field->attr('tabindex', '-1');
            }
            $field->columnWidth = 50;
            $field->collapsed = Inputfield::collapsedYes;
            $field->icon = 'mail-reply';
            $fieldset->add($field);

            // SENDER ERROR EMAIL
            $field = $modules->get('InputfieldText');
            $field->attr('name', 'sender_errors_to');
            $field->attr('value', $data['sender_errors_to']);
            $field->label = $this->_('Errors Email Address');
            if(isset($siteconfig['sender_errors_to'])) {
                $field->notes = $this->attentionMessage($siteconfig['sender_errors_to']);
                $field->attr('tabindex', '-1');
            } else {
                $field->notes = $this->_('Default: empty');
            }
            $field->columnWidth = 50;
            $field->collapsed = Inputfield::collapsedYes;
            $field->icon = 'remove';
            $fieldset->add($field);

            // SENDER SIGNATURE (TEXT)
            $field = $modules->get('InputfieldTextarea');
            $field->attr('name', 'sender_signature');
            $field->attr('value', $data['sender_signature']);
            $field->label = $this->_('Sender Signature (Plain Text)');
            $field->description = $this->_('Like Contact Data and / or Confidentiality Notices');
            if(isset($siteconfig['sender_signature'])) {
                $field->notes = $this->attentionMessage('***');
                $field->attr('tabindex', '-1');
            }
            $field->columnWidth = 33;
            $field->icon = 'pencil';
            $fieldset->add($field);

            // SENDER SIGNATURE (HTML)
            $field = $modules->get('InputfieldTextarea');
            $field->attr('name', 'sender_signature_html');
            $field->attr('value', $data['sender_signature_html']);
            $field->label = $this->_('Sender Signature (HTML)');
            $field->description = $this->_('Like Contact Data and / or Confidentiality Notices');
            if(isset($siteconfig['sender_signature_html'])) {
                $field->notes = $this->attentionMessage('***');
                $field->attr('tabindex', '-1');
            }
            $field->columnWidth = 33;
            $field->icon = 'code';
            $fieldset->add($field);

            // SENDER SEND SIGNATURE
            $field = $modules->get('InputfieldSelect');
            $field->attr('name', 'send_sender_signature');
            $field->attr('value', $data['send_sender_signature']);
            $field->label = $this->_('Send Sender Signature');
                $field->addOptions(array(
                        '1' => 'only when explicitly called via API',
                        '2' => 'automaticaly when FROM = Sender Emailaddress',
                        '3' => 'automaticaly with _every_ Message'
                ));
            $field->description = $this->_('When the Signature should be send by default, (could be overriden by the API)');
            $field->columnWidth = 34;
            $field->icon = 'question';
            $fieldset->add($field);

        $form->add($fieldset);

        // WRAPPER ADVANCED SETTINGS
        $fieldset = $modules->get('InputfieldFieldset');
        $fieldset->label = $this->_('Advanced');
        $fieldset->attr('name', '_advanced');
        $fieldset->collapsed = Inputfield::collapsedYes;
        $fieldset->icon = 'gear';

            // VALID RECIPIENTS
            $field = $modules->get('InputfieldTextarea');
            $field->attr('name', 'valid_recipients');
            $field->attr('value', $data['valid_recipients']);
            $field->label = $this->_('Valid Recipients');
            $field->description = $this->_('List of email addresses that can receive messages.');
            if(isset($siteconfig['valid_recipients'])) {
                $aTemp = array();
                foreach($siteconfig['valid_recipients'] as $k => $v) $aTemp[] = "{$k} : {$v}";
                $field->notes = $this->attentionMessage("\n" . implode("\n", $aTemp) . "\n");
                $field->attr('tabindex', '-1');
                unset($k, $v, $aTemp);
            } else {
                $field->notes = $this->_('One email per line');
            }
            $field->columnWidth = 50;
            $field->icon = 'users';
            $fieldset->add($field);

            // EXTRA HEADERS
            $field = $modules->get('InputfieldTextarea');
            $field->attr('name', 'extra_headers');
            $field->attr('value', $data['extra_headers']);
            $field->label = $this->_('Extra Headers');
            $field->description = $this->_('Optionally define custom meta headers.');
            if(isset($siteconfig['extra_headers'])) {
                $aTemp = array();
                foreach($siteconfig['extra_headers'] as $k => $v) $aTemp[] = "{$k} : {$v}";
                $field->notes = $this->attentionMessage("\n" . implode("\n", $aTemp) . "\n");
                $field->attr('tabindex', '-1');
                unset($k, $v, $aTemp);
            } else {
                $field->notes = $this->_('One email per line');
            }
            $field->columnWidth = 50;
            $field->icon = 'align-left';
            $fieldset->add($field);

        $form->add($fieldset);


        // DISPLAY FINAL MERGED SETTINGS
        $field = $modules->get('InputfieldMarkup');
        $field->attr('name', '_final_settings');
        $field->label = 'Final Merged Settings';
        $field->icon = 'filter';
        $field->columnWidth = 100;
        $field->collapsed = Inputfield::collapsedNo;
        $field->attr('value', $this->finalSettingsMessage($siteconfig));
        $form->add($field);


        // TEST SETTINGS
        $field = $modules->get('InputfieldCheckbox');
        $field->attr('name', '_test_settings');
        $field->label = $this->_('Test settings');
        $field->description = $this->_('Test settings now.');
        $field->attr('value', 1);
        $field->attr('checked', '');
        $field->icon = 'heartbeat';
        $form->add($field);

        if(wire('session')->test_settings) {
            wire('session')->remove('test_settings');
            $field->notes = $this->testSettings();
        } else if(wire('input')->post->_test_settings) {
            wire('session')->set('test_settings', 1);
        }

        return $form;
    }


    public function testSettings() {

        $errors = array();
        $success = false;
        $module = wire('modules')->get('WireMailSmtp');

        try {
            $a = $module->getAdaptor();
            $success = $a->testConnection();
        } catch(Exception $e) {
            $errors[] = $e->getMessage();
        }

        if($success) {
            $note = $this->_('SUCCESS! SMTP settings appear to work correctly.');
            $this->message($note);
        } else {
            $note = $this->_('ERROR: SMTP settings did not work.');
            $this->error($note);
            foreach($a->getErrors() as $error) $this->error($error);
        }

        return $note;
    }


    private function attentionMessage($value) {
        return sprintf($this->_("ATTENTION: Value is overwritten by an entry in your site/config.php:\n -[ %s ]- "), $value);
    }


    private function finalSettingsMessage($siteconfig) {

        $outputTemplate = "<pre style=\"overflow:scroll !important; margin:15px auto; padding:10px 10px 10px 10px; background-color:#ffffdd; color:#555; border:1px solid #AAA; font-family:'Hack', 'Source Code Pro', 'Lucida Console', 'Courier', monospace; font-size:12px; line-height:15px;\">[__CONTENT__]</pre>";

        if(!count($siteconfig)) {
            $content = 'There are no overriding settings defined in your site/config.php';
            return str_replace('[__CONTENT__]', $content, $outputTemplate);
        }

        $validKeys = array(
            'localhost',
            'smtp_host',
            'smtp_port',
            'smtp_ssl',
            //'smtp_ssl_crypto_method',
            'smtp_start_tls',
            'smtp_tls_crypto_method',
            'smtp_user',
            'smtp_password',
            'smtp_certificate',
            'realm',
            'workstation',
            'authentication_mechanism',
            'sender_name',
            'sender_email',
            'sender_reply',
            'sender_errors_to',
            'sender_signature',
            'sender_signature_html',
            'extra_headers',
            'valid_recipients',
            #'smtp_debug',
            #'smtp_html_debug',
        );
        $module = wire('modules')->get('WireMailSmtp');
        $dump = $module->getSettings();
        $v = array();
        foreach($validKeys as $k) {
            if(isset($dump[$k])) {
                $v[$k] = 'smtp_password' == $k ? '********' : $dump[$k];
            }
        }

        ob_start();
        var_dump($v);
        $content = ob_get_contents();
        ob_end_clean();

        $m = 0;
        preg_match_all('#^(.*)=>#mU', $content, $stack);
        $lines = $stack[1];
        $indents = array_map('strlen', $lines);
        if($indents) $m = max($indents) + 1;
        $content = preg_replace_callback(
            '#^(.*)=>\\n\s+(\S)#Um',
            function($match) use ($m) {
                return $match[1] . str_repeat(' ', ($m - strlen($match[1]) > 1 ? $m - strlen($match[1]) : 1)) . $match[2];
            },
            $content
        );
        $content = preg_replace('#^((\s*).*){$#m', "\\1\n\\2{", $content);
        $content = str_replace(array('<pre>', '</pre>'), '', $content);

        return str_replace('[__CONTENT__]', $content, $outputTemplate);
    }

}

