<?php
require_once 'config.php';
require_once 'functions.php';

$currentDay = date('d');
sendResponse(['day' => $currentDay, 'date' => date('Y-m-d')]);
?>