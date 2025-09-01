<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    //    /**
    //     * @return Task[] Returns an array of Task objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Task
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findByUser($user): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.user = :user')
            ->setParameter('user', $user)
            ->orderBy("t.createdAt", "DESC")
            ->getQuery()
            ->getResult();
    }

    public function findByPage($page, &$nbPages): array
    {
        $res = $this->createQueryBuilder('t')
            ->setMaxResults(10)
            ->setFirstResult(10 * ($page - 1))
            ->getQuery()
            ->getResult();
        
        $resNoPaginate = $this->createQueryBuilder('t')
            ->getQuery()
            ->getResult();
        
        $nbPages = count($resNoPaginate) / 10;
        
        return $res;
    }

    public function findCurrent($page, &$nbPages): array
    {
        $res = $this->createQueryBuilder('t')
            ->where('t.treatedAt is not null')
            ->andWhere('t.finalizedAt is null')
            ->setMaxResults(10)
            ->setFirstResult(10 * ($page - 1))
            ->getQuery()
            ->getResult();
        
        $resNoPaginate = $this->createQueryBuilder('t')
            ->where('t.treatedAt is not null')
            ->andWhere('t.finalizedAt is null')
            ->getQuery()
            ->getResult();
        
        $nbPages = count($resNoPaginate) / 10;

        return $res;
    }

    public function findOver($page, &$nbPages): array
    {
        $res = $this->createQueryBuilder('t')
            ->where('t.treatedAt is not null')
            ->andWhere('t.finalizedAt is not null')
            ->setMaxResults(10)
            ->setFirstResult(10 * ($page - 1))
            ->getQuery()
            ->getResult();

        $resNoPaginate = $this->createQueryBuilder('t')
            ->where('t.treatedAt is not null')
            ->andWhere('t.finalizedAt is not null')
            ->getQuery()
            ->getResult();
        
        $nbPages = count($resNoPaginate) / 10;

        return $res;
    }
}
