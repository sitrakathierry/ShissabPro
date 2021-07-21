<?php

namespace AppBundle\Repository;

/**
 * RavitaillementRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RavitaillementRepository extends \Doctrine\ORM\EntityRepository
{
	public function consultation($agence)
	{
		$em = $this->getEntityManager();
		
		$query = "	select r.id, date_format(r.date, '%d/%m/%Y') as date, r.total as montant_total
					from ravitaillement r
					where r.id is not null ";

		if ($agence) {
			$query .= "	and r.agence = " . $agence ;
		}

		$query .= "	order by r.id desc";

        $statement = $em->getConnection()->prepare($query);

        $statement->execute();

        $result = $statement->fetchAll();

        return $result;
	}
}