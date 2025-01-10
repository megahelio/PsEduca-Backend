<?php

use JetBrains\PhpStorm\NoReturn;

require_once(__DIR__."/../model/User.php");
require_once(__DIR__ . "/../service/UserService.php");

/**
* Class BaseController
*
* Superclass for endpoints controllers.
*/
class BaseController {

    /**
     * @param array $data
     * @param string $key
     * @return string|null
     */
    protected static function extractString(array $data, string $key): ?string
    {
        return $data[$key] ?? null;
    }

    /**
     * @return File|null
     */
    protected function extractFile(string $form_data_name): ?File
    {
        if (isset($_FILES[$form_data_name]) && $_FILES[$form_data_name]['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES[$form_data_name]['tmp_name'];// Ruta temporal del archivo
            $fileName = $_FILES[$form_data_name]['name'];// Nombre original del archivo
            $fileSize = $_FILES[$form_data_name]['size'];// Tamaño del archivo
            $fileType = $_FILES[$form_data_name]['type'];// Tipo MIME del archivo
            return new File($fileTmpPath, $fileName, null, $fileSize, $fileType);
        } else {
            return null;
        }
    }


    /**
     * Genera y envía una respuesta JSON con un código HTTP específico.
     *
     * @param int $httpCode Código de respuesta HTTP.
     * @param array $code Código(s) adicional(es) que se incluirán en la respuesta.
     * @param array|null $resource (Opcional) Datos adicionales para incluir en el JSON.
     * @return void Termina la ejecución del script.
     */
    #[NoReturn] static function generateHttpResponse(int $httpCode, array $code, array $resource = null): void
    {
        // Determinar si el código HTTP representa éxito
        $isSuccess = $httpCode >= 200 && $httpCode < 300;

        // Crear la respuesta
        $response = [
            'ok' => $isSuccess,
            'code' => $code,
        ];

        // Agregar el recurso si se proporciona
        if ($resource !== null) {
            $response['resource'] = $resource;
        }

        // Configurar el código de respuesta HTTP
        http_response_code($httpCode);

        // Enviar el JSON como respuesta
        header('Content-Type: application/json');
        die(json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }
}
