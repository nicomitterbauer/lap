<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once 'db/dbaccess.inc.php';
// erzeugt ein neues Objekt der Klasse DbAccess
$dba = new DbAccess();


$errors = [];

?>