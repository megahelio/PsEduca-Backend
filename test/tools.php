<?php
require_once __DIR__ . "/../core/config_file.php";

// Configuración inicial
header("Content-Type: text/html; charset=UTF-8");

function test($description, $callback) {
    static $testCount = 1;
    try {
        $callback();
        echo "<p style='color: green;'>✔ Test $testCount: $description</p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>✘ Test $testCount: $description</p>";
        echo "<p style='color: red; margin-left: 20px;'>→ Error: " . $e->getMessage() . "</p>";
    }
    $testCount++;
}

function assertContains($expected, array $actual, $message = '')
{
    if (!in_array($expected, $actual)) {
        throw new Exception($message . " Se esperaba '$expected', no se encontró. Valores encontrados: " . implode(', ', $actual));
    }
}

function assertEquals($expected, $actual, $message = '') {
    if ($expected !== $actual) {
        throw new Exception($message . " Se esperaba '$expected', se obtuvo '$actual'.");
    }
}

function assertNotEquals($expected, $actual, $message = '') {
    if ($expected === $actual) {
        throw new Exception($message . " Se esperaba que los valores fueran distintos.");
    }
}
{

}

function assertTrue($condition, $message = '') {
    if (!$condition) {
        throw new Exception($message ?: "Condition is not true.");
    }
}

function assertFalse($condition, $message = '') {
    if ($condition) {
        throw new Exception($message ?: "Condition is not false.");
    }
}

function validateResponse(array $response, int $expectedHTTPCode, bool $expectedOk, array $expectedCodes)
{
    // Validar código HTTP
    assertEquals($expectedHTTPCode, $response['httpCode'], "El código HTTP no es el esperado.");
    // Validar ok
    if ($expectedOk) {
        assertTrue($response['body']['ok'], "La respuesta indica fallo (ok = false).");
    } else {
        assertFalse($response['body']['ok'], "La respuesta indica éxito (ok = true).");
    }
    // Validar códigos de respuesta
    foreach ($expectedCodes as $expectedCode) {
        assertContains($expectedCode, $response['body']['code'], "El código de respuesta no es correcto.");
    }
}

// Función para realizar peticiones HTTP
function makeRequest($method, $url, $headers = [], $data = []): array
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    if ($headers) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    return ['httpCode' => $httpCode, 'body' => json_decode($response, true)];
}

