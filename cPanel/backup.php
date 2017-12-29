<?php
// Crontab:  30 3 * * * /usr/local/bin/php /home/username/private/backup.php

// CONFIGURATION
$CPANEL_USERNAME = 'cpanel-username';
$CPANEL_PASSWORD = 'cpanel-password';
$CPANEL_DOMAIN = 'domain.tld';
$CPANEL_THEME = 'paper_lantern';
$FTP_USERNAME = 'ftp-username';
$FTP_PASSWORD = 'ftp-password';
$FTP_HOSTNAME = 'ftp.domain.tld';
$FTP_MODE = 'passiveftp';
$FTP_PORT = '21';
$FTP_DIRECTORY = '/backup/domain.tld';
$EMAIL_NOTIFY = 'backup@domain.tld';
$USE_SSL = false;
// END CONFIGURATION SECTION

$url = $USE_SSL ? sprintf('ssl://%s', $CPANEL_DOMAIN) : $CPANEL_DOMAIN;
$port = $USE_SSL ? 2083 : 2082;

$socket = fsockopen($url, $port);
if(!$socket) {
    echo 'Cannot open socket connection to host';
    exit(1);
}

$authorizationHeader = base64_encode(sprintf('%s:%s', $CPANEL_USERNAME, $CPANEL_PASSWORD));
$parameters = sprintf(
    'dest=%s&email=%s&server=%s&user=%s&pass=%s&port=%s&rdir=%s&submit=Generate Backup',
    $FTP_MODE,
    $EMAIL_NOTIFY,
    $FTP_HOSTNAME,
    $FTP_USERNAME,
    $FTP_PASSWORD,
    $FTP_PORT,
    $FTP_DIRECTORY
);

fputs($socket, sprintf("POST /frontend/%s/backup/dofullbackup.html?%s HTTP/1.0\r\n", $CPANEL_THEME, $parameters));
fputs($socket, sprintf("Host: %s\r\n", $CPANEL_DOMAIN));
fputs($socket, sprintf("Authorization: Basic %s\r\n", $authorizationHeader));
fputs($socket, "Connection: Close\r\n");
fputs($socket, "\r\n");

while (!feof($socket)) {
    $response = fgets($socket,4096);
}

fclose($socket);

exit(0);

