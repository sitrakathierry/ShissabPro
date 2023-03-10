<?php

namespace AppBundle\Repository;

/**
 * ServiceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ServiceRepository extends \Doctrine\ORM\EntityRepository
{
	public function getList(
		$agence, 
		$recherche_par = '', 
		$a_rechercher = ''
	)
	{
		$em = $this->getEntityManager();
		
		$query = "	select *
					from service s
					where s.nom is not null ";

		if ($agence) {
			$query .= "	and s.agence = " . $agence ;
		}

		if ($recherche_par == 1) {
			$query .= "	and s.nom like '%" . $a_rechercher . "%'";
		}

		$query .= "	order by s.nom asc";

        $statement = $em->getConnection()->prepare($query);

        $statement->execute();

        $result = $statement->fetchAll();

        return $result;
	}
}
