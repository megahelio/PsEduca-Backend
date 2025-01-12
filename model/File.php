<?php
// file: model/File.php

/**
 * Class File
 *
 * Represents a file
 */
class File
{
    /**
     * The path of the file
     * @var string|null
     */
    private ?string $fileTmpPath;

    /**
     * The size of the file in bytes
     * @var int|null
     */
    private ?int $fileSize;

    /**
     * The MIME type of the file
     * @var string|null
     */
    private ?string $mimeType;

    /**
     * The original name of the file
     * @var string|null
     */
    private ?string $originalFileName;

    /**
     * The new name of the file
     * @var string|null
     */
    private ?string $storageFileName;

    /**
     * The brief description of the file.
     * Note: It is only used in the context of catalogue items.
     * @var string|null
     */
    private ?string $description;

    /**
     * The id of the file
     * Note: It is only used in the context of catalogue items.
     * @var string|null
     */
    private ?string $id;

    /**
     * The constructor
     *
     * @param string|null $originalFileName The name of the file
     * @param int|null $fileSize The size of the file in bytes
     * @param string|null $mimeType The MIME type of the file
     * @param string|null $fileTempPath The path of the file
     * @param string|null $storageFileName The new name of the file
     * @param string|null $description The brief description of the file
     * @param string|null $id The id of the file
     */
    public function __construct(
        ?string $fileTempPath = null,
        ?string $originalFileName = null,
        ?string $storageFileName = null,
        ?int    $fileSize = null,
        ?string $mimeType = null,
        ?string $description = null,
        ?string $id = null
    ) {
        $this->fileTmpPath = $fileTempPath;
        $this->originalFileName = $originalFileName;
        $this->storageFileName = $storageFileName;
        $this->fileSize = $fileSize;
        $this->mimeType = $mimeType !== null ? strtolower($mimeType) : null;
        $this->description = $description;
        $this->id = $id;
    }

    /**
     * Creates a File instance from an existing file path.
     *
     * @param string $fileName The path of the file on the disk.
     * @return File The File instance.
     * @throws FileException If the file does not exist or cannot be read.
     */
    public static function fromExistingFile(string $fileName, string $id = null, string $description = null): File
    {
        $filePath = UPLOAD_FOLDER . $fileName;
        // Verificar si el archivo existe
        if (!file_exists($filePath)) {
            throw new FileException("El archivo especificado no existe: $filePath");
        }

        // Obtener información del archivo
        $fileSize = filesize($filePath);
        $mimeType = mime_content_type($filePath);
        $storageFileName = pathinfo($filePath, PATHINFO_BASENAME);

        // Crear una instancia de File con los datos del archivo existente
        return new self(
            null, // El archivo ya está en su ubicación definitiva
            null, // No conservamos el nombre original
            $storageFileName,
            $fileSize,
            $mimeType,
            $description,
            $id
        );
    }

    /**
     * @throws FileException
     */
    public function save(): void
    {
        // Crear el directorio si no existe
        if (!file_exists(UPLOAD_FOLDER)) {
            mkdir(UPLOAD_FOLDER, 0777, true);
        }

        // Ruta completa con el nuevo nombre
        $destPath = UPLOAD_FOLDER . $this->getStorageFileName();

        // Mover el archivo desde la carpeta temporal al destino
        if (!move_uploaded_file($this->fileTmpPath, $destPath)) {
            throw new FileException("Hubo un error al mover el archivo $this->originalFileName al directorio "
                . UPLOAD_FOLDER . " con el nombre" . $this->getStorageFileName() . ".");
        }
    }

    public function exists(): bool
    {
        return file_exists(UPLOAD_FOLDER . $this->getStorageFileName());
    }

    public function delete(): bool
    {
        // Ruta completa del archivo a eliminar
        $filePath = UPLOAD_FOLDER . $this->getStorageFileName();

        // Verificar si el archivo existe y eliminarlo si existe
        return file_exists($filePath) && unlink($filePath);
    }


    public function getStorageFileName(): string
    {
        if ($this->storageFileName === null){
            $this->generateRandomFileName();
        }
        return $this->storageFileName;
    }

    private function generateRandomFileName(): void
    {
        $this->storageFileName = uniqid('', true) . '.'
            . pathinfo($this->originalFileName, PATHINFO_EXTENSION);
    }

    /**
     * Gets the name of the file
     *
     * @return string|null The name of the file
     */
    public function getOriginalFileName(): ?string
    {
        return $this->originalFileName;
    }

    /**
     * Sets the name of the file
     *
     * @param string $originalFileName The name of the file
     * @return void
     */
    public function setOriginalFileName(string $originalFileName): void
    {
        $this->originalFileName = $originalFileName;
    }

    /**
     * Gets the size of the file in bytes
     *
     * @return int|null The size of the file in bytes
     */
    public function getFileSize(): ?int
    {
        return $this->fileSize;
    }

    /**
     * Sets the size of the file in bytes
     *
     * @param int $fileSize The size of the file in bytes
     * @return void
     */
    public function setFileSize(int $fileSize): void
    {
        $this->fileSize = $fileSize;
    }

    /**
     * Gets the MIME type of the file
     *
     * @return string|null The MIME type of the file
     */
    public function getMimeType(): ?string
    {
        return strtolower($this->mimeType);
    }

    /**
     * Sets the MIME type of the file
     *
     * @param string $mimeType The MIME type of the file
     * @return void
     */
    public function setMimeType(string $mimeType): void
    {
        $this->mimeType = $mimeType;
    }

    /**
     * Gets the brief description of the file
     *
     * @return string|null The brief description of the file
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Sets the brief description of the file
     *
     * @param string $description The brief description of the file
     * @return void
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * Gets the id of the file
     *
     * @return string|null The id of the file
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Sets the id of the file
     *
     * @param string $id The id of the file
     * @return void
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }
}
