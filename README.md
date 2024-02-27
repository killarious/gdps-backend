# Killarious GDPS Backed
Basically a Geometry Dash Server Emulator
Supported versions of Geometry Dash: 1.0 - 2.2
Required version of PHP: 5.5+ (tested up to 8.1.2)

See [the backwards compatibility section of this article](
https://github.com/Cvolton/GMDprivateServer/wiki/Deliberate
-differences-from-real-GD) for more information

### Setup (DEPRECATED)
1) Upload the files on a webserver
2) Import database.sql into a MySQL/MariaDB database
3) Edit the links in GeometryDash.exe (some are base64 encoded since 2.1, remember that)

#### Updating the server
See [README.md in the `_updates`](_updates/README.md)

### Credits

#### People:
- [Cvolton](https://github.com/Cvolton) — Creator of the original backend and author of the idea
- [MegaSa1nt](https://github.com/MegaSa1nt) — 2.2 support and much more
- [someguy28](https://github.com/someguy28) — Base for account settings and the private messaging system by someguy28
- [pavlukivan](https://github.com/pavlukivan) and [Italian APK Downloader](https://github.com/ItalianApkDownloader) — Figured out most of the stuff in generateHash.php

#### Software
XOR encryption — https://github.com/sathoro/php-xor-cipher — (incl/lib/XORCipher.php)
- Cloud save encryption — https://github.com/defuse/php-encryption — (incl/lib/defuse-crypto.phar)
- Mail verification — https://github.com/phpmailer/phpmailer — (config/mail)
- JQuery — https://github.com/jquery/jquery — (dashboard/lib/jq.js)
- Image dominant color picker — https://github.com/swaydeng/imgcolr — (dashboard/lib/imgcolr.js)
Media cover — https://github.com/aadsm/jsmediatags — (dashboard/lib/jsmediatags.js)
