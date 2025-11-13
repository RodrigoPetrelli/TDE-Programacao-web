<?php
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
}
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: no-referrer-when-downgrade');
header('Content-Security-Policy: default-src \'self\'; script-src \'self\'; object-src \'none\';');
