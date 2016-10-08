###Changelog###

+    0.2.3  updated the attachment function to be conform with the new integrated function in core WireMail class (introduced with PW 3.0.36)

+    0.2.2  fixed date string to follow strict RFC2822, (https://github.com/horst-n/WireMailSmtp/issues/5) - Many thanks to @selsermedia!

+    0.2.0  added support for Selfsigned Certificates, a contribution from @flydev, (https://processwire.com/talk/topic/5704-wiremailsmtp/page-5#entry113290) - Many thanks!

+    0.1.14 changed smtp class to new version 1.50 to solve a bug with two debug messages echoed out, even if debug was set to false

+    0.1.13 changed default setting for wrapText from true to false.

+    0.1.12 fixed not sending to all CC-recipients when in sendSingle mode. ([url=https://github.com/horst-n/WireMailSmtp/issues/3]https://github.com/horst-n/WireMailSmtp/issues/3[/url])

+    0.1.11 changed smtp class to new version to solve problems with mixed usage of IP and hostname, found by @k07n (https://processwire.com/talk/topic/5704-wiremailsmtp/page-3#entry95880)

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
