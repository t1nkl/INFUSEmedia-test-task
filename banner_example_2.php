<?php

$mysqli = new mysqli('127.0.0.1', 'root', '', 'test', '3306');
if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}

$ipAddress = $_SERVER['REMOTE_ADDR'];
$userAgent = $_SERVER['HTTP_USER_AGENT'];
$pageUrl = $_SERVER['REQUEST_URI'];

// Start transaction
$mysqli->begin_transaction();

$stmt = $mysqli->prepare("SELECT views_count FROM banner_views WHERE ip_address = :? AND user_agent = ? AND page_url = ?");
$stmt->bind_param("sss", $ipAddress, $userAgent, $pageUrl);
$stmt->execute();

$result = $stmt->get_result();
$existingRecord = $result->fetch_assoc();

if ($existingRecord) {
    $stmt = $mysqli->prepare("UPDATE banner_views SET view_date = NOW(), views_count = ? WHERE ip_address = ? AND user_agent = ? AND page_url = ?");
    $new_views_count = $existingRecord['views_count'] + 1;
    $stmt->bind_param("isss", $new_views_count, $ipAddress, $userAgent, $pageUrl);
} else {
    $stmt = $mysqli->prepare("INSERT INTO banner_views (ip_address, user_agent, page_url, view_date, views_count) VALUES (?, ?, ?, NOW(), 1)");
    $stmt->bind_param("sss", $ipAddress, $userAgent, $pageUrl);
}
$stmt->execute();

// Commit transaction
$mysqli->commit();

$stmt->close();
$mysqli->close();

header('Content-Type: image/webp');
header('Content-Length: ' . filesize('./img/img.webp'));
header('Cache-Control: no-cache');
readfile('img/img.webp');
exit;
