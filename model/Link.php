<?php

/**
 * Class Link
 *
 * Represents a link. Only is used in catalogue items.
 */
class Link
{

    /**
     * The id of the link
     * @var string|null
     */
    private ?string $id;

    /**
     * A brief description of the link
     * @var string|null
     */
    private ?string $name;

    /**
     * The URL of the link
     * @var string|null
     */
    private ?string $url;

    /**
     * The constructor
     *
     * @param string|null $id The id of the link
     * @param string|null $name The name of the link
     * @param string|null $url The URL of the link
     */
    public function __construct(
        ?string $id = null,
        ?string $name = null,
        ?string $url = null,
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->url = $url;
    }

    /**
     * Gets the id of the link
     *
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Gets the name of the link
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Gets the URL of the link
     *
     * @return string|null
     */
    public function getURL(): ?string
    {
        return $this->url;
    }

    /**
     * Sets the id of the link
     *
     * @param string|null $id The id of the link
     * @return void
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    /**
     * Sets the name of the link
     *
     * @param string|null $name The name of the link
     * @return void
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * Sets the URL of the link
     *
     * @param string|null $url The URL of the link
     * @return void
     */
    public function setURL(?string $url): void
    {
        $this->url = $url;
    }
}