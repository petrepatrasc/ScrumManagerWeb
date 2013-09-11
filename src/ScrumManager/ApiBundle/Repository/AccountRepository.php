<?php

namespace ScrumManager\ApiBundle\Repository;


use Doctrine\ORM\EntityRepository;
use ScrumManager\ApiBundle\Entity\Account;

/**
 * Class handles all of the DB mapping procedures.
 * @package ScrumManager\ApiBundle\Repository
 */
class AccountRepository extends EntityRepository {

    /**
     * Create a new entity in the database.
     * @param Account $entity The entity that should be created in the database.
     * @return Account The entity that has been persisted to the database.
     */
    public function create(Account $entity) {
        $this->_em->persist($entity);
        $this->_em->flush();

        return $entity;
    }
}