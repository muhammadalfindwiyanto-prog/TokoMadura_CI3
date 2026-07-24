<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
http_response_code(200);

echo json_encode([
    'success' => true,
    'service' => 'TokoMadura_CI3',
    'status' => 'ok',
    'time' => date(DATE_ATOM),
], JSON_UNESCAPED_SLASHES);