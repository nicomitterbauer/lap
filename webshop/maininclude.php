<?php
error_reporting(E_ALL);
ini_set('diplay_errors', 1);
session_start();

require_once 'db/dbaccess.php';
$dba = new DbAccess();

$errors = [];
?>