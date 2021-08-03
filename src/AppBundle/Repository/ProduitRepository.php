<?php

namespace AppBundle\Repository;

/**
 * ProduitRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProduitRepository extends \Doctrine\ORM\EntityRepository
{
	public function getList($agence, $recherche_par, $a_rechercher)
	{
		$em = $this->getEntityManager();
		
		$query = "	select *
					from produit p
					where p.nom is not null ";

		if ($agence) {
			$query .= "	and p.agence = " . $agence ;
		}

		if ($recherche_par == 0) {
			$query .= "	and p.code_produit like '%" . $a_rechercher . "%'";
		}

		if ($recherche_par == 1) {
			$query .= "	and p.nom like '%" . $a_rechercher . "%'";
		}

		$query .= "	order by p.nom asc";

        $statement = $em->getConnection()->prepare($query);

        $statement->execute();

        $result = $statement->fetchAll();

        return $result;
	}
}
