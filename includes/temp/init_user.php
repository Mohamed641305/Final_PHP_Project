<?php
// Ensure output buffering for user pages to avoid headers-sent issues
if (ob_get_level() == 0) ob_start();
include "includes/db/db.php";
include "includes/temp/header.php";
include "includes/temp/navbar.php";
?>