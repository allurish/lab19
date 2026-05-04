<?php
require_once 'config.php';
require_once 'functions.php';

$currentMonth = date('m');
sendResponse(['month' => $currentMonth, 'month_name' => date('F'), 'date' => date('Y-m-d')]);
?>