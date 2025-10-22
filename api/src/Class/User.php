<?php


class User implements JsonSerializable {
    private int $id = 0;
    private string $name = '';
    private string $lastName = '';
    private string $email = '';
    private string $password = '';

    public function __construct(int $id = 0){
        $this->id = $id;
    }
    
    public function getId(): int
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

    public function jsonSerialize(): mixed {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "lastName" => $this->lastName,
            "email" => $this->email,
        ];
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function setPassword(string $password): self {
        $this->password = $password;
        return $this;
    }
}