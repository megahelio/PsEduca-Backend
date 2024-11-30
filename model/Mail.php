<?php
// file: model/Mail.php

/**
 * Class Mail
 *
 * Represents an Email
 */
class Mail
{
    /**
     * The name of the sender
     * @var string|null
     */
    private ?string $senderName;

    /**
     * The email address of the sender
     * @var string|null
     */
    private ?string $senderEmail;

    /**
     * The subject of the email
     * @var string|null
     */
    private ?string $subject;

    /**
     * The message content of the email
     * @var string|null
     */
    private ?string $message;

    /**
     * The constructor
     *
     * @param string|null $senderName The name of the sender
     * @param string|null $senderEmail The email address of the sender
     * @param string|null $subject The subject of the email
     * @param string|null $message The message content of the email
     */
    public function __construct(
        ?string $senderName = null,
        ?string $senderEmail = null,
        ?string $subject = null,
        ?string $message = null
    ) {
        $this->senderName = $senderName;
        $this->senderEmail = $senderEmail;
        $this->subject = $subject;
        $this->message = $message;
    }

    /**
     * Gets the name of the sender
     *
     * @return string|null The name of the sender
     */
    public function getSenderName(): ?string
    {
        return $this->senderName;
    }

    /**
     * Sets the name of the sender
     *
     * @param string $senderName The name of the sender
     * @return void
     */
    public function setSenderName(string $senderName): void
    {
        $this->senderName = $senderName;
    }

    /**
     * Gets the email address of the sender
     *
     * @return string|null The email address of the sender
     */
    public function getSenderEmail(): ?string
    {
        return $this->senderEmail;
    }

    /**
     * Sets the email address of the sender
     *
     * @param string $senderEmail The email address of the sender
     * @return void
     */
    public function setSenderEmail(string $senderEmail): void
    {
        $this->senderEmail = $senderEmail;
    }

    /**
     * Gets the subject of the email
     *
     * @return string|null The subject of the email
     */
    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * Sets the subject of the email
     *
     * @param string $subject The subject of the email
     * @return void
     */
    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * Gets the message content of the email
     *
     * @return string|null The message content of the email
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * Sets the message content of the email
     *
     * @param string $message The message content of the email
     * @return void
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
}
