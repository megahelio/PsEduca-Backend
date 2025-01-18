<?php

require_once __DIR__ . "/../exception/ValidationException.php";
require_once __DIR__ . "/../exception/FileException.php";
require_once __DIR__ . "/../model/CatalogueItem.php";
require_once __DIR__ . "/../model/Link.php";
require_once __DIR__ . "/../mapper/CatalogueItemMapper.php";

class CatalogueService
{
    private CatalogueItemMapper $catalogueItemMapper;

    public function __construct()
    {
        $this->catalogueItemMapper = CatalogueItemMapper::getInstance();
    }

    /**
     * @throws ReflectionException
     * @throws ValidationException
     * @throws FileException
     */
    public function add(?string $acronym, ?string $name, ?File $image, ?string $yearMinAge, ?string $monthMinAge,
                        ?string $yearMaxAge, ?string $monthMaxAge, ?string $authors, ?string $length,
                        ?string $description, ?string $note, ?array $areas, ?array $tags, ?array $resourceTypes,
                        ?array $formats, ?array $applicationModes, ?array $files, ?array $links): CatalogueItem
    {
        $catalogueItem = new CatalogueItem(
            id: null,
            acronym: $acronym == "" ? null : $acronym,
            name: $name == "" ? null : $name,
            image: $image,
            yearMinAge: $yearMinAge == "" ? null : $yearMinAge,
            monthMinAge: $monthMinAge == "" ? null : $monthMinAge,
            yearMaxAge: $yearMaxAge == "" ? null : $yearMaxAge,
            monthMaxAge: $monthMaxAge == "" ? null : $monthMaxAge,
            authors: $authors == "" ? null : $authors,
            length: $length == "" ? null : $length,
            description: $description == "" ? null : $description,
            note: $note == "" ? null : $note,

            files: $files,
            links: $links,

            areas: $areas,
            tags: $tags,
            resourceTypes: $resourceTypes,
            formats: $formats,
            applicationModes: $applicationModes
        );

        Validator::validate($catalogueItem, Action::ADD);

        return $this->catalogueItemMapper->save($catalogueItem);
    }

    /**
     * @throws ReflectionException
     * @throws ValidationException
     * @throws NotFoundException
     * @throws FileException
     */
    public function edit(?string $id, ?string $acronym, ?string $name, ?File $image, ?string $yearMinAge,
                         ?string $monthMinAge, ?string $yearMaxAge, ?string $monthMaxAge, ?string $authors,
                         ?string $length, ?string $description, ?string $note, ?array $areas, ?array $tags,
                         ?array $resourceTypes, ?array $formats, ?array $applicationModes, ?array $files, ?array $links)
    {
        $catalogueItem = $this->get($id);

        if (!is_null($acronym)) $catalogueItem->setAcronym($acronym == "" ? null : $acronym);
        if (!is_null($name)) $catalogueItem->setName($name == "" ? null : $name);
        if (!is_null($image)) $catalogueItem->setNewImage($image);
        if (!is_null($yearMinAge)) $catalogueItem->setYearMinAge($yearMinAge == "" ? null : $yearMinAge);
        if (!is_null($monthMinAge)) $catalogueItem->setMonthMinAge($monthMinAge == "" ? null : $monthMinAge);
        if (!is_null($yearMaxAge)) $catalogueItem->setYearMaxAge($yearMaxAge == "" ? null : $yearMaxAge);
        if (!is_null($monthMaxAge)) $catalogueItem->setMonthMaxAge($monthMaxAge == "" ? null : $monthMaxAge);
        if (!is_null($authors)) $catalogueItem->setAuthors($authors == "" ? null : $authors);
        if (!is_null($length)) $catalogueItem->setLength($length == "" ? null : $length);
        if (!is_null($description)) $catalogueItem->setDescription($description == "" ? null : $description);
        if (!is_null($note)) $catalogueItem->setNote($note == "" ? null : $note);

        if (!is_null($files)) $catalogueItem->setFiles($files);
        if (!is_null($links)) $catalogueItem->setLinks($links);

        if (!is_null($areas)) $catalogueItem->setAreas($areas == [""] ? null : $areas);
        if (!is_null($tags)) $catalogueItem->setTags($tags == [""] ? null : $tags);
        if (!is_null($resourceTypes)) $catalogueItem->setResourceTypes($resourceTypes == [""] ? null : $resourceTypes);
        if (!is_null($formats)) $catalogueItem->setFormats($formats == [""] ? null : $formats);
        if (!is_null($applicationModes)) $catalogueItem->setApplicationModes($applicationModes == [""] ? null : $applicationModes);

        Validator::validate($catalogueItem, Action::EDIT);

        return $this->catalogueItemMapper->update($catalogueItem);
    }

    /**
     * @throws ReflectionException
     * @throws ValidationException
     * @throws NotFoundException
     * @throws FileException
     * @throws Exception
     */
    public function delete(?string $id): void
    {
        $catalogueItem = $this->get($id);

        Validator::validate($catalogueItem, Action::DELETE);

        $this->catalogueItemMapper->delete($catalogueItem);
    }

    /**
     * @throws ReflectionException
     * @throws NotFoundException
     * @throws ValidationException
     * @throws FileException
     */
    public function get(?string $id): CatalogueItem
    {
        Validator::validate(new CatalogueItem(id: $id), Action::GET);

        $catalogueItem = $this->catalogueItemMapper->findById($id);

        if ($catalogueItem != null) {
            return $catalogueItem;
        } else {
            throw new NotFoundException("Catalogue item not found");
        }
    }

    /**
     * @throws ReflectionException
     * @throws FileException
     * @throws ValidationException
     */
    public function list(): array
    {
        Validator::validate(new CatalogueItem(), Action::LIST);
        return $this->catalogueItemMapper->findAll();
    }

    /**
     * @throws ReflectionException
     * @throws ValidationException
     */
    public function listAvailableAreas(): array
    {
        Validator::validate(new CatalogueItem(), Action::LIST);
        return $this->catalogueItemMapper->listCurrentFilterValues(FilterType::Areas);
    }

    /**
     * @throws ReflectionException
     * @throws ValidationException
     */
    public function listAvailableTags(): array
    {
        Validator::validate(new CatalogueItem(), Action::LIST);
        return $this->catalogueItemMapper->listCurrentFilterValues(FilterType::Tags);
    }

    /**
     * @throws ReflectionException
     * @throws ValidationException
     */
    public function listAvailableResourceTypes(): array
    {
        Validator::validate(new CatalogueItem(), Action::LIST);
        return $this->catalogueItemMapper->listCurrentFilterValues(FilterType::ResourceTypes);
    }

    /**
     * @throws ReflectionException
     * @throws ValidationException
     */
    public function listAvailableFormats(): array
    {
        Validator::validate(new CatalogueItem(), Action::LIST);
        return $this->catalogueItemMapper->listCurrentFilterValues(FilterType::Formats);
    }

    /**
     * @throws ReflectionException
     * @throws ValidationException
     */
    public function listAvailableApplicationModes(): array
    {
        Validator::validate(new CatalogueItem(), Action::LIST);
        return $this->catalogueItemMapper->listCurrentFilterValues(FilterType::ApplicationModes);
    }

    /**
     * @throws NotFoundException
     * @throws ReflectionException
     * @throws ValidationException
     * @throws FileException
     */
    public function addFile(?string $catalogueItemId, ?File $file, ?string $name): CatalogueItem
    {
        $file->setDescription($name);

        $catalogueItem = $this->get($catalogueItemId);

        $catalogueItem->addNewFile($file);

        Validator::validate($catalogueItem, Action::EDIT);

        return $this->catalogueItemMapper->update($catalogueItem);
    }

    /**
     * @throws NotFoundException
     * @throws ReflectionException
     * @throws ValidationException
     * @throws FileException
     */
    public function addLink(?string $catalogueItemId, ?string $link, ?string $name)
    {
        $catalogueItem = $this->get($catalogueItemId);

        $catalogueItem->addNewLink(new Link(null, $name, $link));

        Validator::validate($catalogueItem, Action::EDIT);

        return $this->catalogueItemMapper->update($catalogueItem);
    }

    /**
     * @throws NotFoundException
     * @throws ReflectionException
     * @throws ValidationException
     * @throws FileException
     */
    public function deleteFile(?string $catalogueItemId, ?string $fileId)
    {
        $catalogueItem = $this->get($catalogueItemId);

        $catalogueItem->addFileToDelete(new File(id: $fileId));

        Validator::validate($catalogueItem, Action::EDIT);

        return $this->catalogueItemMapper->update($catalogueItem);
    }

    /**
     * @throws NotFoundException
     * @throws ReflectionException
     * @throws ValidationException
     * @throws FileException
     */
    public function deleteLink(?string $catalogueItemId, ?string $linkId)
    {
        $catalogueItem = $this->get($catalogueItemId);

        $catalogueItem->addLinkToDelete(new Link(id: $linkId));

        Validator::validate($catalogueItem, Action::EDIT);

        return $this->catalogueItemMapper->update($catalogueItem);
    }
}