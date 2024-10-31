<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Client>
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    public function paginateClients(int $page, int $limit, string $telephone = '*'): array
    {
        $qb = $this->createQueryBuilder('c');

        // Si un numéro de téléphone est fourni, ajoutez-le à la requête
        if ($telephone !== '*') {
            $qb->andWhere('c.telephone LIKE :telephone')
               ->setParameter('telephone', '%' . $telephone . '%');
        }

        // Pagination
        return $qb->getQuery()
                   ->setFirstResult(($page - 1) * $limit) // Début de la page
                   ->setMaxResults($limit) // Limite des résultats
                   ->getResult();
    }

    public function findAllQuery(array $criteria = []): Query
    {
        $qb = $this->createQueryBuilder('c');

        // Ajoutez une condition de recherche si elle est fournie
        if (!empty($criteria['search'])) {
            $qb->andWhere('c.name LIKE :search OR c.telephone LIKE :search')
               ->setParameter('search', '%' . $criteria['search'] . '%');
        }

        return $qb->getQuery();
    }
}
