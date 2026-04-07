<?php
$dns = "mysql:host=localhost;dbname=final_php_project;charset=utf8";
$username = "root";
$password = "";

try {
  $connect = new PDO($dns, $username, $password);
  $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
  echo "Connection failed: " . $e->getMessage();
}
?>