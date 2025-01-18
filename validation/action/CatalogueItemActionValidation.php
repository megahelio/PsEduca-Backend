<?php

class CatalogueItemActionValidation {

    private array $errors;
    private CatalogueItemMapper $catalogueItemMapper;

    public function __construct() {
        $this->errors = [];
        $this->catalogueItemMapper = CatalogueItemMapper::getInstance();
    }

    public function addError(string $error): void {
        $this->errors[] = $error;
    }

    // Obtener errores
    public function getErrors(): array {
        return array_unique($this->errors);
    }


    /**
     * Valida las reglas específicas según la acción proporcionada.
     *
     * @param Action $action La acción que se va a realizar posteriormente si las comprobaciones son correctas.
     * @param CatalogueItem $catalogueItem El objeto de la sección catálogo.
     * @return array Lista de errores encontrados.
     */
    public function validate(Action $action, CatalogueItem $catalogueItem): array {

        switch ($action) {
            case Action::EDIT:
                $this->validateCatalogueItemExists($catalogueItem->getId());
                $this->validateCatalogueItemFilesExists($catalogueItem->getId(), $catalogueItem->getFilesToDelete());
                $this->validateCatalogueItemLinksExists($catalogueItem->getId(), $catalogueItem->getLinksToDelete());
                break;

            case Action::DELETE:
            case Action::GET:
                $this->validateCatalogueItemExists($catalogueItem->getId());
                break;

            default:
                break;
       }

        return $this->getErrors();
    }

    private function validateCatalogueItemFilesExists(?int $catalogueItemId, ?array $getFiles): void
    {
        if ($getFiles !== null) {
            foreach ($getFiles as $file) {
                 $this->validateCatalogueItemFileExists($catalogueItemId, $file->getId());
            }
        }
    }

    private function validateCatalogueItemLinksExists(?int $catalogueItemId, ?array $getLinks): void
    {
        if ($getLinks !== null) {
            foreach ($getLinks as $link) {
                $this->validateCatalogueItemLinkExists($catalogueItemId, $link->getId());
            }
        }
    }

    /**
     * Comprueba si un recurso de catálogo existe por su ID.
     *
     * @param int $catalogueItemId El ID del recurso de catálogo a verificar.
     */
    private function validateCatalogueItemExists(int $catalogueItemId): void {
        if (!$this->catalogueItemMapper->catalogueItemExists($catalogueItemId)) {
            $this->addError('ITEM_CATALOGO_NO_ENCONTRADO_A_KO');
        }
    }

    /**
     * Comprueba si un fichero asociado a un recurso de catálogo existe por su ID.
     *
     * @param int $catalogueItemId El ID del recurso de catálogo a verificar.
     * @param int $fileId El ID del fichero a verificar.
     */
    private function validateCatalogueItemFileExists(int $catalogueItemId, int $fileId): void
    {

        if (!$this->catalogueItemMapper->fileExists($fileId, $catalogueItemId)) {
            $this->addError('FICHERO_NO_ENCONTRADO_A_KO');
        }

    }

    /**
     * Comprueba si un enlace asociado a un recurso de catálogo existe por su ID.
     *
     * @param int $catalogueItemId El ID del recurso de catálogo a verificar.
     * @param int $linkId El ID del enlace a verificar.
     */
    private function validateCatalogueItemLinkExists(int $catalogueItemId, int $linkId): void
    {
        if (!$this->catalogueItemMapper->linkExists($linkId, $catalogueItemId)) {
            $this->addError('ENLACE_NO_ENCONTRADO_A_KO');
        }
    }
}
