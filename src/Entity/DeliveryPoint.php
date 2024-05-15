<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Trait\SoftDeletableTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Repository\DeliveryPointRepository;
use App\Enum\DeliveryPointTypeEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    ManyToMany,
    JoinTable,
};
use Symfony\Component\Validator\Constraints\{
    NotBlank,
    Length,
};

#[ApiResource]
#[Entity(repositoryClass: DeliveryPointRepository::class)]
#[Table(name: 'delivery_points')]
#[HasLifecycleCallbacks]
class DeliveryPoint
{
    use TimestampableTrait;
    use SoftDeletableTrait;

    #[Id]
    #[Column(type: 'uuid', unique: true)]
    #[GeneratedValue(strategy: 'CUSTOM')]
    #[CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private Uuid $id;

    #[Column(type: Types::STRING, length: 50, enumType: DeliveryPointTypeEnum::class)]
    #[NotBlank]
    #[Length(max: 50)]
    private DeliveryPointTypeEnum $type;

    #[Column(type: Types::STRING, length: 30)]
    #[NotBlank]
    #[Length(max: 30)]
    private string $name;

    #[Column(type: Types::STRING, length: 6)]
    #[NotBlank]
    #[Length(min: 6, max: 6)]
    private string $postCode;

    #[Column(type: Types::STRING, length: 30)]
    #[NotBlank]
    #[Length(min: 2, max: 30)]
    private string $city;

    #[Column(type: Types::STRING, length: 50)]
    #[NotBlank]
    #[Length(min: 10, max: 50)]
    private string $address;

    #[ManyToMany(targetEntity: Employee::class, inversedBy: 'deliveryPoints')]
    #[JoinTable(name: 'employees_delivery_points')]
    private Collection $employees;

    public function __construct()
    {
        $this->employees = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getType(): DeliveryPointTypeEnum
    {
        return $this->type;
    }

    public function setType(DeliveryPointTypeEnum $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPostCode(): string
    {
        return $this->postCode;
    }

    public function setPostCode(string $postCode): self
    {
        $this->postCode = $postCode;

        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getEmployees(): Collection
    {
        return $this->employees;
    }

    public function addEmployee(Employee $employee): self
    {
        if (!$this->employees->contains($employee)) {
            $this->employees[] = $employee;
            $employee->addDeliveryPoint($this);
        }

        return $this;
    }

    public function removeEmployee(Employee $employee): self
    {
        if ($this->employees->removeElement($employee)) {
            $employee->removeDeliveryPoint($this);
        }

        return $this;
    }
}
