<?php

require_once __DIR__ . "/../exception/ValidationException.php";
require_once __DIR__ . "/../exception/FileException.php";
require_once __DIR__ . "/../model/OutreachItem.php";
require_once __DIR__ . "/../mapper/OutreachItemMapper.php";

class OutreachService
{
    private OutreachItemMapper $outreachItemMapper;

    public function __construct()
    {
        $this->outreachItemMapper = OutreachItemMapper::getInstance();
    }

    /**
     * @throws ReflectionException
     * @throws ValidationException
     * @throws FileException
     */
    public function add(?string $type, ?string $title, ?string $description, ?File $image, ?string $externalURL,
                        ?string $pageContent, ?File $file): OutreachItem
    {
        $outreachItem = new OutreachItem(
            id: null,
            type: $type == "" ? null : $type,
            title: $title == "" ? null : $title,
            description: $description == "" ? null : $description,
            image: $image,
            lastModified: date('Y-m-d H:i:s'),
            file: $file,
            externalURL: $externalURL == "" ? null : $externalURL,
            pageContent: $pageContent == "" ? null : $pageContent
        );

        Validator::validate($outreachItem, Action::ADD);

        return $this->outreachItemMapper->save($outreachItem);
    }

    /**
     * @throws ReflectionException
     * @throws ValidationException
     * @throws NotFoundException
     */
    public function edit(?string $id, ?string $type, ?string $title, ?string $description, ?File $image,
                         ?string $externalURL, ?string $pageContent, ?File $file)
    {
        $outreachItem = $this->get($id);

        if (!is_null($type)) $outreachItem->setType($type == "" ? null : $type);
        if (!is_null($title)) $outreachItem->setTitle($title == "" ? null : $title);
        if (!is_null($description)) $outreachItem->setDescription($description == "" ? null : $description);
        if (!is_null($image)) $outreachItem->setNewImage($image);
        if (!is_null($externalURL)) $outreachItem->setExternalURL($externalURL == "" ? null : $externalURL);
        if (!is_null($pageContent)) $outreachItem->setPageContent($pageContent == "" ? null : $pageContent);
        if (!is_null($file)) $outreachItem->setNewFile($file);

        Validator::validate($outreachItem, Action::EDIT);

        return $this->outreachItemMapper->update($outreachItem);
    }

    /**
     * @throws ReflectionException
     * @throws ValidationException
     * @throws NotFoundException
     */
    public function delete(?string $id): void
    {
        $outreachItem = $this->get($id);

        Validator::validate($outreachItem, Action::DELETE);

        $this->outreachItemMapper->delete($outreachItem);
    }

    /**
     * @throws ReflectionException
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function get(?string $id): OutreachItem
    {
        Validator::validate(new OutreachItem(id: $id), Action::GET);

        $outreachItem = $this->outreachItemMapper->findById($id);

        if ($outreachItem != null) {
            return $outreachItem;
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
        Validator::validate(new OutreachItem(), Action::LIST);
        return $this->outreachItemMapper->findAll();
    }
}