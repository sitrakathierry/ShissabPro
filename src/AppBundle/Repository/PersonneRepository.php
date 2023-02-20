<?php

namespace AppBundle\Repository;

/**
 * PersonneRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PersonneRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAllPersonne($agenceId)
    {
        $em = $this->getEntityManager(); // GESTIONNAIRE D'ENTITE
        $sql = "SELECT * FROM `personne` WHERE `id_agence` = ? ORDER BY nom_personne ASC" ; // PREPARATION DE LA REQUETE
        $statement = $em->getConnection()->prepare($sql);
        $statement->execute(array($agenceId));
        $result = $statement->fetchAll();
        return $result ; 
    }
}