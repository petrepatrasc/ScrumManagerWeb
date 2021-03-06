<?php

namespace ScrumManager\ApiBundle\Repository;


use Doctrine\ORM\EntityRepository;
use ScrumManager\ApiBundle\Entity\Email;
use DateTime;

class EmailRepository extends EntityRepository {

    /**
     * Create a new entity in the database.
     * @param Email $entity The entity that should be created in the database.
     * @return Email The entity that has been persisted to the database.
     */
    public function create(Email $entity) {
        $this->_em->persist($entity);
        $this->_em->flush();

        return $entity;
    }

    /**
     * Update an existing entity in the database.
     * @param Email $entity The entity that should be updated in the database.
     * @return Email The entity that has been persisted to the database.
     */
    public function updateOne(Email $entity) {
        $entity->setUpdatedAt(new DateTime('now'));
        return $this->create($entity);
    }

    /**
     * Retrieve the last entry stored in the database.
     * @return Email The last Email entry stored in the database.
     */
    public function retrieveLast() {
        $query = $this->getEntityManager()->createQuery(
            "SELECT e
            FROM ScrumManagerApiBundle:Email e
            ORDER BY e.id DESC"
        )->setMaxResults(1);

        return $query->getSingleResult();
    }
}