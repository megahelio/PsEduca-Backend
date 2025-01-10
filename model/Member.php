<?php
// file: model/Miembro.php

require_once 'File.php';

/**
 * Class Miembro
 *
 * Represents a Member
 */
class Member
{
    /**
     * The id of the member
     * @var string|null
     */
    private ?string $id;
    /**
     * The name of the member
     * @var string|null
     */
    private ?string $name;

    /**
     * The email of the member
     * @var string|null
     */
    private ?string $email;

    /**
     * The description of the member
     * @var string|null
     */
    private ?string $description;

    /**
     * The reference URL for additional information about the member
     * @var string|null
     */
    private ?string $referenceURL;

    /**
     * The image associated with the member
     * @var File|null
     */
    private ?File $image;

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

    /**
     * The constructor
     *
     * @param string|null $name The name of the member
     * @param string|null $email The email of the member
     * @param string|null $description The description of the member
     * @param string|null $referenceURL The reference URL
     * @param File|null $image The image of the member
     */
    public function __construct(
        ?string $id = null,
        ?string $name = null,
        ?string $email = null,
        ?string $description = null,
        ?string $referenceURL = null,
        ?File   $image = null,
        ?File   $newImage = null,
        bool    $isImageChanged = false
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->description = $description;
        $this->referenceURL = $referenceURL;
        $this->image = $image;
        $this->newImage = $newImage;
        $this->isImageChanged = $isImageChanged;
    }

    /**
     * Gets the id of the member
     *
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Sets the id of the member
     *
     * @param string $id
     * @return void
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * Gets the name of the member
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sets the name of the member
     *
     * @param string|null $name
     * @return void
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * Gets the email of the member
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Sets the email of the member
     *
     * @param string|null $email
     * @return void
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * Gets the description of the member
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Sets the description of the member
     *
     * @param string|null $description
     * @return void
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * Gets the reference URL
     *
     * @return string|null
     */
    public function getReferenceURL(): ?string
    {
        return $this->referenceURL;
    }

    /**
     * Sets the reference URL
     *
     * @param string|null $referenceURL
     * @return void
     */
    public function setReferenceURL(?string $referenceURL): void
    {
        $this->referenceURL = $referenceURL;
    }

    /**
     * Gets the image of the member
     *
     * @return File|null
     */
    public function getImage(): ?File
    {
        return $this->image;
    }

    /**
     * Sets the image of the member
     *
     * @param File|null $file
     * @return void
     */
    public function setImage(?File $file): void
    {
        $this->image = $file;
    }

    /**
     * Sets the image of the member
     *
     * @param File|null $newImage
     * @return void
     */
    public function setNewImage(?File $newImage): void
    {
        $this->newImage = $newImage;
        $this->isImageChanged = true;
    }

    /**
     * Gets the new image of the member
     *
     * @return File|null
     */
    public function getNewImage(): ?File
    {
        return $this->newImage;
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
}
