<?php

namespace AppBundle\Repository;

/**
 * CategoriePlatRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoriePlatRepository extends \Doctrine\ORM\EntityRepository
{
    public function list($agence)
    {
        $em = $this->getEntityManager();
        
        $query = "  select c.id, c.nom, c.description
                    from categorie_plat c
                    where c.nom is not null ";

        if ($agence) {
            $query .= " and c.agence = " . $agence ;
        }

        $query .= " order by c.nom asc";

        $statement = $em->getConnection()->prepare($query);

        $statement->execute();

        $result = $statement->fetchAll();

        return $result;
    }
}
