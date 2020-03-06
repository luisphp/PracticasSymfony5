<?php


namespace App\Repository;
use App\Entity\Animal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Common\Persistence\ManagerRegistry;


class AnimalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry){
        parent::__construct($registry, Animal::class);
    }

    public function findOneByRaza($order){
            //Query builder:
        $qb = $this->createQueryBuilder('a')
                            ->andWhere("a.raza = 'boxer'")
                            ->getQuery();
                        $resultset = $qb->execute();

    return $resultset;

    }

}

