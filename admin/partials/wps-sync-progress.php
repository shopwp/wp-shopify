<?php

// TODO: Implement, not currently used

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

session_start();

echo "id: 1" . PHP_EOL;
echo "retry: 500" . PHP_EOL;
echo 'data: {"hello": ' . json_encode($_SESSION["wps-progress"]) . ' }'  . PHP_EOL;
echo PHP_EOL;

ob_flush();
flush();

?>
