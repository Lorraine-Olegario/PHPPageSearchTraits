<?php

$lines = file(__DIR__ . '/.env');

foreach ($lines as $line) {
    if (trim($line)) {
        putenv(trim($line));
    }
}