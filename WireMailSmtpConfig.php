<?php
/*******************************************************************************
 *  WireMailSmtp
 *
 * ---------------------------------------------------------------------------
 * @version     -   '0.1.8'
 * @date        -   $Date: 2014/03/14 20:09:23 $
 * @author      -   Horst Nogajski
 * @licence     -   GNU GPL v2 - http://www.gnu.org/licenses/gpl-2.0.html
 * ---------------------------------------------------------------------------
 *  $Source: /WEB/pw4/htdocs/site/modules/WireMailSmtp/WireMailSmtpConfig.php,v $
 *  $Id: WireMailSmtpConfig.php,v 1.7 2014/03/14 20:09:23 horst Exp $
 ******************************************************************************
 *
 *  LAST CHANGES:
 *
 **/


class WireMailSmtpConfig extends Wire
{

    public function getConfig(array $data)
    {

        // check that they have the required PW version
        if (version_compare(wire('config')->version, '2.4.1', '<')) {
            $this->error("This module requires ProcessWire 2.4.1 or newer. Please upgrade.");
        }

        $modules = wire('modules');
        /** @var $form \ProcessWire\InputfieldWrapper */
        $form = new InputfieldWrapper();

        // // special handling for SMTP password
        // // seen by @teppo's SwiftMailer
        // if(isset($data['smtp_password2'])) {
        //     $data['smtp_password'] = $data['smtp_password2'];
        //     unset($data['smtp_password2'], $data['clear_smtp_password']);
        //     wire('modules')->saveModuleConfigData('WireMailSmtp', $data);
        // }
        // elseif(isset($data['clear_smtp_password']) && $data['clear_smtp_password']) {
        //     unset($data['smtp_password'], $data['smtp_password2'], $data['clear_smtp_password']);
        //     wire('modules')->saveModuleConfigData('WireMailSmtp', $data);
        // }
        // else {
        //     unset($data['smtp_password2'], $data['clear_smtp_password']);
        //     wire('modules')->saveModuleConfigData('WireMailSmtp', $data);
        // }

        // LOCALHOST
        $form->add([
            'type'        => 'text',
            'name'        => 'localhost',
            'value'       => $data['localhost'],
            'required'    => true,
            'label'       => $this->_('Local Hostname'),
            'description' => $this->_('Hostname of this computer'),
            'icon'        => 'desktop',
        ]);

        // SMTP SERVER
        /** @var $smtpWrapper \ProcessWire\InputfieldFieldset */
        $smtpWrapper = $modules->get('InputfieldFieldset');
        $smtpWrapper->setArray([
            'label'     => $this->_('SMTP Server'),
            'name'      => '_smtp',
            'collapsed' => Inputfield::collapsedNo,
            'icon'      => 'server',
        ]);
        $form->add($smtpWrapper);

        // SMTP HOST
        $smtpWrapper->add([
            'type'        => 'text',
            'name'        => 'smtp_host',
            'value'       => $data['smtp_host'],
            'required'    => true,
            'label'       => $this->_('SMTP Hostname'),
            'description' => $this->_('Set to the host name of the SMTP server to which you want to relay the messages'),
            'icon'        => 'server',
            'columnWidth' => 50,
        ]);

        // SMTP PORT
        $smtpWrapper->add([
            'type'        => 'integer',
            'name'        => 'smtp_port',
            'value'       => $data['smtp_port'],
            'required'    => true,
            'label'       => $this->_('SMTP Port'),
            'description' => $this->_('Set to the TCP port of the SMTP server host to connect'),
            'notes'       => $this->_("Default: `25`\nDefault for TLS / SSL: `587` or `465`"),
            'columnWidth' => 50,
            'icon'        => 'sign-out',
        ]);

        // SMTP USER
        $smtpWrapper->add([
            'type'        => 'text',
            'name'        => 'smtp_user',
            'value'       => $data['smtp_user'],
            'label'       => $this->_('SMTP User'),
            'description' => $this->_('Set this variable to the user name when the SMTP server requires authentication'),
            'columnWidth' => 50,
            'icon'        => 'user',
        ]);

        // SMTP PASSWORD
        $smtpWrapper->add([
            'type'        => 'text',
            'name'        => 'smtp_password',
            'value'       => $data['smtp_password'],
            'label'       => $this->_('SMTP Password'),
            'description' => $this->_('Set this variable to the user password when the SMTP server requires authentication.'),
            'notes'       => $this->_('**Note:** Password is stored as plain text in database.'),
            'icon'        => 'asterisk',
            'columnWidth' => 50,
        ]);

        // SMTP STARTTLS
        $smtpWrapper->add([
            'type'        => 'checkbox',
            'name'        => 'smtp_start_tls',
            'value'       => 1,
            'checked'     => $data['smtp_start_tls'] ? 'checked' : '',
            'label'       => $this->_('Use STARTTLS'),
            'description' => $this->_('Check if the connection to the SMTP server should use encryption after the connection is established using TLS protocol.'),
            'columnWidth' => 33,
            'icon'        => 'lock',
        ]);

        // SMTP SSL
        $smtpWrapper->add([
            'type'        => 'checkbox',
            'name'        => 'smtp_ssl',
            'value'       => 1,
            'checked'     => $data['smtp_ssl'] ? 'checked' : '',
            'label'       => $this->_('Use SSL'),
            'description' => $this->_('Check if the SMTP server requires secure connections using SSL protocol.'),
            'columnWidth' => 33,
            'icon'        => 'lock',
        ]);

        // SMTP SELF-SIGNED CERTIFICATE
        // @flydev: https://processwire.com/talk/topic/5704-wiremailsmtp/page-5#entry113290
        if (version_compare(phpversion(), '5.6.0', '>=')) {
            $smtpWrapper->add([
                'type'        => 'checkbox',
                'name'        => 'smtp_certificate',
                'value'       => $data['smtp_certificate'],
                'checked'     => $data['smtp_certificate'] ? 'checked' : '',
                'label'       => $this->_('SMTP Certificate'),
                'description' => $this->_('Allow self signed certificate'),
                'notes'       => $this->_('For PHP >= 5.6'),
                'icon'        => 'certificate',
                'columnWidth' => 34,
            ]);
        }

        // $field = $modules->get("InputfieldCheckbox");
        // $field->name = "clear_smtp_password";
        // $field->label = $this->_("Clear password?");
        // $field->notes = $this->_("Check and save form to clear stored password.");
        // $field->columnWidth = 20;
        // $fieldset->add($field);

        // ADVANCED SMTP SETTINGS
        /** @var $advancedSmtpWrapper \ProcessWire\InputfieldFieldset */
        $advancedSmtpWrapper = $modules->get('InputfieldFieldset');
        $advancedSmtpWrapper->setArray([
            'label'     => $this->_('Advanced SMTP'),
            'name'      => '_advanced_SMTP',
            'collapsed' => Inputfield::collapsedYes,
            'icon'      => 'cogs',
        ]);
        $smtpWrapper->add($advancedSmtpWrapper);

        // AUTHENTICATION MECHANISM
        $advancedSmtpWrapper->add([
            'type'        => 'text',
            'name'        => 'authentication_mechanism',
            'value'       => $data['authentication_mechanism'],
            'label'       => $this->_('Authentication Mechanism'),
            'description' => $this->_('Force the use of a specific authentication mechanism.'),
            'notes'       => $this->_('Default: empty'),
            'columnWidth' => 33,
            'icon'        => 'unlock',
        ]);

        // REALM
        $advancedSmtpWrapper->add([
            'type'        => 'text',
            'name'        => 'realm',
            'value'       => $data['realm'],
            'label'       => $this->_('Realm'),
            'description' => $this->_('Set this variable when the SMTP server requires authentication and if more than one authentication realm is supported'),
            'icon'        => 'map-signs',
            'columnWidth' => 33,
        ]);

        // WORKSTATION
        $advancedSmtpWrapper->add([
            'type'        => 'text',
            'name'        => 'workstation',
            'value'       => $data['workstation'],
            'label'       => $this->_('Workstation'),
            'description' => $this->_('Set this variable to the client workstation when the SMTP server requires authentication identifiying the origin workstation name.'),
            'icon'        => 'building',
            'columnWidth' => 34,
        ]);

        // SENDER
        /** @var $senderWrapper \ProcessWire\InputfieldFieldset */
        $senderWrapper = $modules->get('InputfieldFieldset');
        $senderWrapper->setArray([
            'label'     => $this->_('Sender'),
            'name'      => '_sender',
            'collapsed' => Inputfield::collapsedNo,
            'icon'      => 'address-book',
        ]);
        $form->add($senderWrapper);

        // SENDER EMAIL
        $senderWrapper->add([
            'type'        => 'email',
            'name'        => 'sender_email',
            'value'       => $data['sender_email'],
            'label'       => $this->_('Sender Email Address'),
            'icon'        => 'at',
            'columnWidth' => 50,
        ]);

        // SENDER NAME
        $senderWrapper->add([
            'type'        => 'text',
            'name'        => 'sender_name',
            'value'       => $data['sender_name'],
            'label'       => $this->_('Sender Name'),
            'icon'        => 'user',
            'columnWidth' => 50,
        ]);

        // SENDER EMAIL
        $senderWrapper->add([
            'type'        => 'email',
            'name'        => 'sender_reply',
            'value'       => $data['sender_reply'],
            'label'       => $this->_('Sender Reply Email Address'),
            'description' => $this->_('If empty, sender email address is used.'),
            'icon'        => 'mail-reply',
            'columnWidth' => 50,
            'collapsed'   => Inputfield::collapsedYes,
        ]);

        // SENDER ERROR EMAIL
        $senderWrapper->add([
            'type'        => 'email',
            'name'        => 'sender_errors_to',
            'value'       => $data['sender_errors_to'],
            'label'       => $this->_('Errors Email Address'),
            'notes'       => $this->_('Default: empty'),
            'icon'        => 'remove',
            'columnWidth' => 50,
            'collapsed'   => Inputfield::collapsedYes,
        ]);

        // SENDER SIGNATURE (TEXT)
        $senderWrapper->add([
            'type'        => 'textarea',
            'name'        => 'sender_signature',
            'value'       => $data['sender_signature'],
            'label'       => $this->_('Sender Signature (Text)'),
            'description' => $this->_('Like contact data and/or confidentiality notices.'),
            'icon'        => 'pencil',
            'columnWidth' => 33,
        ]);

        // SENDER SIGNATURE (HTML)
        $senderWrapper->add([
            'type'        => 'textarea',
            'name'        => 'sender_signature_html',
            'value'       => $data['sender_signature_html'],
            'label'       => $this->_('Sender Signature (HTML)'),
            'description' => $this->_('Like contact data and/or confidentiality notices.'),
            'columnWidth' => 33,
            'icon'        => 'code',
        ]);

        // SENDER SEND SIGNATURE
        $senderWrapper->add([
            'type'        => 'select',
            'name'        => 'send_sender_signature',
            'value'       => $data['send_sender_signature'],
            'label'       => $this->_('Send Sender Signature'),
            'description' => $this->_('When the signature should be sent by default (could be overriden by the API)'),
            'options'     => [
                1 => $this->_('Only when explicitly called via API'),
                2 => $this->_('Automatically when `from` = sender email address'),
                3 => $this->_('Automaticaly with _every_ message'),
            ],
            'icon'        => 'question',
            'columnWidth' => 34,
        ]);

        // ADVANCED SETTINGS
        /** @var $advancedWrapper \ProcessWire\InputfieldFieldset */
        $advancedWrapper = $modules->get('InputfieldFieldset');
        $advancedWrapper->setArray([
            'label'     => $this->_('Advanced'),
            'icon'      => 'gear',
            'name'      => '_advanced',
            'collapsed' => Inputfield::collapsedYes,
        ]);
        $form->add($advancedWrapper);

        // VALID RECIPIENTS
        $advancedWrapper->add([
            'type'        => 'textarea',
            'name'        => 'valid_recipients',
            'value'       => $data['valid_recipients'],
            'label'       => $this->_('Valid Recipients'),
            'description' => $this->_('List of email addresses that can receive messages.'),
            'notes'       => $this->_('One email per line'),
            'icon'        => 'users',
            'columnWidth' => 50,
        ]);

        // EXTRA HEADERS
        $advancedWrapper->add([
            'type'        => 'textarea',
            'name'        => 'extra_headers',
            'value'       => $data['extra_headers'],
            'label'       => $this->_('Extra Headers'),
            'description' => $this->_('Optionally define custom meta headers'),
            'notes'       => $this->_('One header per line'),
            'icon'        => 'align-left',
            'columnWidth' => 50,
        ]);

        //	$field = $modules->get("InputfieldText");
        //	$field->attr('name', 'user_agent');
        //	$field->attr('value', $data['user_agent']);
        //	$field->label = $this->_('User-Agent');
        //	$field->notes = $this->_("Set the user agent used when connecting via an HTTP proxy");
        //	#$field->columnWidth = 50;
        //	$fieldset->add($field);

        // TEST SETTINGS
        $testSettings = [
            'type'        => 'checkbox',
            'name'        => '_test_settings',
            'value'       => 1,
            'checked'     => '',
            'label'       => $this->_('Test Settings'),
            'description' => $this->_('Test settings now.'),
            'icon'        => 'heartbeat',
        ];

        if (wire('session')->test_settings) {
            $testSettings['notes'] = $this->testSettings();
            wire('session')->remove('test_settings');
        } elseif (wire('input')->post->_test_settings) {
            wire('session')->set('test_settings', 1);
        }
        $form->add($testSettings);

        return $form;
    }


    public function testSettings()
    {
        $errors = [];
        $success = false;
        $module = wire('modules')->get('WireMailSmtp');

        try {
            $a = $module->getAdaptor();
            $success = $a->testConnection();
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }

        if ($success) {
            $note = $this->_('SUCCESS! SMTP settings appear to work correctly.');
            $this->message($note);
        } else {
            $note = $this->_('SMTP settings did not work.');
            $this->error($note);
            foreach ($a->getErrors() as $error) $this->error($error);
        }

        return $note;
    }

}
