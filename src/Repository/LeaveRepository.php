<?php

namespace App\Repository;

use App\Entity\Leave;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Leave>
 *
 * @method Leave|null find($id, $lockMode = null, $lockVersion = null)
 * @method Leave|null findOneBy(array $criteria, array $orderBy = null)
 * @method Leave[]    findAll()
 * @method Leave[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LeaveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Leave::class);
    }

    public function countLeavesRequestedToday(): int
    {
        $today = (new \DateTime())->setTime(0, 0, 0);

        return $this->createQueryBuilder('l')
            ->select('count(l.id)')
            ->where('l.dateajout = :today')
            ->setParameter('today', $today)
            ->getQuery()
            ->getSingleScalarResult();
    }

//    /**
//     * @return Leave[] Returns an array of Leave objects
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

//    public function findOneBySomeField($value): ?Leave
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
