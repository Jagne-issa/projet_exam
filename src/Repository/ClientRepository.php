<?php

// src/Repository/ClientRepository.php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

public function searchByQuery(ClientSearch $clientSearch)
{
    $qb = $this->createQueryBuilder('c');

    if ($clientSearch->getTelephone()) {
        $qb->andWhere('c.telephone = :telephone')
           ->setParameter('telephone', $clientSearch->getTelephone());
    }
    
    if ($clientSearch->getSurname()) {
        $qb->andWhere('c.surname LIKE :surname')
           ->setParameter('surname', '%' . $clientSearch->getSurname() . '%');
    }

    if ($clientSearch->getEnum()) {
        $qb->andWhere('c.enum = :enum')
           ->setParameter('enum', $clientSearch->getEnum());
    }

    return $qb->getQuery()->getResult();
}

}
