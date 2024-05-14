<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ParcelTrace;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ParcelTrace|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParcelTrace|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParcelTrace[] findAll()
 * @method ParcelTrace[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class ParcelTraceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParcelTrace::class);
    }
}
