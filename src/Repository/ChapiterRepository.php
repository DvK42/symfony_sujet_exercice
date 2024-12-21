<?php

namespace App\Repository;

use App\Entity\Chapiter;
use App\Entity\Level;
use App\Entity\Subject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Chapiter>
 */
class ChapiterRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Chapiter::class);
  }

  public function findOneBySlug(string $slug): ?Chapiter
  {
    return $this->createQueryBuilder('s')
      ->andWhere('s.slug = :slug')
      ->setParameter('slug', $slug)
      ->getQuery()
      ->getOneOrNullResult();
  }

  public function findLevelsBySubject(Subject $subject): array
  {
    return $this->createQueryBuilder('c')
      ->select('DISTINCT l.id, l.name, l.slug')
      ->join('c.level', 'l')
      ->where('c.subject = :subject')
      ->setParameter('subject', $subject)
      ->getQuery()
      ->getResult();
  }

  public function findSubjectsByLevel(Level $level): array
  {
    return $this->createQueryBuilder('c')
      ->select('DISTINCT s.id, s.name, s.slug')
      ->join('c.subject', 's')
      ->where('c.level = :level')
      ->setParameter('level', $level)
      ->getQuery()
      ->getResult();
  }

  public function findBySubjectAndLevel(Subject $subject, Level $level): array
  {
    return $this->createQueryBuilder('e')
      ->where('e.subject = :subject')
      ->andWhere('e.level = :level')
      ->setParameter('subject', $subject)
      ->setParameter('level', $level)
      ->getQuery()
      ->getResult();
  }

  //    /**
//     * @return Chapiter[] Returns an array of Chapiter objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

  //    public function findOneBySomeField($value): ?Chapiter
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
