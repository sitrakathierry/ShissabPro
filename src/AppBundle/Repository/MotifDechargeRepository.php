<?php

namespace AppBundle\Repository;

/**
 * MotifDechargeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MotifDechargeRepository extends \Doctrine\ORM\EntityRepository
{
    public function list($agence)
    {

        $em = $this->getEntityManager();
        
        $query = "  select m.*
                    from motif_decharge m
                    inner join agence ag on (m.agence = ag.id)
                    ";

        $query .= "  where ag.id = " . $agence ;

        $query .= " order by m.libelle asc";

        $statement = $em->getConnection()->prepare($query);

        $statement->execute();

        $result = $statement->fetchAll();

        return $result;

    }
}
