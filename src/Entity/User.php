<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Controller\GetUserController;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\CreateUserController;
use App\Controller\DeleteUserController;
use App\Controller\GetAllUserController;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource]
#[GetCollection(
    normalizationContext: ['groups' => ['user:readAll']],
    controller:GetAllUserController::class)]
#[Get(
    normalizationContext: ['groups' => ['user:readOne','user:readAll']],
    controller:GetUserController::class,
    )]
#[Post(
    controller: CreateUserController::class,
    normalizationContext:['groups' => ['user:readAll']],
     )]
#[Delete(
    controller:DeleteUserController::class,
)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('user:readAll')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[NotBlank()]
    #[Groups('user:readAll')]
    private ?string $first_name = null;

    #[ORM\Column(length: 255)]
    #[NotBlank()]
    #[Groups('user:readAll')]
    private ?string $last_name = null;

    #[ORM\Column(length: 255)]
    #[NotBlank()]
    #[Groups('user:readAll')]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[NotBlank()]
    private ?string $password = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('user:readAll')]
    #[ApiProperty(genId:false)]
    private ?Company $company = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): static
    {
        $this->last_name = $last_name;

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

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): static
    {
        $this->company = $company;

        return $this;
    }
}
