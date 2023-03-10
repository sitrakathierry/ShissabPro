<?php

namespace AppBundle\Repository;

/**
 * FournisseurRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FournisseurRepository extends \Doctrine\ORM\EntityRepository
{
    public function list($agence)
    {
        $em = $this->getEntityManager();
        
        $query = "  select *
                    from fournisseur f
                    where f.nom is not null ";

        if ($agence) {
            $query .= " and f.agence = " . $agence ;
        }

        $query .= " order by f.nom asc";

        $statement = $em->getConnection()->prepare($query);

        $statement->execute();

        $result = $statement->fetchAll();

        return $result;
    }
}
