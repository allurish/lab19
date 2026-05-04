<?php
require_once 'config.php';
require_once 'functions.php';

$currentYear = date('Y');
sendResponse(['year' => $currentYear, 'date' => date('Y-m-d')]);
?>
