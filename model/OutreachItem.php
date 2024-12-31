<?php
// file: model/outreachItem.php

require_once 'File.php';

/**
 * Class outreachItem
 *
 * Represents an outreach record
 */
class OutreachItem
{

    // Datos comunes a todas las instancias

    /**
     * The id of the outreach item
     * @var string|null
     */
    private ?string $id;

    /**
     * The type of the outreach item
     * @var string|null
     */
    private ?string $type;

    /**
     * The title of the outreach item
     * @var string|null
     */
    private ?string $title;

    /**
     * The description of the outreach item
     * @var string|null
     */
    private ?string $description;

    /**
     * The associated file for the outreach item
     * @var File|null
     */
    private ?File $image;

    /**
     * The last modified date of the outreach item
     * @var string|null
     */
    private ?string $lastModified;

    // Datos específicos del tipo de instancia

    /**
     * The external URL for additional information
     * @var string|null
     */
    private ?string $externalURL;

    /**
     * The file associated with the outreach item
     * @var File|null
     */
    private ?File $file;

    /**
     * The content of the outreach item
     * @var string|null
     */
    private ?string $pageContent;

    // Variables de control para cambio de imagen

    /**
     * The new image of the member
     * @var File|null
     */
    private ?File $newImage;
    /**
     * Indicates if the image has changed
     * @var bool
     */
    private bool $isImageChanged;

    // Variables de control para cambio de fichero

    /**
     * The new file of the item
     * @var File|null
     */
    private ?File $newFile;
    /**
     * Indicates if the file has changed
     * @var bool
     */
    private bool $isFileChanged;

    /**
     * @param string|null $id
     * @param string|null $type
     * @param string|null $title
     * @param string|null $description
     * @param File|null $image
     * @param string|null $lastModified
     * @param File|null $file
     * @param string|null $externalURL
     * @param string|null $pageContent
     * @param File|null $newImage
     * @param File|null $newFile
     * @param bool $isImageChanged
     * @param bool $isFileChanged
     */
    public function __construct(
        ?string $id = null,
        ?string $type = null,
        ?string $title = null,
        ?string $description = null,
        ?File $image = null,
        ?string $lastModified = null,
        ?File $file = null,
        ?string $externalURL = null,
        ?string $pageContent = null,
        ?File $newImage = null,
        ?File $newFile = null,
        bool $isImageChanged = false,
        bool $isFileChanged = false
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->title = $title;
        $this->description = $description;
        $this->image = $image;
        $this->lastModified = $lastModified;
        $this->file = $file;
        $this->externalURL = $externalURL;
        $this->pageContent = $pageContent;
        $this->newImage = $newImage;
        $this->newFile = $newFile;
        $this->isImageChanged = $isImageChanged;
        $this->isFileChanged = $isFileChanged;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return File|null
     */
    public function getImage(): ?File
    {
        return $this->image;
    }

    /**
     * @param File|null $image
     */
    public function setImage(?File $image): void
    {
        $this->image = $image;
    }

    /**
     * @return string|null
     */
    public function getLastModified(): ?string
    {
        return $this->lastModified;
    }

//    /**
//     * @param string|null $lastModified
//     */
//    public function setLastModified(?string $lastModified): void
//    {
//        $this->lastModified = $lastModified;
//    }

    /**
     * @return string|null
     */
    public function getExternalURL(): ?string
    {
        return $this->externalURL;
    }

    /**
     * @param string|null $externalURL
     */
    public function setExternalURL(?string $externalURL): void
    {
        $this->externalURL = $externalURL;
    }

    /**
     * @return File|null
     */
    public function getFile(): ?File
    {
        return $this->file;
    }

    /**
     * @param File|null $file
     */
    public function setFile(?File $file): void
    {
        $this->file = $file;
    }

    /**
     * @return string|null
     */
    public function getPageContent(): ?string
    {
        return $this->pageContent;
    }

    /**
     * @param string|null $pageContent
     */
    public function setPageContent(?string $pageContent): void
    {
        $this->pageContent = $pageContent;
    }

    /**
     * @return File|null
     */
    public function getNewImage(): ?File
    {
        return $this->newImage;
    }

    /**
     * @param File|null $newImage
     */
    public function setNewImage(?File $newImage): void
    {
        $this->newImage = $newImage;
        $this->isImageChanged = true;
    }

    /**
     * @return File|null
     */
    public function getNewFile(): ?File
    {
        return $this->newFile;
    }

    /**
     * @param File|null $newFile
     */
    public function setNewFile(?File $newFile): void
    {
        $this->newFile = $newFile;
        $this->isFileChanged = true;
    }


    /**
     * Indicates if the image has changed
     *
     * @return bool
     */
    public function isImageChanged(): bool
    {
        return $this->isImageChanged;
    }

    public function resetImageChangedFlag(): void
    {
        $this->isImageChanged = false;
    }

    /**
     * Indicates if the file has changed
     *
     * @return bool
     */
    public function isFileChanged(): bool
    {
        return $this->isFileChanged;
    }

    public function resetFileChangedFlag(): void
    {
        $this->isFileChanged = false;
    }
}
