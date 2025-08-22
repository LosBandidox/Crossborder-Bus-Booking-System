<?php
date_default_timezone_set('Africa/Nairobi');
header('Content-Type: application/json');
echo json_encode(['php_time' => date('Y-m-d H:i:s')]);
?>