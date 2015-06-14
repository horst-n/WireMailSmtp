WireMailSmtp
============

###ProcessWire: a extension to the new WireMail base class that uses SMTP-transport###

###only functional with ProcessWire Version 2.4.1 or greater###

This module integrates EmailMessage, SMTP and SASL php-libraries from [Manuel Lemos](http://www.phpclasses.org/browse/author/1.html) into ProcessWire.


More information and code examples: http://processwire.com/talk/topic/5704-module-wiremailsmtp/

---


###Current Version 0.1.13  (beta)###


###List of all options and features###

**testConnection** () *- returns true on success, false on failures*


**sendSingle** ( true | false ) *- default is false*

**sendBulk** ( true | false ) *- default is false, Set this to true if you have lots of recipients (50+)*


**to** ($recipients) *- one emailaddress or array with multiple emailaddresses*

**cc** ($recipients) *- only available with mode sendSingle, one emailaddress or array with multiple emailaddresses*

**bcc** ($recipients) *- one emailaddress or array with multiple emailaddresses*



**from** = 'person@example.com' *- can be set in module config (called Sender Emailaddress) but it can be overwritten here*

**fromName =** 'Name Surname' *- optional, can be set in module config (called Sender Name) but it can be overwritten here*


**priority** (3) *- 1 = Highest | 2 = High | 3 = Normal | 4 = Low | 5 = Lowest*

**dispositionNotification** () or **notification** ()  *- request a Disposition Notification*


**subject** ($subject) *- subject of the message*

**body** ($textBody) *- use this one alone to create and send plainText emailmessages*

**bodyHTML** ($htmlBody) *- use this to create a Multipart Alternative Emailmessage (containing a HTML-Part and a Plaintext-Part as fallback)*

**addSignature** ( true | false ) *- the default-behave is selectable in config screen, this can be overridden here. (only available if a signature is defined in the config screen)*

**attachment** ($filename) *- add attachment file(s) - string or array()*


**send** () *- send the mail(s) and return number of successful send messages*


**getResult** () *- returns a dump (array) with all recipients (to, cc, bcc) and settings you have selected with the message, the message subject and body, and lists of successfull addresses and failed addresses,*


**logActivity** ($logmessage) *- you may log success if you want*

**logError** ($logmessage) *- you may log errors, too. - Errors are also logged automaticaly*


**useSentLog** (true | false) *- intended for usage with e.g. third party newsletter modules - tells the send() method to make usage of the sentLog-methods*

**sentLogReset** ()  *- starts a new Session. Best usage would be interactively once when setting up a new Newsletter*

**sentLogGet** ()  *- returns an array containing all previously used emailaddresses*

**sentLogAdd** ($emailaddress)  *- is called automaticly within the send() method*

**wrapText** (true|false) *- default is false*


###Changelog###

+    0.1.13 changed default setting for wrapText from true to false.

+    0.1.12 fixed not sending to all CC-recipients when in sendSingle mode. (https://github.com/horst-n/WireMailSmtp/issues/3)

+    0.1.11 changed smtp class to new version to solve problems with mixed usage of IP and hostname, found by k07n (https://processwire.com/talk/topic/5704-wiremailsmtp/page-3#entry95880)

+    0.1.10 made wrapText configurable by Jan Romero (https://github.com/horst-n/WireMailSmtp/commit/abc0ac0b4a3edd0fcbbb8b4695f00a362705ad5b)

+    0.1.9  disabling connecting without authentication in the smtp base class

+    0.1.8  added a separate inputfield for a HTML signature into config page

+    0.1.7  set status from alpha to beta

+    0.0.7  added new sentLog methods for permanent logging, intended for usage with third party modules, e.g. newsletter modules

+    0.0.6  corrected addSignature to check and respect the config screen setting

+    0.0.5  added multiple emails sending and bulkmail sending

+    0.0.4  changed the functions "to" "cc" "bcc" to be compatible with Ryans changes

+    0.0.3  added GMT Timezone to the Dateheader to reflect local timezones and show DateTime correct in Mailclients

+    0.0.2  added sanitization to recipient names in email addresses (in file: WireMailSmtp.module)

+    0.0.1  initial release


More information and code examples: http://processwire.com/talk/topic/5704-module-wiremailsmtp/
