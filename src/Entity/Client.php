<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping\Entity;

#[ApiResource]
#[Entity(repositoryClass: ClientRepository::class)]
final class Client extends User
{
}
