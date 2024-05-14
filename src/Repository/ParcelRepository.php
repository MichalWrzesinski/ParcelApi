<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Parcel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Parcel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Parcel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Parcel[] findAll()
 * @method Parcel[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class ParcelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Parcel::class);
    }
}
