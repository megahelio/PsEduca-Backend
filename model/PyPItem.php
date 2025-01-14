<?php
// file: model/PyPItem.php

class PyPItem
{
    /**
     * The id of the PyP item
     * @var string|null
     */
    private ?string $id;

    /**
     * The title of the PyP item
     * @var string|null
     */
    private ?string $title;

    /**
     * The description of the PyP item
     * @var string|null
     */
    private ?string $description;

    /**
     * The associated file for the PyP item
     * @var File|null
     */
    private ?File $image;

    /**
     * The external URL for additional information
     * @var string|null
     */
    private ?string $externalURL;

    /**
     * The new image of the PyP item
     * @var File|null
     */
    private ?File $newImage;
    /**
     * Indicates if the image has changed
     * @var bool
     */
    /**
     * Indicates if the image has changed
     * @var bool
     */
    private bool $isImageChanged;

    /**
     * @param string|null $id
     * @param string|null $title
     * @param string|null $description
     * @param File|null $image
     * @param string|null $externalURL
     * @param File|null $newImage
     * @param bool $isImageChanged
     */
    public function __construct(
        ?string $id = null,
        ?string $title = null,
        ?string $description = null,
        ?File $image = null,
        ?string $externalURL = null,

        ?File $newImage = null,
        bool $isImageChanged = false
    ){
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->image = $image;
        $this->externalURL = $externalURL;

        $this->newImage = $newImage;
        $this->isImageChanged = $isImageChanged;
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
     * @return bool
     */
    public function isImageChanged(): bool
    {
        return $this->isImageChanged;
    }

    /**
     * Resets the image changed flag
     */
    public function resetImageChangedFlag(): void
    {
        $this->isImageChanged = false;
    }

}