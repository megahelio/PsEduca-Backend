<?php

require_once __DIR__ . "/../exception/ValidationException.php";
require_once __DIR__ . "/../exception/FileException.php";
require_once __DIR__ . "/../model/Member.php";
require_once __DIR__ . "/../mapper/MemberMapper.php";

class MembersService
{

    private MemberMapper $memberMapper;

    public function __construct()
    {
        $this->memberMapper = new MemberMapper();
    }

    /**
     * @throws ReflectionException
     * @throws ValidationException
     * @throws FileException
     */
    public function add(?string  $name, ?string $email, ?string $description, ?string $referenceURL, ?File $file): Member
    {

        $member = new Member(null, $name, $email, $description, $referenceURL, $file);

        Validator::validate($member, Action::ADD);

        return $this->memberMapper->save($member);

    }

    /**
     * @throws ReflectionException
     * @throws ValidationException
     * @throws NotFoundException
     */
    public function get(?string $id): Member
    {

        Validator::validate(new Member($id), Action::GET);

        $member = $this->memberMapper->findById($id);

        if ($member != null) {
            return $member;
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
        Validator::validate(new Member(), Action::LIST);
        return $this->memberMapper->findAll();
    }

    /**
     * @throws ReflectionException
     * @throws FileException
     * @throws ValidationException
     * @throws NotFoundException
     */
    public function edit(?string $id, ?string $name, ?string $email, ?string $description, ?string $referenceURL, ?File $file): Member
    {

        $member = $this->get($id);

        if (!is_null($name)) $member->setName($name ?: null);
        if (!is_null($email)) $member->setEmail($email ?: null);
        if (!is_null($description)) $member->setDescription($description ?: null);
        if (!is_null($referenceURL)) $member->setReferenceURL($referenceURL ?: null);
        if (!is_null($file)) $member->setNewImage($file);

        Validator::validate($member, Action::EDIT);

        return $this->memberMapper->update($member);
    }

    /**
     * @throws NotFoundException
     * @throws ReflectionException
     * @throws ValidationException
     */
    public function delete(?string $id): void
    {
        $member = $this->get($id);
        Validator::validate($member, Action::DELETE);
        $this->memberMapper->delete($member);
    }


}