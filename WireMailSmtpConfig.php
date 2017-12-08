<?php
/*******************************************************************************
  *  WireMailSmtp
  * ---------------------------------------------------------------------------
  *  @version     -   '0.3.0'
  *  @author      -   Horst Nogajski
  *  @licence     -   GNU GPL v2 - http://www.gnu.org/licenses/gpl-2.0.html
*******************************************************************************/



class WireMailSmtpConfig extends Wire {

    public function getConfig(array $data) {

        // check that they have the required PW version
        if(version_compare(wire('config')->version, '2.4.1', '<')) {
            $this->error(' requires ProcessWire 2.4.1 or newer. Please update.');
        }

        $modules = wire('modules');
        $form = new InputfieldWrapper();

        // LOCALHOST
        $field = $modules->get('InputfieldText');
        $field->attr('name', 'localhost');
        $field->attr('value', $data['localhost']);
        $field->label = $this->_('Local Hostname');
        $field->description = $this->_('Hostname of this computer');
        $field->required = true;
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
            $field->required = true;
            $field->icon = 'server';
            $fieldset->add($field);

            // SMTP PORT
            $field = $modules->get('InputfieldInteger');
            $field->attr('name', 'smtp_port');
            $field->attr('value', $data['smtp_port']);
            $field->label = $this->_('SMTP Port');
            $field->description = $this->_('Set to the TCP port of the SMTP server host to connect');
            $field->notes = $this->_("default: 25\ndefault for TLS / SSL: 587 or 465");
            $field->columnWidth = 50;
            $field->required = true;
            $field->icon = 'sign-out';
            $fieldset->add($field);

            // SMTP USER
            $field = $modules->get('InputfieldText');
            $field->attr('name', 'smtp_user');
            $field->attr('value', $data['smtp_user']);
            $field->label = $this->_('SMTP user');
            $field->description = $this->_('Set this variable to the user name when the SMTP server requires authentication');
            $field->columnWidth = 50;
            $field->icon = 'user';
            $fieldset->add($field);

            // SMTP PASSWORD
            $field = $modules->get('InputfieldText');
            $field->attr('name', 'smtp_password');
            $field->attr('value', $data['smtp_password']);
            $field->attr('type', 'password');
            $field->label = $this->_('SMTP password');
            $field->description = $this->_('Set this variable to the user password when the SMTP server requires authentication');
            $field->notes = $this->_('**Note**: Password is stored as plain text in database.');
            $field->columnWidth = 50;
            $field->icon = 'asterisk';
            $fieldset->add($field);

            // SMTP STARTTLS
            $field = $modules->get('InputfieldCheckbox');
            $field->attr('name', 'smtp_start_tls');
            $field->attr('value', 1);
            $field->attr('checked', $data['smtp_start_tls'] ? 'checked' : '');
            $field->columnWidth = 50;
            $field->label = $this->_('Use START-TLS');
            $field->description = $this->_('Check if the connection to the SMTP server should use encryption after the connection is established using TLS protocol');
            $field->icon = 'lock';
            $field->showOnlyIf = 'smtp_ssl=0';
            $fieldset->add($field);

            // SMTP SSL
            $field = $modules->get('InputfieldCheckbox');
            $field->attr('name', 'smtp_ssl');
            $field->attr('value', 1);
            $field->attr('checked', $data['smtp_ssl'] ? 'checked' : '');
            $field->columnWidth = 50;
            $field->label = $this->_('Use SSL');
            $field->description = $this->_('Check if the SMTP server requires secure connections using SSL protocol');
            $field->icon = 'lock';
            $field->showOnlyIf = 'smtp_start_tls=0';
            $fieldset->add($field);

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
                $field->notes = $this->_('Default: empty');
                $field->columnWidth = 33;
                $field->icon = 'unlock';
                $fieldset2->add($field);

                // REALM
                $field = $modules->get('InputfieldText');
                $field->attr('name', 'realm');
                $field->attr('value', $data['realm']);
                $field->label = $this->_('Realm');
                $field->description = $this->_('Set this variable when the SMTP server requires authentication and if more than one authentication realm is supported');
                $field->notes = $this->_('Default: empty');
                $field->columnWidth = 34;
                $field->icon = 'map-signs';
                $fieldset2->add($field);

                // WORKSTATION
                $field = $modules->get('InputfieldText');
                $field->attr('name', 'workstation');
                $field->attr('value', $data['workstation']);
                $field->label = $this->_('Workstation');
                $field->description = $this->_('Set this variable to the client workstation when the SMTP server requires authentication identifiying the origin workstation name');
                $field->notes = $this->_('Default: empty');
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
            $field->columnWidth = 50;
            $field->icon = 'at';
            $fieldset->add($field);

            // SENDER NAME
            $field = $modules->get('InputfieldText');
            $field->attr('name', 'sender_name');
            $field->attr('value', $data['sender_name']);
            $field->label = $this->_('Sender Name');
            $field->description = $this->_("");
            $field->columnWidth = 50;
            $field->icon = 'user';
            $fieldset->add($field);

            // SENDER REPLY EMAIL
            $field = $modules->get('InputfieldText');
            $field->attr('name', 'sender_reply');
            $field->attr('value', $data['sender_reply']);
            $field->label = $this->_('Reply Email Address');
            $field->description = $this->_('if is empty, Sender Emailaddress is used');
            $field->columnWidth = 50;
            $field->collapsed = Inputfield::collapsedYes;
            $field->icon = 'mail-reply';
            $fieldset->add($field);

            // SENDER ERROR EMAIL
            $field = $modules->get('InputfieldText');
            $field->attr('name', 'sender_errors_to');
            $field->attr('value', $data['sender_errors_to']);
            $field->label = $this->_('Errors Email Address');
            $field->notes = $this->_('Default: empty');
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
            $field->columnWidth = 36;
            $field->icon = 'pencil';
            $fieldset->add($field);

            // SENDER SIGNATURE (HTML)
            $field = $modules->get('InputfieldTextarea');
            $field->attr('name', 'sender_signature_html');
            $field->attr('value', $data['sender_signature_html']);
            $field->label = $this->_('Sender Signature (HTML)');
            $field->description = $this->_('Like Contact Data and / or Confidentiality Notices');
            $field->columnWidth = 36;
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
            $field->columnWidth = 28;
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
            $field->notes = $this->_('One email per line');
            $field->columnWidth = 50;
            $field->icon = 'users';
            $fieldset->add($field);

            // EXTRA HEADERS
            $field = $modules->get('InputfieldTextarea');
            $field->attr('name', 'extra_headers');
            $field->attr('value', $data['extra_headers']);
            $field->label = $this->_('Extra Headers');
            $field->description = $this->_('Optionally define custom meta headers.');
            $field->notes = $this->_('One header per line');
            $field->columnWidth = 50;
            $field->icon = 'align-left';
            $fieldset->add($field);

        $form->add($fieldset);

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

}

