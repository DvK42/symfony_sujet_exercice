<?php

namespace App\Repository;

use App\Entity\Chapiter;
use App\Entity\Exercise;
use App\Entity\Level;
use App\Entity\Subject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Exercise>
 */
class ExerciseRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Exercise::class);
  }

  public function findOneBySlug(string $slug): ?Exercise
  {
    return $this->createQueryBuilder('s')
      ->andWhere('s.slug = :slug')
      ->setParameter('slug', $slug)
      ->getQuery()
      ->getOneOrNullResult();
  }

  public function findByChapiterSubjectAndLevel(Subject $subject, Level $level, Chapiter $chapiter): array
  {
    return $this->createQueryBuilder('e')
      ->where('e.subject = :subject')
      ->andWhere('e.level = :level')
      ->andWhere('e.chapiter = :chapiter')
      ->setParameter('subject', $subject)
      ->setParameter('level', $level)
      ->setParameter('chapiter', $chapiter)
      ->getQuery()
      ->getResult();
  }

  public function findByParamsSlug(Subject $subject, Level $level, Chapiter $chapiter, Exercise $exercise): ?Exercise
  {
    return $this->createQueryBuilder('e')
      ->where('e.subject = :subject')
      ->andWhere('e.level = :level')
      ->andWhere('e.chapiter = :chapiter')
      ->andWhere('e = :exercise')
      ->setParameter('subject', $subject)
      ->setParameter('level', $level)
      ->setParameter('chapiter', $chapiter)
      ->setParameter('exercise', $exercise)
      ->getQuery()
      ->getOneOrNullResult();
  }
  //    /**
//     * @return Exercise[] Returns an array of Exercise objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

  //    public function findOneBySomeField($value): ?Exercise
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
