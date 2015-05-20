======================
Shorts - URL Shortener
======================

This extension Uses a RealURL hook for generating a short URL. A short URL consists of an identifier with 5
Characters preceded by the hostname and a dash, such as http://hostname/-Sh3y1

*************
Configuration
*************

This line needs to be added to the .htaccess:

RewriteRule ^-(.*)$  index.php?eID=shorts&shortUrl=$1 [L,NC,QSA]

So, each URL beginning with a - are forwarded to the shortening service.

*******************************
Frontend output on a TYPO3 page
*******************************

# URL shortener in TypoScript setup like:

lib.shortener < tt_content.list.20.shorts_shortener
