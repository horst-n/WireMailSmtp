# Changelog


### 0.4.1 - 2019-04-19
- added support for verbose debug of the connection and sending process via a new method: $mail->debugSend()

### 0.4.0 - 2019-04-19
- added support for manual selection of a crypto method for TLS per this request: https://processwire.com/talk/topic/5704-wiremailsmtp/page/12/?tab=comments#comment-184229

### 0.3.1 - 2019-03-27
- enhanced the module to get overridden by site/config settings per this request: https://processwire.com/talk/topic/5704-wiremailsmtp/page/12/?tab=comments#comment-182735

### 0.3.0 - 2017-12-08
- fixed code that broke backward compatibility for PW 2.4 and 2.5, brought in with the config cosmetics (0.2.6)

### 0.2.7 - 2017-10-19
- @abdus fixed smtp password not saving

### 0.2.6 - 2017-10-17
- module config cosmetics by @abdus, many thanks! [See this forum post](https://processwire.com/talk/topic/5704-wiremailsmtp/?page=9&tab=comments#comment-153329)

### 0.2.5 - 2017-09-12
- fixed adding HTML-signatures into HTML-messages without body-end-tag

### 0.2.4 - 2017-09-03
- updated the attachment function to silence a PHP-Strict notice

### 0.2.3 - 2016-10-08
- updated the attachment function to be conform with the new integrated function in core WireMail class (introduced with PW 3.0.36)

### 0.2.2 - 2016-05-26
- fixed date string to follow strict RFC2822, See [Issue 5](https://github.com/horst-n/WireMailSmtp/issues/5) - Many thanks to @selsermedia!

### 0.2.0 - 2016-02-15
- added support for Selfsigned Certificates, a contribution from @flydev, [See this post](https://processwire.com/talk/topic/5704-wiremailsmtp/page-5#entry113290) - Many thanks!

### 0.1.14
- changed smtp class to new version 1.50 to solve a bug with two debug messages echoed out, even if debug was set to false

### 0.1.13 - 2015-06-14
- changed default setting for wrapText from true to false.

### 0.1.12
- fixed not sending to all CC-recipients when in sendSingle mode. See [issue 3](https://github.com/horst-n/WireMailSmtp/issues/3)

### 0.1.11
- changed smtp class to new version to solve problems with mixed usage of IP and hostname, found by @k07n. See [this post](https://processwire.com/talk/topic/5704-wiremailsmtp/page-3#entry95880)

### 0.1.10
- made wrapText configurable by Jan Romero [Commit abc0ac0b](https://github.com/horst-n/WireMailSmtp/commit/abc0ac0b4a3edd0fcbbb8b4695f00a362705ad5b)

### 0.1.9
- disabling connecting without authentication in the smtp base class

### 0.1.8
- added a separate inputfield for a HTML signature into config page

### 0.1.7
- set status from alpha to beta

### 0.0.7
- added new sentLog methods for permanent logging, intended for usage with third party modules, e.g. newsletter modules

### 0.0.6
- corrected addSignature to check and respect the config screen setting

### 0.0.5
- added multiple emails sending and bulkmail sending

### 0.0.4
- changed the functions "to" "cc" "bcc" to be compatible with Ryans changes

### 0.0.3
- added GMT Timezone to the Dateheader to reflect local timezones and show DateTime correct in Mailclients

### 0.0.2
- added sanitization to recipient names in email addresses (in file: WireMailSmtp.module)

### 0.0.1
- initial release

More information and code examples, please visit the [WireMailSmtp ProcessWire forum thread.](http://processwire.com/talk/topic/5704-module-wiremailsmtp/)
