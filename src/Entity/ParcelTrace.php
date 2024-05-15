<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Trait\SoftDeletableTrait;
use App\Entity\Trait\TimestampableTrait;
use ApiPlatform\Metadata\ApiResource;
use App\Enum\ParcelStatusEnum;
use App\Repository\ParcelTraceRepository;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping\{
    Entity,
    HasLifecycleCallbacks,
    Table,
    Column,
    Id,
    CustomIdGenerator,
    GeneratedValue,
};
use Symfony\Component\Validator\Constraints\{
    NotBlank,
    Length,
};

#[ApiResource]
#[Entity(repositoryClass: ParcelTraceRepository::class)]
#[Table(name: 'parcel_traces')]
#[HasLifecycleCallbacks]
final class ParcelTrace
{
    use TimestampableTrait;
    use SoftDeletableTrait;

    #[Id]
    #[Column(type: 'uuid', unique: true)]
    #[GeneratedValue(strategy: 'CUSTOM')]
    #[CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private Uuid $id;

    #[Column(type: Types::STRING, length: 50, enumType: ParcelStatusEnum::class)]
    #[NotBlank]
    #[Length(max: 50)]
    private ?ParcelStatusEnum $type = null;

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getType(): ?ParcelStatusEnum
    {
        return $this->type;
    }

    public function setType(?ParcelStatusEnum $type): self
    {
        $this->type = $type;

        return $this;
    }
}
