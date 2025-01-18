<?php
// file: model/PyPAuthorization.php

class PyPAuthorization
{
    private ?string $idPyPItem;
    private ?string $titlePyPItem;

    private ?string $idUser;
    private ?string $nameUser;

    /**
     * @param string|null $idPyPItem
     * @param string|null $titlePyPItem
     * @param string|null $idUser
     * @param string|null $nameUser
     */
    public function __construct(
        ?string $idPyPItem = null,
        ?string $titlePyPItem = null,
        ?string $idUser = null,
        ?string $nameUser = null
    ) {
        $this->idPyPItem = $idPyPItem;
        $this->titlePyPItem = $titlePyPItem;
        $this->idUser = $idUser;
        $this->nameUser = $nameUser;
    }


    /**
     * @return string|null
     */
    public function getIdPyPItem(): ?string
    {
        return $this->idPyPItem;
    }

    /**
     * @param string|null $idPyPItem
     */
    public function setIdPyPItem(?string $idPyPItem): void
    {
        $this->idPyPItem = $idPyPItem;
    }

    /**
     * @return string|null
     */
    public function getIdUser(): ?string
    {
        return $this->idUser;
    }

    /**
     * @param string|null $idUser
     */
    public function setIdUser(?string $idUser): void
    {
        $this->idUser = $idUser;
    }

    /**
     * @return string|null
     */
    public function getTitlePyPItem(): ?string
    {
        return $this->titlePyPItem;
    }

    /**
     * @param string|null $titlePyPItem
     */
    public function setTitlePyPItem(?string $titlePyPItem): void
    {
        $this->titlePyPItem = $titlePyPItem;
    }

    /**
     * @return string|null
     */
    public function getNameUser(): ?string
    {
        return $this->nameUser;
    }

    /**
     * @param string|null $nameUser
     */
    public function setNameUser(?string $nameUser): void
    {
        $this->nameUser = $nameUser;
    }
}