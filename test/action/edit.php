<?php

// ADD

// TESTS
//echo "<h1>Resultados de las pruebas</h1>";
//
//// Test para nombre de usuario demasiado corto
//test("ADD: Validar NOMBRE_USUARIO_MINIMO_F_KO", function() use ($tokenValido, $baseUrl) {
//    $response = makeRequest(
//        "POST",
//        "$baseUrl/?controller=user&action=add",
//        ["Authorization: Bearer $tokenValido"],
//        [
//            'userName' => 'usr',
//            'fullName' => 'Usuario de Prueba',
//            'role' => 'ADMIN_GLOBAL',
//            'password' => 'password123'
//        ]
//    );
//
//    validateResponse(
//        response: $response,
//        expectedHTTPCode: 400,
//        expectedOk: false,
//        expectedCodes: ['NOMBRE_USUARIO_MINIMO_F_KO']
//    );
//});
//
//// Test para nombre de usuario demasiado largo
//test("ADD: Validar NOMBRE_USUARIO_MAXIMO_F_KO", function() use ($tokenValido, $baseUrl) {
//    $response = makeRequest(
//        "POST",
//        "$baseUrl/?controller=user&action=add",
//        ["Authorization: Bearer $tokenValido"],
//        [
//            'userName' => str_repeat('a', 255),
//            'fullName' => 'Usuario de Prueba',
//            'role' => 'ADMIN_GLOBAL',
//            'password' => 'password123'
//        ]
//    );
//
//    validateResponse(
//        response: $response,
//        expectedHTTPCode: 400,
//        expectedOk: false,
//        expectedCodes: ['NOMBRE_USUARIO_MAXIMO_F_KO']
//    );
//});
//
//// Test para caracteres inválidos en nombre de usuario
//test("ADD: Validar NOMBRE_USUARIO_CARACTERES_F_KO", function() use ($tokenValido, $baseUrl) {
//    $response = makeRequest(
//        "POST",
//        "$baseUrl/?controller=user&action=add",
//        ["Authorization: Bearer $tokenValido"],
//        [
//            'userName' => 'user@prueba',
//            'fullName' => 'Usuario de Prueba',
//            'role' => 'ADMIN_GLOBAL',
//            'password' => 'password123'
//        ]
//    );
//
//    validateResponse(
//        response: $response,
//        expectedHTTPCode: 400,
//        expectedOk: false,
//        expectedCodes: ['NOMBRE_USUARIO_CARACTERES_F_KO']
//    );
//});
//
//// Test para longitud mínima en el nombre completo
//test("ADD: Validar NOMBRE_COMPLETO_MINIMO_F_KO", function() use ($tokenValido, $baseUrl) {
//    $response = makeRequest(
//        "POST",
//        "$baseUrl/?controller=user&action=add",
//        ["Authorization: Bearer $tokenValido"],
//        [
//            'userName' => 'usuarioPrueba',
//            'fullName' => 'abc',
//            'role' => 'ADMIN_GLOBAL',
//            'password' => 'password123'
//        ]
//    );
//
//    validateResponse(
//        response: $response,
//        expectedHTTPCode: 400,
//        expectedOk: false,
//        expectedCodes: ['NOMBRE_COMPLETO_MINIMO_F_KO']
//    );
//});
//
//// Test para nombre completo demasiado largo
//test("ADD: Validar NOMBRE_COMPLETO_MAXIMO_F_KO", function() use ($tokenValido, $baseUrl) {
//    $response = makeRequest(
//        "POST",
//        "$baseUrl/?controller=user&action=add",
//        ["Authorization: Bearer $tokenValido"],
//        [
//            'userName' => 'usuarioPrueba',
//            'fullName' => str_repeat('a', 255),
//            'role' => 'ADMIN_GLOBAL',
//            'password' => 'password123'
//        ]
//    );
//
//    validateResponse(
//        response: $response,
//        expectedHTTPCode: 400,
//        expectedOk: false,
//        expectedCodes: ['NOMBRE_COMPLETO_MAXIMO_F_KO']
//    );
//});
//
//// Test para caracteres inválidos en nombre completo
//test("ADD: Validar NOMBRE_COMPLETO_CARACTERES_F_KO", function() use ($tokenValido, $baseUrl) {
//    $response = makeRequest(
//        "POST",
//        "$baseUrl/?controller=user&action=add",
//        ["Authorization: Bearer $tokenValido"],
//        [
//            'userName' => 'usuarioPrueba',
//            'fullName' => 'Nombre@Inválido',
//            'role' => 'ADMIN_GLOBAL',
//            'password' => 'password123'
//        ]
//    );
//
//    validateResponse(
//        response: $response,
//        expectedHTTPCode: 400,
//        expectedOk: false,
//        expectedCodes: ['NOMBRE_COMPLETO_CARACTERES_F_KO']
//    );
//});
//
//// Test para contraseña demasiado corta
//test("ADD: Validar CONTRASENHA_MINIMO_F_KO", function() use ($tokenValido, $baseUrl) {
//    $response = makeRequest(
//        "POST",
//        "$baseUrl/?controller=user&action=add",
//        ["Authorization: Bearer $tokenValido"],
//        [
//            'userName' => 'usuarioPrueba',
//            'fullName' => 'Usuario de Prueba',
//            'role' => 'ADMIN_GLOBAL',
//            'password' => '123'
//        ]
//    );
//
//    validateResponse(
//        response: $response,
//        expectedHTTPCode: 400,
//        expectedOk: false,
//        expectedCodes: ['CONTRASENHA_MINIMO_F_KO']
//    );
//});
//
//// Test para contraseña demasiado largo
//test("ADD: Validar CONTRASENHA_MAXIMO_F_KO", function() use ($tokenValido, $baseUrl) {
//    $response = makeRequest(
//        "POST",
//        "$baseUrl/?controller=user&action=add",
//        ["Authorization: Bearer $tokenValido"],
//        [
//            'userName' => 'usuarioPrueba',
//            'fullName' => 'Usuario de Prueba',
//            'role' => 'ADMIN_GLOBAL',
//            'password' => str_repeat('a', 255)
//        ]
//    );
//
//    validateResponse(
//        response: $response,
//        expectedHTTPCode: 400,
//        expectedOk: false,
//        expectedCodes: ['CONTRASENHA_MAXIMO_F_KO']
//    );
//});
//
//// Test para caracteres inválidos en contraseña
//test("ADD: Validar CONTRASENHA_CARACTERES_F_KO", function() use ($tokenValido, $baseUrl) {
//    $response = makeRequest(
//        "POST",
//        "$baseUrl/?controller=user&action=add",
//        ["Authorization: Bearer $tokenValido"],
//        [
//            'userName' => 'usuarioPrueba',
//            'fullName' => 'Usuario de Prueba',
//            'role' => 'ADMIN_GLOBAL',
//            'password' => 'contraseña$invalida'
//        ]
//    );
//
//    validateResponse(
//        response: $response,
//        expectedHTTPCode: 400,
//        expectedOk: false,
//        expectedCodes: ['CONTRASENHA_CARACTERES_F_KO']
//    );
//});
//
//// Test para rol inválido
//test("ADD: Validar ROL_F_KO", function() use ($tokenValido, $baseUrl) {
//    $response = makeRequest(
//        "POST",
//        "$baseUrl/?controller=user&action=add",
//        ["Authorization: Bearer $tokenValido"],
//        [
//            'userName' => 'usuarioPrueba',
//            'fullName' => 'Usuario de Prueba',
//            'role' => 'ROL_INVALIDO',
//            'password' => 'password123'
//        ]
//    );
//
//    validateResponse(
//        response: $response,
//        expectedHTTPCode: 400,
//        expectedOk: false,
//        expectedCodes: ['ROL_F_KO']
//    );
//});

// Test para añadir un usuario válido
//test("ADD: Añadir un usuario válido", function() use ($tokenValido, $baseUrl) {
//    $response = makeRequest(
//        "POST",
//        "$baseUrl/?controller=user&action=add",
//        ["Authorization: Bearer $tokenValido"],
//        [
//            'userName' => 'usuarioPrueba',
//            'fullName' => 'Usuario de Prueba',
//            'role' => 'ADMIN_GLOBAL',
//            'password' => 'password123'
//        ]
//    );
//
//    validateResponse(
//        response: $response,
//        expectedHTTPCode: 201,
//        expectedOk: true,
//        expectedCodes: ['RECORDSET_DATA']
//    );
//});
//
// Más tests...
//
//echo "<h2>Fin de las pruebas</h2>";


// EDIT


//// Test para ID_MINIMO_F_KO
//test("EDIT: Validar id mínimo >= 0", function() use ($tokenValido, $baseUrl) {
//    $response = makeRequest(
//        "POST",
//        "$baseUrl/?controller=user&action=edit",
//        ["Authorization: Bearer $tokenValido"],
//        [
//            'id' => -1
//        ]
//    );
//
//    validateResponse(
//        response: $response,
//        expectedHTTPCode: 400,
//        expectedOk: false,
//        expectedCodes: ['ID_MINIMO_F_KO']
//    );
//});
//
//// Test para ID_INVALIDO_F_KO
//test("EDIT: Validar id es un número positivo", function() use ($tokenValido, $baseUrl) {
//    $response = makeRequest(
//        "POST",
//        "$baseUrl/?controller=user&action=edit",
//        ["Authorization: Bearer $tokenValido"],
//        [
//            'id' => "abc"
//        ]
//    );
//
//    validateResponse(
//        response: $response,
//        expectedHTTPCode: 400,
//        expectedOk: false,
//        expectedCodes: ['ID_INVALIDO_F_KO']
//    );
//});
//
//// Test para NOMBRE_USUARIO_MINIMO_F_KO
//test("EDIT: Validar longitud mínima del nombre de usuario", function() use ($tokenValido, $baseUrl) {
//    $response = makeRequest(
//        "POST",
//        "$baseUrl/?controller=user&action=edit",
//        ["Authorization: Bearer $tokenValido"],
//        [
//            'id' => 10,
//            'userName' => 'abc'
//        ]
//    );
//
//    validateResponse(
//        response: $response,
//        expectedHTTPCode: 400,
//        expectedOk: false,
//        expectedCodes: ['NOMBRE_USUARIO_MINIMO_F_KO']
//    );
//});
//
//// Test para NOMBRE_USUARIO_MAXIMO_F_KO
//test("EDIT: Validar longitud máxima del nombre de usuario", function() use ($tokenValido, $baseUrl) {
//    $userName = str_repeat('a', 255); // Generar nombre de 255 caracteres
//    $response = makeRequest(
//        "POST",
//        "$baseUrl/?controller=user&action=edit",
//        ["Authorization: Bearer $tokenValido"],
//        [
//            'id' => 10,
//            'userName' => $userName
//        ]
//    );
//
//    validateResponse(
//        response: $response,
//        expectedHTTPCode: 400,
//        expectedOk: false,
//        expectedCodes: ['NOMBRE_USUARIO_MAXIMO_F_KO']
//    );
//});
//
//// Test para NOMBRE_USUARIO_CARACTERES_F_KO
//test("EDIT: Validar caracteres permitidos en el nombre de usuario", function() use ($tokenValido, $baseUrl) {
//    $response = makeRequest(
//        "POST",
//        "$baseUrl/?controller=user&action=edit",
//        ["Authorization: Bearer $tokenValido"],
//        [
//            'id' => 10,
//            'userName' => 'usuario@123'
//        ]
//    );
//
//    validateResponse(
//        response: $response,
//        expectedHTTPCode: 400,
//        expectedOk: false,
//        expectedCodes: ['NOMBRE_USUARIO_CARACTERES_F_KO']
//    );
//});
//
//// Test para ROL_F_KO
//test("EDIT: Validar rol permitido", function() use ($tokenValido, $baseUrl) {
//    $response = makeRequest(
//        "POST",
//        "$baseUrl/?controller=user&action=edit",
//        ["Authorization: Bearer $tokenValido"],
//        [
//            'id' => 10,
//            'role' => 'ADMINISTRADOR'
//        ]
//    );
//
//    validateResponse(
//        response: $response,
//        expectedHTTPCode: 400,
//        expectedOk: false,
//        expectedCodes: ['ROL_F_KO']
//    );
//});