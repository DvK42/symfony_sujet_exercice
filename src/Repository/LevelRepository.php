<?php

namespace App\Repository;

use App\Entity\Level;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Level>
 */
class LevelRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Level::class);
  }

  public function findOneBySlug(string $slug): ?Level
  {
    return $this->createQueryBuilder('s')
      ->andWhere('s.slug = :slug')
      ->setParameter('slug', $slug)
      ->getQuery()
      ->getOneOrNullResult();
  }

  public function findMenuLevels(): array
  {
    return $this->createQueryBuilder('s')
      ->orderBy('s.id', 'ASC')
      ->getQuery()
      ->getResult()
    ;
  }

  //    /**
//     * @return Level[] Returns an array of Level objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

  //    public function findOneBySomeField($value): ?Level
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
