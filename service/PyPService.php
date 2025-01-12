<?php

require_once __DIR__ . "/../exception/ValidationException.php";
require_once __DIR__ . "/../exception/FileException.php";
require_once __DIR__ . "/../model/PyPItem.php";
require_once __DIR__ . "/../mapper/PyPItemMapper.php";

class PyPService
{
    private PyPItemMapper $pypItemMapper;

    public function __construct()
    {
        $this->pypItemMapper = PyPItemMapper::getInstance();
    }

    /**
     * @throws ReflectionException
     * @throws ValidationException
     */
    public function add(?string $title, ?string $description, ?File $image, ?string $externalURL): PyPItem
    {
        $pypItem = new PyPItem(
            id: null,
            title: $title,
            description: $description,
            image: $image,
            externalURL: $externalURL
        );

        Validator::validate($pypItem, Action::ADD);

        return $this->pypItemMapper->save($pypItem);
    }

    /**
     * @throws ReflectionException
     * @throws ValidationException
     * @throws NotFoundException
     */
    public function edit(?string $id, ?string $title, ?string $description, ?File $image, ?string $externalURL): PyPItem
    {
        $pypItem = $this->get($id);

        if (!is_null($title)) $pypItem->setTitle($title);
        if (!is_null($description)) $pypItem->setDescription($description);
        if (!is_null($image)) $pypItem->setNewImage($image);
        if (!is_null($externalURL)) $pypItem->setExternalURL($externalURL);

        Validator::validate($pypItem, Action::EDIT);

        return $this->pypItemMapper->update($pypItem);
    }

    /**
     * @throws NotFoundException
     * @throws ReflectionException
     * @throws ValidationException
     */
    public function delete(?string $id): void
    {
        $pypItem = $this->get($id);

        Validator::validate($pypItem, Action::DELETE);

        $this->pypItemMapper->delete($pypItem);
    }

    /**
     * @throws ReflectionException
     * @throws ValidationException
     * @throws NotFoundException
     */
    public function get(?string $id): PyPItem
    {
        Validator::validate(new PyPItem(id: $id), Action::GET);

        $pypItem = $this->pypItemMapper->findById($id);

        if ($pypItem != null) {
            return $pypItem;
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
        Validator::validate(new PyPItem(), Action::LIST);
        return $this->pypItemMapper->findAll();
    }


}