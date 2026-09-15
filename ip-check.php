<?php

header('Content-Type: text/plain');

echo 'REMOTE_ADDR: ' . ($_SERVER['REMOTE_ADDR'] ?? 'not set') . PHP_EOL;
echo 'HTTP_X_FORWARDED_FOR: ' . ($_SERVER['HTTP_X_FORWARDED_FOR'] ?? 'not set') . PHP_EOL;
echo 'HTTP_FORWARDED: ' . ($_SERVER['HTTP_FORWARDED'] ?? 'not set') . PHP_EOL;
