<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

// ?? Obtener IP real (considerando proxy)
function obtenerIPReal() {

    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($ips[0]);
    }

    if (!empty($_SERVER['HTTP_X_REAL_IP'])) {
        return $_SERVER['HTTP_X_REAL_IP'];
    }

    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

$ip_cliente = obtenerIPReal();

// ?? Obtener token desde header personalizado
$token = $_SERVER['HTTP_X_API_KEY'] ?? '';

// ?? Configuración
$ip_permitida = '132.248.54.219';
$token_valido = 'MI_TOKEN_SECRETO';

// ?? DEBUG (activar si necesitas)
/*
echo json_encode([
    "IP_DETECTADA" => $ip_cliente,
    "TOKEN_RECIBIDO" => $token,
    "HEADERS" => $_SERVER
], JSON_PRETTY_PRINT);
exit;
*/

// ?? Validar IP
if ($ip_cliente !== $ip_permitida) {
    http_response_code(403);
    echo json_encode([
        "status" => "error",
        "mensaje" => "IP no autorizada",
        "ip_detectada" => $ip_cliente
    ]);
    exit;
}

// ?? Validar token
if ($token !== $token_valido) {
    http_response_code(401);
    echo json_encode([
        "status" => "error",
        "mensaje" => "Token inválido",
        "token_recibido" => $token
    ]);
    exit;
}

// ?? Respuesta OK
echo json_encode([
    "status" => "ok",
    "mensaje" => "Acceso permitido",
    "ip_detectada" => $ip_cliente
]);
