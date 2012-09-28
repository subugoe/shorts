=======================
Shorts - URL Verkuerzer
=======================

Nutzt einen Hook in RealURL um kurze URL zu generieren. Die kurzen URL sind 5 Zeichen nach dem Hostname lang.
Der Aufruf erfolgt aus Performancegruenden mit dem eID Parameter von TYPO3.

*************
Konfiguration
*************

Folgender Eintrag muss in die .htaccess:

RewriteRule ^-(.*)$  index.php?eID=shorts&shortUrl=$1 [L,NC,QSA]

Damit werden alle URL, die mit - beginnen an den Shortenerdienst weitergeleitet

******************************************************
Einbinden der URL in die Frontendausgabe als Permalink
******************************************************

# URL shortener im TypoScript Setup
temp.FRIGHT.26 < tt_content.list.20.shorts_shortener