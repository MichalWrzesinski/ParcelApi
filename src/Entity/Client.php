<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Security\Core\User\UserInterface;

#[ApiResource]
#[Entity(repositoryClass: ClientRepository::class)]
class Client extends User implements UserInterface
{
    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }
}
