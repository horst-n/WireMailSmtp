WireMailSmtp
============

### ProcessWire: a extension to the new WireMail base class that uses SMTP-transport ###

### Only functional with ProcessWire Version 2.4.1 or greater ###

This module integrates EmailMessage, SMTP and SASL php-libraries from [Manuel Lemos](http://www.phpclasses.org/browse/author/1.html) into ProcessWire.


[More information and code examples](http://processwire.com/talk/topic/5704-module-wiremailsmtp/)

---


### Current Version 0.4.1 ###



### List of all options and features ###

**testConnection** () *- returns true on success, false on failures*

**debugSend** () *- send the mail(s) and output or return verbose messages of the complete connection and sending process*


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

**attachment** ($filename, $alternativeBasename = '') *- add attachment file*


**send** () *- send the mail(s) and return number of successful send messages*


**getResult** () *- returns a dump (array) with all recipients (to, cc, bcc) and settings you have selected with the message, the message subject and body, and lists of successfull addresses and failed addresses,*


**logActivity** ($logmessage) *- you may log success if you want*

**logError** ($logmessage) *- you may log errors, too. - Errors are also logged automaticaly*


**useSentLog** (true | false) *- intended for usage with e.g. third party newsletter modules - tells the send() method to make usage of the sentLog-methods*

**sentLogReset** ()  *- starts a new Session. Best usage would be interactively once when setting up a new Newsletter*

**sentLogGet** ()  *- returns an array containing all previously used emailaddresses*

**sentLogAdd** ($emailaddress)  *- is called automaticly within the send() method*

**wrapText** (true|false) *- default is true*



[More information and code examples](http://processwire.com/talk/topic/5704-module-wiremailsmtp/)
