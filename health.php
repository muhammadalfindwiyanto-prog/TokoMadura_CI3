<?php

header('Content-Type: application/json');

echo json_encode([
    "status" => "ok",
    "service" => "TokoMadura_CI3",
    "time" => date("Y-m-d H:i:s")
]);