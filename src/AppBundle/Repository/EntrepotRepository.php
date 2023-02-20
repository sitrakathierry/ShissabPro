<?php

namespace AppBundle\Repository;

/**
 * EntrepotRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EntrepotRepository extends \Doctrine\ORM\EntityRepository
{
    public function list($agence)
    {
        $em = $this->getEntityManager();
        
        $query = "  select e.id, e.nom, e.adresse, e.tel
                    from entrepot e
                    where e.nom is not null ";

        if ($agence) {
            $query .= " and e.agence = " . $agence ;
        }

        $query .= " order by e.nom asc";

        $statement = $em->getConnection()->prepare($query);

        $statement->execute();

        $result = $statement->fetchAll();

        return $result;
    }
}
