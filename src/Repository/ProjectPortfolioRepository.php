<?php

namespace App\Repository;

use App\Entity\ProjectPortfolio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProjectPortfolio|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectPortfolio|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectPortfolio[]    findAll()
 * @method ProjectPortfolio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectPortfolioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectPortfolio::class);
    }

    /*
    public function findOneBySomeField($value): ?ProjectPortfolio
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
