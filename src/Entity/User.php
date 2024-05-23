<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Trait\SoftDeletableTrait;
use App\Entity\Trait\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\{
    DiscriminatorColumn,
    DiscriminatorMap,
    Entity,
    HasLifecycleCallbacks,
    InheritanceType,
    Table,
    Column,
    Id,
    CustomIdGenerator,
    GeneratedValue,
    OneToMany,
};
use Symfony\Component\Validator\Constraints\{
    NotBlank,
    Length,
    Email,
};
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\{
    UserInterface,
    PasswordAuthenticatedUserInterface,
};

#[Entity]
#[Table(name: 'users')]
#[InheritanceType(value: 'SINGLE_TABLE')]
#[DiscriminatorColumn(name: 'type', type: Types::STRING)]
#[DiscriminatorMap(value: ['CLIENT' => Client::class, 'EMPLOYEE' => Employee::class])]
#[HasLifecycleCallbacks]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableTrait;
    use SoftDeletableTrait;

    #[Id]
    #[Column(type: 'uuid', unique: true)]
    #[GeneratedValue(strategy: 'CUSTOM')]
    #[CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private Uuid $id;

    #[Column(type: Types::STRING, length: 12, unique: true)]
    #[NotBlank]
    #[Length(min: 6, max: 12)]
    private string $phone;

    #[Column(type: Types::STRING, length: 100, unique: true)]
    #[NotBlank]
    #[Length(min: 6, max: 100)]
    #[Email]
    private ?string $email = null;

    #[Column(type: Types::STRING, length: 255)]
    #[NotBlank]
    #[Length(min: 8, max: 255)]
    private ?string $password = null;

    #[Column(type: Types::STRING, length: 50)]
    #[Length(min: 3, max: 50)]
    private ?string $firstName = null;

    #[Column(type: Types::STRING, length: 50)]
    #[Length(min: 3, max: 50)]
    private ?string $lastName = null;

    #[OneToMany(targetEntity: Notification::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    private Collection $notifications;

    public function __construct()
    {
        $this->notifications = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getRoles(): array
    {
        return [];
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->getEmail();
    }

    public function getUserIdentifier(): string
    {
        return $this->getEmail();
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setUser($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        $this->notifications->removeElement($notification);

        return $this;
    }

    public function eraseCredentials(): void
    {
    }
}
