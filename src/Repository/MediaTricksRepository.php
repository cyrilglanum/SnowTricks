<?php

namespace App\Repository;

use App\Entity\MediaTricks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MediaTricks|null find($id, $lockMode = null, $lockVersion = null)
 * @method MediaTricks|null findOneBy(array $criteria, array $orderBy = null)
 * @method MediaTricks[]    findAll()
 * @method MediaTricks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaTricksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MediaTricks::class);
    }

    // /**
    //  * @return MediaTricks[] Returns an array of MediaTricks objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MediaTricks
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
