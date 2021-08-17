<?php

namespace AppBundle\Repository;

/**
 * BanqueRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BanqueRepository extends \Doctrine\ORM\EntityRepository
{
	public function list()
	{

		$em = $this->getEntityManager();
		
		$query = "	select *
					from banque";

        $statement = $em->getConnection()->prepare($query);

        $statement->execute();

        $result = $statement->fetchAll();

        return $result;

	}
}