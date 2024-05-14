<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Trait\SoftDeletableTrait;
use App\Entity\Trait\TimestampableTrait;
use ApiPlatform\Metadata\ApiResource;
use App\Enum\ParcelStatusEnum;
use App\Repository\NotificationRepository;
use Symfony\Component\Uid\Uuid;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\{
    Entity,
    HasLifecycleCallbacks,
    Table,
    Column,
    Id,
    CustomIdGenerator,
    GeneratedValue,
    ManyToOne,
    JoinColumn,
};
use Symfony\Component\Validator\Constraints\{
    NotBlank,
    Length,
};

#[ApiResource]
#[Entity(repositoryClass: NotificationRepository::class)]
#[Table(name: 'notifications')]
#[HasLifecycleCallbacks]
final class Notification
{
    use TimestampableTrait;
    use SoftDeletableTrait;

    #[Id]
    #[Column(type: 'uuid', unique: true)]
    #[GeneratedValue(strategy: 'CUSTOM')]
    #[CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private Uuid $id;

    #[ManyToOne(targetEntity: User::class, inversedBy: 'notifications')]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    private User $user;

    #[Column(type: Types::TEXT)]
    #[Length(min: 10)]
    private ?string $description = null;

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
