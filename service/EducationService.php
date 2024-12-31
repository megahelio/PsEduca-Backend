<?php

require_once __DIR__ . "/../exception/ValidationException.php";
require_once __DIR__ . "/../exception/FileException.php";
require_once __DIR__ . "/../model/EducationItem.php";
require_once __DIR__ . "/../mapper/EducationItemMapper.php";

class EducationService
{

    private EducationItemMapper $educationMapper;

    public function __construct()
    {
        $this->educationMapper = EducationItemMapper::getInstance();
    }

    /**
     * @throws ReflectionException
     * @throws FileException
     * @throws ValidationException
     */
    public function add(?string $type, ?string $title, ?string $description, ?string $referenceURL, ?string $initYear,
                        ?string $endYear, ?File $file): EducationItem
    {
        $educationItem = new EducationItem(
            id: null,
            type: $type == "" ? null : $type,
            title: $title == "" ? null : $title,
            description: $description == "" ? null : $description,
            image: $file,
            referenceURL: $referenceURL == "" ? null : $referenceURL,
            initYear: $initYear == "" ? null : $initYear,
            endYear: $endYear == "" ? null : $endYear
        );

        Validator::validate($educationItem, Action::ADD);

        return $this->educationMapper->save($educationItem);
    }

    /**
     * @throws ReflectionException
     * @throws NotFoundException
     * @throws FileException
     * @throws ValidationException
     */
    public function edit(?string $id, ?string $type, ?string $title, ?string $description, ?string $referenceURL, ?string $initYear,
                         ?string $endYear, ?File $file): EducationItem
    {
        $educationItem = $this->get($id);

        if (!is_null($type)) $educationItem->setType($type == "" ? null : $type);
        if (!is_null($title)) $educationItem->setTitle($title == "" ? null : $title);
        if (!is_null($description)) $educationItem->setDescription($description == "" ? null : $description);
        if (!is_null($referenceURL)) $educationItem->setReferenceURL($referenceURL == "" ? null : $referenceURL);
        if (!is_null($initYear)) $educationItem->setInitYear($initYear == "" ? null : $initYear);
        if (!is_null($endYear)) $educationItem->setEndYear($endYear == "" ? null : $endYear);
        if (!is_null($file)) $educationItem->setNewImage($file);

        Validator::validate($educationItem, Action::EDIT);

        return $this->educationMapper->update($educationItem);
    }

    /**
     * @throws NotFoundException
     * @throws ReflectionException
     * @throws ValidationException
     */
    public function delete(?string $id): void
    {
        $educationItem = $this->get($id);

        Validator::validate($educationItem, Action::DELETE);

        $this->educationMapper->delete($educationItem);
    }

    /**
     * @throws ReflectionException
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function get(?string $id): EducationItem
    {
        Validator::validate(new EducationItem(id: $id), Action::GET);

        $educationItem = $this->educationMapper->findById($id);

        if ($educationItem != null) {
            return $educationItem;
        } else {
            throw new NotFoundException();
        }
    }

    /**
     * @throws ReflectionException
     * @throws ValidationException
     */
    public function list(): array
    {
        Validator::validate(new EducationItem(), Action::LIST);
        return $this->educationMapper->findAll();
    }
}