<?php

$mysqli = new mysqli('127.0.0.1', 'root', '', 'test', '3306');
if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}

$ipAddress = $_SERVER['REMOTE_ADDR'];
$userAgent = $_SERVER['HTTP_USER_AGENT'];
$pageUrl = $_SERVER['REQUEST_URI'];

$sql = "INSERT INTO banner_views (ip_address, user_agent, page_url, view_date, views_count) VALUES (?, ?, ?, NOW(), 1) ON DUPLICATE KEY UPDATE view_date = NOW(), views_count = views_count + 1";
$stmt = $mysqli->prepare($sql);

$stmt->bind_param('sss', $ipAddress, $userAgent, $pageUrl);
$stmt->execute();

$stmt->close();
$mysqli->close();

header('Content-Type: image/webp');
header('Content-Length: ' . filesize('./img/img.webp'));
header('Cache-Control: no-cache');
readfile('img/img.webp');
exit;
