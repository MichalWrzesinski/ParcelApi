<?php

namespace App\Security;

use App\Entity\{Client, Employee};
use App\Repository\{ClientRepository, EmployeeRepository};
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\{UnsupportedUserException, UserNotFoundException};
use Symfony\Component\Security\Core\User\{UserInterface, UserProviderInterface};

final readonly class UserProvider implements UserProviderInterface
{
    private const HEADER_USER_TYPE = 'X-User-Type';

    public function __construct(
        private EmployeeRepository $employeeRepository,
        private ClientRepository $clientRepository,
        private RequestStack $requestStack,
    ) {
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $request = $this->requestStack->getCurrentRequest();
        $userType = $request->headers->get(self::HEADER_USER_TYPE);

        $user = match ($userType) {
            'client' => $this->clientRepository->find($identifier),
            'employee' => $this->employeeRepository->find($identifier),
            default => throw new UnsupportedUserException(sprintf('User type %s is not supported or not provided.', $userType))
        };

        if (!$user) {
            throw new UserNotFoundException(sprintf('User with identifier %s not found.', $identifier));
        }

        return $user;
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof Client && !$user instanceof Employee) {
            throw new UnsupportedUserException(sprintf('Invalid user class: %s.', get_class($user)));
        }

        $repository = $user instanceof Client ? $this->clientRepository : $this->employeeRepository;
        $freshUser = $repository->find($user->getId());

        if (!$freshUser) {
            throw new UserNotFoundException(sprintf('User not found: %s.', $user->getUserIdentifier()));
        }

        return $freshUser;
    }

    public function supportsClass(string $class): bool
    {
        return Client::class === $class || Employee::class === $class;
    }
}
