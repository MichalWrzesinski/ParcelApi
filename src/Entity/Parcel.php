<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Trait\SoftDeletableTrait;
use App\Entity\Trait\TimestampableTrait;
use ApiPlatform\Metadata\ApiResource;
use App\Enum\ParcelStatusEnum;
use App\Repository\ParcelRepository;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints\{
    Length,
    NotBlank,
};
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

#[ApiResource]
#[Entity(repositoryClass: ParcelRepository::class)]
#[Table(name: 'parcels')]
#[HasLifecycleCallbacks]
class Parcel
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
    private ParcelStatusEnum $status;

    #[ManyToOne(targetEntity: Client::class)]
    #[JoinColumn(name: 'sender_id', referencedColumnName: 'id')]
    private Client $sender;

    #[ManyToOne(targetEntity: Client::class)]
    #[JoinColumn(name: 'receiver_id', referencedColumnName: 'id')]
    private Client $receiver;

    #[ManyToOne(targetEntity: DeliveryPoint::class)]
    #[JoinColumn(name: 'sending_point_id', referencedColumnName: 'id')]
    private DeliveryPoint $sendingPoint;

    #[ManyToOne(targetEntity: DeliveryPoint::class)]
    #[JoinColumn(name: 'receiving_point_id', referencedColumnName: 'id')]
    private DeliveryPoint $receivingPoint;

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getStatus(): ParcelStatusEnum
    {
        return $this->status;
    }

    public function setStatus(ParcelStatusEnum $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getSender(): Client
    {
        return $this->sender;
    }

    public function setSender(Client $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getReceiver(): Client
    {
        return $this->receiver;
    }

    public function setReceiver(Client $receiver): self
    {
        $this->receiver = $receiver;

        return $this;
    }

    public function getSendingPoint(): DeliveryPoint
    {
        return $this->sendingPoint;
    }

    public function setSendingPoint(DeliveryPoint $sendingPoint): self
    {
        $this->sendingPoint = $sendingPoint;

        return $this;
    }

    public function getReceivingPoint(): DeliveryPoint
    {
        return $this->receivingPoint;
    }

    public function setReceivingPoint(DeliveryPoint $receivingPoint): self
    {
        $this->receivingPoint = $receivingPoint;

        return $this;
    }
}
