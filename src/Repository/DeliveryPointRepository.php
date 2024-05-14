<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\DeliveryPoint;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DeliveryPoint|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeliveryPoint|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeliveryPoint[] findAll()
 * @method DeliveryPoint[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class DeliveryPointRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeliveryPoint::class);
    }
}
