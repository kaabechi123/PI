<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface, \Serializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $role = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $token = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $otp = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function getOTP(): ?string
    {
        return $this->otp;
    }

    public function setOTP(?string $otp): static
    {
        $this->otp = $otp;

        return $this;
    }

    // Implement getUserIdentifier() from UserInterface
    public function getUserIdentifier(): string
    {
        return $this->email;  // Using email as the user identifier
    }

    // Implement eraseCredentials() from UserInterface
    public function eraseCredentials(): void
    {
        // Erase sensitive data (like plain-text passwords)
        // If you store plain-text passwords temporarily, clear them here
    }

    // Implement getRoles() from UserInterface
    public function getRoles(): array
    {
        // Return roles as an array; if role is stored as a string, return it as an array
        return [$this->role];  // Assuming 'role' is a string, like 'ROLE_USER' or 'ROLE_ADMIN'
    }

    // Implement \Serializable interface
    public function serialize(): string
    {
        return serialize([
            $this->id,
            $this->username,
            $this->email,
            $this->password,
            $this->role,
            $this->token,
            $this->otp,
        ]);
    }

    public function unserialize($serialized): void
    {
        list(
            $this->id,
            $this->username,
            $this->email,
            $this->password,
            $this->role,
            $this->token,
            $this->otp,
        ) = unserialize($serialized);
    }
}