<?php

class TestUser
{
    private string $id;
    private string $role;
    private string $jwtToken;

    public function __construct(string $id, string $role, string $jwtToken)
    {
        $this->id = $id;
        $this->role = $role;
        $this->jwtToken = $jwtToken;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getJwtToken(): string
    {
        return $this->jwtToken;
    }
}