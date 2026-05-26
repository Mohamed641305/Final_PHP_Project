<?php

if (ob_get_level() == 0) {
    ob_start();
}

include __DIR__ . "/../db/db.php";
include __DIR__ . "/header.php";
include __DIR__ . "/navbar.php";