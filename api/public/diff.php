<?php
require_once 'config.php';
require_once 'functions.php';

if (!isset($_GET['date1']) || !isset($_GET['date2'])) {
    sendResponse(['error' => 'Parameters "date1" and "date2" are required. Format: YYYY-MM-DD'], 400);
}

$date1 = $_GET['date1'];
$date2 = $_GET['date2'];

$timestamp1 = strtotime($date1);
$timestamp2 = strtotime($date2);

if ($timestamp1 === false || $timestamp2 === false) {
    sendResponse(['error' => 'Invalid date format. Use YYYY-MM-DD'], 400);
}

$diff = abs($timestamp2 - $timestamp1);
$days = floor($diff / (60 * 60 * 24));

sendResponse([
    'date1' => $date1,
    'date2' => $date2,
    'days_between' => $days
]);
?>