<?php

namespace App\Repository;

use App\Entity\Work;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Work|null find($id, $lockMode = null, $lockVersion = null)
 * @method Work|null findOneBy(array $criteria, array $orderBy = null)
 * @method Work[]    findAll()
 * @method Work[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Work::class);
    }

    /**
     * @param string $column
     * @param string $direction
     * @param bool $payed
     *
     * @return Work[]
     */
    public function findAllOrdered(string $column, string $direction, bool $payed = false): array
    {
        $disabledStates = [7];

        $queryBuilder = $this->createQueryBuilder("work")
            ->leftJoin("work.client", "client")
            ->leftJoin("work.state", "state")
            ->orderBy($column, $direction)
            ->where("state");

        if (!$payed) {
            $disabledStates[] = 6;
        }

        $queryBuilder->where("state.id NOT IN (:disabled_states)")
            ->setParameter("disabled_states", $disabledStates);

        return $queryBuilder->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Work[] Returns an array of Work objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Work
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
