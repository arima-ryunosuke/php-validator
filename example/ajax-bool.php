<?php
require_once __DIR__ . '/+include.php';

header('Content-Type: application/json');

echo json_encode(($_GET['ajax1'] ?? 0) + ($_GET['ajax2'] ?? 0) === intval($_GET['ajax_bool'] ?? null));
