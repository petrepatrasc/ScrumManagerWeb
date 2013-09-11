<?php

namespace ScrumManager\ApiBundle\Entity;

/**
 * Interface that ensures that an entity can in fact be transformed into an array, while also offering
 * functionality for loading data from an array.
 * @package ScrumManager\ApiBundle\Entity\
 */
interface SerializableInterface {

    /**
     * Makes an entity from an array and returns it.
     * @param array $params The parameters that should be used creating the entity.
     * @param mixed $entity The entity which we should already use if we just want to update an entry with some data.
     * @return mixed The entity that was generated after loading it from the array.
     */
    public static function makeFromArray($params, $entity = null);

    /**
     * Return the entity by mapping its fields into an array.
     * @return array Array containing the mapping of the entity.
     */
    public function toArray();

    /**
     * Return the entity by mapping its fields into an array, however only returning data that should be returned via
     * the API and that is considered "safe" for interception.
     * @return array Array containing the mapping of the entity.
     */
    public function toSafeArray();
}