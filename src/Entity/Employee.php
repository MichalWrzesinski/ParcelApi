<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Enum\EmployeePositionEnum;
use App\Repository\EmployeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\{Column, Entity, ManyToMany, ManyToOne, JoinColumn};
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints\{Length, NotBlank};
use Symfony\Component\Security\Core\User\UserInterface;

#[ApiResource]
#[Entity(repositoryClass: EmployeeRepository::class)]
class Employee extends User implements UserInterface
{
    #[Column(type: Types::STRING, length: 50, enumType: EmployeePositionEnum::class)]
    #[NotBlank]
    #[Length(max: 50)]
    private EmployeePositionEnum $position;

    #[ManyToOne(targetEntity: Warehouse::class, inversedBy: 'employees')]
    #[JoinColumn(name: 'warehouse_id', referencedColumnName: 'id', nullable: false)]
    private Warehouse $warehouse;

    #[ManyToMany(targetEntity: DeliveryPoint::class, mappedBy: 'employees')]
    private Collection $deliveryPoints;

    public function __construct()
    {
        parent::__construct();

        $this->deliveryPoints = new ArrayCollection();
    }

    public function getRoles(): array
    {
        return ['ROLE_EMPLOYEE'];
    }

    public function getPosition(): EmployeePositionEnum
    {
        return $this->position;
    }

    public function setPosition(EmployeePositionEnum $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getWarehouse(): Warehouse
    {
        return $this->warehouse;
    }

    public function setWarehouse(Warehouse $warehouse): self
    {
        $this->warehouse = $warehouse;

        return $this;
    }

    public function getDeliveryPoints(): Collection
    {
        return $this->deliveryPoints;
    }

    public function addDeliveryPoint(DeliveryPoint $deliveryPoint): self
    {
        if (!$this->deliveryPoints->contains($deliveryPoint)) {
            $this->deliveryPoints[] = $deliveryPoint;
            $deliveryPoint->addEmployee($this);
        }

        return $this;
    }

    public function removeDeliveryPoint(DeliveryPoint $deliveryPoint): self
    {
        if ($this->deliveryPoints->removeElement($deliveryPoint)) {
            $deliveryPoint->removeEmployee($this);
        }

        return $this;
    }
}
