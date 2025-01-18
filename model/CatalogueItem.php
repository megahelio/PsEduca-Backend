<?php

class CatalogueItem
{
    private ?string $id;
    private ?string $acronym;
    private ?string $name;
    private ?File $image;
    private ?string $yearMinAge;
    private ?string $monthMinAge;
    private ?string $yearMaxAge;
    private ?string $monthMaxAge;
    private ?string $authors;
    private ?string $length;
    private ?string $description;
    private ?string $note;

    private array $files;
    private array $links;

    private array $areas;
    private array $tags;
    private array $resourceTypes;
    private array $formats;
    private array $applicationModes;

    private ?File $newImage;
    private bool $isImageChanged;

    private array $newFiles;
    private array $filesToDelete;

    private array $newLinks;
    private array $linksToDelete;

    public function __construct(
        ?string $id = null,
        ?string $acronym = null,
        ?string $name = null,
        ?File $image = null,
        ?string $yearMinAge = null,
        ?string $monthMinAge = null,
        ?string $yearMaxAge = null,
        ?string $monthMaxAge = null,
        ?string $authors = null,
        ?string $length = null,
        ?string $description = null,
        ?string $note = null,

        ?array $files = null,
        ?array $links = null,

        ?array $areas = null,
        ?array $tags = null,
        ?array $resourceTypes = null,
        ?array $formats = null,
        ?array $applicationModes = null,

        ?File $newImage = null,
        ?bool $isImageChanged = false,

        ?array $newFiles = null,
        ?array $filesToDelete = null,

        ?array $newLinks = null,
        ?array $linksToDelete = null
    ) {
        $this->id = $id;
        $this->acronym = $acronym;
        $this->name = $name;
        $this->image = $image;
        $this->yearMinAge = $yearMinAge;
        $this->monthMinAge = $monthMinAge;
        $this->yearMaxAge = $yearMaxAge;
        $this->monthMaxAge = $monthMaxAge;
        $this->authors = $authors;
        $this->length = $length;
        $this->description = $description;
        $this->note = $note;

        $this->files = $files??[];
        $this->links = $links??[];

        $this->areas = $areas??[];
        $this->tags = $tags??[];
        $this->resourceTypes = $resourceTypes??[];
        $this->formats = $formats??[];
        $this->applicationModes = $applicationModes??[];

        $this->newImage = $newImage;
        $this->isImageChanged = $isImageChanged;

        $this->newFiles = $newFiles??[];
        $this->filesToDelete = $filesToDelete??[];

        $this->newLinks = $newLinks??[];
        $this->linksToDelete = $linksToDelete??[];
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
    public function getAcronym(): ?string
    {
        return $this->acronym;
    }

    /**
     * @param string|null $acronym
     */
    public function setAcronym(?string $acronym): void
    {
        $this->acronym = $acronym;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
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
    public function getYearMinAge(): ?string
    {
        return $this->yearMinAge;
    }

    /**
     * @param string|null $yearMinAge
     */
    public function setYearMinAge(?string $yearMinAge): void
    {
        $this->yearMinAge = $yearMinAge;
    }

    /**
     * @return string|null
     */
    public function getMonthMinAge(): ?string
    {
        return $this->monthMinAge;
    }

    /**
     * @param string|null $monthMinAge
     */
    public function setMonthMinAge(?string $monthMinAge): void
    {
        $this->monthMinAge = $monthMinAge;
    }

    /**
     * @return string|null
     */
    public function getMonthMaxAge(): ?string
    {
        return $this->monthMaxAge;
    }

    /**
     * @param string|null $monthMaxAge
     */
    public function setMonthMaxAge(?string $monthMaxAge): void
    {
        $this->monthMaxAge = $monthMaxAge;
    }

    /**
     * @return string|null
     */
    public function getYearMaxAge(): ?string
    {
        return $this->yearMaxAge;
    }

    /**
     * @param string|null $yearMaxAge
     */
    public function setYearMaxAge(?string $yearMaxAge): void
    {
        $this->yearMaxAge = $yearMaxAge;
    }

    /**
     * @return string|null
     */
    public function getAuthors(): ?string
    {
        return $this->authors;
    }

    /**
     * @param string|null $authors
     */
    public function setAuthors(?string $authors): void
    {
        $this->authors = $authors;
    }

    /**
     * @return string|null
     */
    public function getLength(): ?string
    {
        return $this->length;
    }

    /**
     * @param string|null $length
     */
    public function setLength(?string $length): void
    {
        $this->length = $length;
    }

    /**
     * @return string|null
     */
    public function getNote(): ?string
    {
        return $this->note;
    }

    /**
     * @param string|null $note
     */
    public function setNote(?string $note): void
    {
        $this->note = $note;
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
     * @return array
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * @param array $files
     */
    public function setFiles(array $files): void
    {
        $this->files = $files;
    }

    /**
     * @return array
     */
    public function getLinks(): array
    {
        return $this->links;
    }

    /**
     * @param array $links
     */
    public function setLinks(array $links): void
    {
        $this->links = $links;
    }

    /**
     * @return array
     */
    public function getAreas(): array
    {
        return $this->areas;
    }

    /**
     * @param array $areas
     */
    public function setAreas(array $areas): void
    {
        $this->areas = $areas;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @param array $tags
     */
    public function setTags(array $tags): void
    {
        $this->tags = $tags;
    }

    /**
     * @return array
     */
    public function getResourceTypes(): array
    {
        return $this->resourceTypes;
    }

    /**
     * @param array $resourceTypes
     */
    public function setResourceTypes(array $resourceTypes): void
    {
        $this->resourceTypes = $resourceTypes;
    }

    /**
     * @return array
     */
    public function getFormats(): array
    {
        return $this->formats;
    }

    /**
     * @param array $formats
     */
    public function setFormats(array $formats): void
    {
        $this->formats = $formats;
    }

    /**
     * @return array
     */
    public function getApplicationModes(): array
    {
        return $this->applicationModes;
    }

    /**
     * @param array $applicationModes
     */
    public function setApplicationModes(array $applicationModes): void
    {
        $this->applicationModes = $applicationModes;
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
     * @return array
     */
    public function getNewFiles(): array
    {
        return $this->newFiles;
    }

    public function addNewFile(File $file): void
    {
        $this->newFiles[] = $file;
    }


    /**
     * @return array
     */
    public function getFilesToDelete(): array
    {
        return $this->filesToDelete;
    }

    public function addFileToDelete(File $file): void
    {
        $this->filesToDelete[] = $file;
    }

    /**
     * @return array
     */
    public function getNewLinks(): array
    {
        return $this->newLinks;
    }

    public function addNewLink(Link $link): void
    {
        $this->newLinks[] = $link;
    }

    /**
     * @return array
     */
    public function getLinksToDelete(): array
    {
        return $this->linksToDelete;
    }

    public function addLinkToDelete(Link $link): void
    {
        $this->linksToDelete[] = $link;
    }
}