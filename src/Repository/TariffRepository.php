<?php

namespace App\Repository;

use App\Entity\Tariff;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tariff|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tariff|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tariff[]    findAll()
 * @method Tariff[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TariffRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tariff::class);
    }

    public function findAllUnitsSummarized()
    {
        return $this->createQueryBuilder("tariff", "tariff.id")
            ->leftJoin("tariff.units", "unit")
            ->select("tariff.id, SUM(unit.amount) AS amount")
            ->where("unit.created_at >= :first_day")
            ->andWhere("unit.created_at <= :last_day")
            ->setParameter("first_day", date("Y-m-01"))
            ->setParameter("last_day", date("Y-m-t"))
            ->groupBy("tariff.id")
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Tariff[] Returns an array of Tariff objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Tariff
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
