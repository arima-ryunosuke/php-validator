<?php
require_once __DIR__ . '/+include.php';

header('Content-Type: application/json');
echo json_encode($ajax->response());
