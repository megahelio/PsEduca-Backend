<?php

require_once __DIR__ . "/../exception/ValidationException.php";
require_once __DIR__ . "/../exception/FileException.php";
require_once __DIR__ . "/../model/PyPItem.php";
require_once __DIR__ . "/../model/PyPAuthorization.php";
require_once __DIR__ . "/../mapper/PyPItemMapper.php";
require_once __DIR__ . "/../mapper/PyPAuthorizationMapper.php";

class PyPService
{
    private PyPItemMapper $pypItemMapper;
    private PyPAuthorizationMapper $pypAuthorizationMapper;
    private UserMapper $userMapper;

    public function __construct()
    {
        $this->pypItemMapper = PyPItemMapper::getInstance();
        $this->pypAuthorizationMapper = PyPAuthorizationMapper::getInstance();
        $this->userMapper = UserMapper::getInstance();
    }

    /**
     * @throws ReflectionException
     * @throws ValidationException
     * @throws FileException
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
     * @throws FileException
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
     * @throws Exception
     */
    public function list(): array
    {
        $user = Validator::validate(new PyPItem(), Action::LIST);

        if ($user == null) throw new Exception("User not found");

        return $this->pypItemMapper->findAll(
            returnOnlyAuthorized: $user->getRole() != UserRole::ADMIN_GLOBAL,
            userId: $user->getId()
        );
    }

    /**
     * @throws ReflectionException
     * @throws ValidationException
     */
    public function addAuthorization(?string $idPyPItem, ?string $idUser): void
    {
        $pypAuthorization = new PyPAuthorization(
            idPyPItem: $idPyPItem,
            idUser: $idUser
        );

        Validator::validate($pypAuthorization, Action::ADD);

        $this->pypAuthorizationMapper->save($pypAuthorization);
    }

    /**
     * @throws ReflectionException
     * @throws ValidationException
     * @throws NotFoundException
     */
    public function removeAuthorization(?string $idPyPItem, ?string $idUser): void
    {
        $pypAuthorization = new PyPAuthorization(
            idPyPItem: $idPyPItem,
            idUser: $idUser
        );

        Validator::validate($pypAuthorization, Action::DELETE);

        $this->pypAuthorizationMapper->delete($pypAuthorization);
    }

    /**
     * @throws ReflectionException
     * @throws ValidationException
     */
    public function listCurrentAuthorizations(): array
    {
        Validator::validate(new PyPAuthorization(), Action::LIST);

        $pypAuths = $this->pypAuthorizationMapper->findAll();
        $pypItems = $this->pypItemMapper->findAll(false);
        $users = $this->userMapper->findAll();

        $pypItemIds = array_map(fn($pypAuth) => $pypAuth->getIdPyPItem(), $pypAuths);
        $userIds = array_map(fn($pypAuth) => $pypAuth->getIdUser(), $pypAuths);

        $pypItems = array_filter($pypItems, fn($pypItem) => !in_array($pypItem->getId(), $pypItemIds));
        $users = array_filter($users, fn($user) => !in_array($user->getId(), $userIds));

        return array(
            "currentPyPAuthorizations" => $pypAuths,
            "otherPyPItems" => $pypItems,
            "otherUsers" => $users
        );
    }
}