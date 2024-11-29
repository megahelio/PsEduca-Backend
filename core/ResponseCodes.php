<?php
// Archivo: ResponseCodes.php
class ResponseCodes {
    // Success codes
    const RECORDSET_DATA = 'RECORDSET_DATA'; // Datos recuperados correctamente.
    const RECORDSET_EMPTY = 'RECORDSET_EMPTY'; // No se encontraron datos.

    // Error codes
    const NOT_FOUND_KO = 'NOT_FOUND_KO'; // No se encontró el recurso solicitado (cuando supone un error, por ejemplo, intento de login con token válido y no se encuentra el usuario).

    const USER_CREDENTIALS_INVALID_KO = 'USER_CREDENTIALS_INVALID_KO'; // Contraseña de usuario inválida.

    const FORBIDDEN_ACCESS_KO = 'FORBIDDEN_ACCESS_KO'; // Acceso prohibido.

    const AUTHENTICATION_REQUIRED_KO = 'AUTHENTICATION_REQUIRED_KO'; // La autenticación es requerida.
    const AUTHENTICATION_TYPE_NOT_SUPPORTED_KO = 'AUTHENTICATION_TYPE_NOT_SUPPORTED_KO'; // Tipo de autenticación no soportado.
    const AUTHENTICATION_EXPIRED_KO = 'AUTHENTICATION_EXPIRED_KO'; // Token expirado.
    const AUTHENTICATION_INVALID_KO = 'AUTHENTICATION_INVALID_KO'; // Token inválido.

    const INTERNAL_SERVER_ERROR_KO = 'INTERNAL_SERVER_ERROR_KO'; // Error interno del servidor.

    // Errores de validación de USUARIOS

    const USUARIO_NO_ENCONTRADO_A_KO = 'USUARIO_NO_ENCONTRADO_A_KO'; // Usuario no encontrado.
}