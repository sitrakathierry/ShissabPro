<?php

namespace AppBundle\Repository;

/**
 * LibelleGeneralRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LibelleGeneralRepository extends \Doctrine\ORM\EntityRepository
{
    public function list($agence)
    {
        $em = $this->getEntityManager();
        
        $query = "  select l.*
                    from libelle_general l
                    where l.nom is not null ";

        if ($agence) {
            $query .= " and l.agence = " . $agence ;
        }

        $query .= " order by l.nom asc";

        $statement = $em->getConnection()->prepare($query);

        $statement->execute();

        $result = $statement->fetchAll();

        return $result;
    }
}
