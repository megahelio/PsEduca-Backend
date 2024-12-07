<?php
// file: model/EducationItem.php

require_once 'File.php';

/**
 * Class EducationItem
 *
 * Represents an education record
 */
class EducationItem
{
    /**
     * The id of the education item
     * @var string|null
     */
    private ?string $id;

    /**
     * The type of the education item
     * @var string|null
     */
    private ?string $type;

    /**
     * The title of the education item
     * @var string|null
     */
    private ?string $title;

    /**
     * The description of the education item
     * @var string|null
     */
    private ?string $description;

    /**
     * The associated file for the education item
     * @var File|null
     */
    private ?File $image;

    /**
     * The reference URL for additional information
     * @var string|null
     */
    private ?string $referenceURL;

    /**
     * The start year of the education period
     * @var string|null
     */
    private ?string $initYear;

    /**
     * The end year of the education period
     * @var string|null
     */
    private ?string $endYear;

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
     * Constructor
     *
     * @param string|null $id The id of the education item
     * @param string|null $title The title of the education item
     * @param string|null $description The description of the education item
     * @param File|null $image The associated file
     * @param string|null $referenceURL The reference URL
     * @param string|null $initYear The start year of the education period
     * @param string|null $endYear The end year of the education period
     */
    public function __construct(
        ?string $id = null,
        ?string $type = null,
        ?string $title = null,
        ?string $description = null,
        ?File   $image = null,
        ?string $referenceURL = null,
        ?string $initYear = null,
        ?string $endYear = null,
        ?File  $newImage = null,
        bool $isImageChanged = false
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->title = $title;
        $this->description = $description;
        $this->image = $image;
        $this->referenceURL = $referenceURL;
        $this->initYear = $initYear;
        $this->endYear = $endYear;
        $this->newImage = $newImage;
        $this->isImageChanged = $isImageChanged;
    }

    /**
     * Gets the id of the education item
     *
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Sets the id of the education item
     *
     * @param string $id
     * @return void
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * Gets the type of the education item
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Sets the type of the education item
     *
     * @param string|null $type
     * @return void
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    /**
     * Gets the title of the education item
     *
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Sets the title of the education item
     *
     * @param string|null $title
     * @return void
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * Gets the description of the education item
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Sets the description of the education item
     *
     * @param string|null $description
     * @return void
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * Gets the associated file
     *
     * @return File|null
     */
    public function getImage(): ?File
    {
        return $this->image;
    }

    /**
     * Sets the associated file
     *
     * @param File|null $image
     * @return void
     */
    public function setImage(?File $image): void
    {
        $this->image = $image;
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
     * Gets the start year of the education period
     *
     * @return string|null
     */
    public function getInitYear(): ?string
    {
        return $this->initYear;
    }

    /**
     * Sets the start year of the education period
     *
     * @param string|null $initYear
     * @return void
     */
    public function setInitYear(?string $initYear): void
    {
        $this->initYear = $initYear;
    }

    /**
     * Gets the end year of the education period
     *
     * @return string|null
     */
    public function getEndYear(): ?string
    {
        return $this->endYear;
    }

    /**
     * Sets the end year of the education period
     *
     * @param string|null $endYear
     * @return void
     */
    public function setEndYear(?string $endYear): void
    {
        $this->endYear = $endYear;
    }
}
