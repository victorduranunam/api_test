<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

// ==========================
// CONFIGURACIÓN
// ==========================
$ip_permitida  = '132.248.54.219';
$token_valido  = 'MI_TOKEN_SECRETO';

// ==========================
// FUNCIONES
// ==========================
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

// ==========================
// DATOS DE LA PETICIÓN
// ==========================
$ip_cliente = obtenerIPReal();
$token      = $_SERVER['HTTP_X_API_KEY'] ?? '';

// ==========================
// VALIDACIONES
// ==========================

// Validar IP
if ($ip_cliente !== $ip_permitida) {
    http_response_code(403);

    echo json_encode([
        "status" => "error",
        "mensaje" => "IP no autorizada",
        "ip_detectada" => $ip_cliente
    ], JSON_PRETTY_PRINT);

    exit;
}

// Validar token
if ($token !== $token_valido) {
    http_response_code(401);

    echo json_encode([
        "status" => "error",
        "mensaje" => "Token inválido",
        "token_recibido" => $token
    ], JSON_PRETTY_PRINT);

    exit;
}

// ==========================
// DATOS DE RESPUESTA
// ==========================
$personas = [
    [
        "id" => 1,
        "nombre" => "Juan Pérez",
        "edad" => 30,
        "correo" => "juan@example.com"
    ],
    [
        "id" => 2,
        "nombre" => "María López",
        "edad" => 25,
        "correo" => "maria@example.com"
    ],
    [
        "id" => 3,
        "nombre" => "Carlos García",
        "edad" => 40,
        "correo" => "carlos@example.com"
    ],
    [
        "id" => 4,
        "nombre" => "Ana Torres",
        "edad" => 28,
        "correo" => "ana@example.com"
    ]
];

// ==========================
// RESPUESTA EXITOSA
// ==========================
echo json_encode([
    "status" => "ok",
    "mensaje" => "Acceso permitido",
    "ip_detectada" => $ip_cliente,
    "data" => [
        "personas" => $personas
    ]
], JSON_PRETTY_PRINT);
