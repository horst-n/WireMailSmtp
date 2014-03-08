WireMailSmtp
============

###ProcessWire: a extension to the new WireMail base class that uses SMTP-transport###

###only functional with ProcessWire Version 2.4.1 or greater###

This module integrates EmailMessage, SMTP and SASL php-libraries from [Manuel Lemos](http://www.phpclasses.org/browse/author/1.html) into ProcessWire.


More information and code examples: http://processwire.com/talk/topic/5704-module-wiremailsmtp/

---


###Current Version 0.0.7  (alpha)###


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



###Changelog###

+    0.0.7 added new sentLog methods for permanent logging, intended for usage with third party modules, e.g. newsletter modules

+    0.0.6 corrected addSignature to check and respect the config screen setting
     
+    0.0.5 added multiple emails sending and bulkmail sending
     
+    0.0.4 changed the functions "to" "cc" "bcc" to be compatible with Ryans changes
     
+    0.0.3 added GMT Timezone to the Dateheader to reflect local timezones and show DateTime correct in Mailclients
     
+    0.0.2 added sanitization to recipient names in email addresses (in file: WireMailSmtp.module)
     
+    0.0.1 initial release


More information and code examples: http://processwire.com/talk/topic/5704-module-wiremailsmtp/
