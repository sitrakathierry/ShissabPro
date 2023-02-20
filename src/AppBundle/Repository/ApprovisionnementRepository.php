<?php

namespace AppBundle\Repository;

/**
 * ApprovisionnementRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ApprovisionnementRepository extends \Doctrine\ORM\EntityRepository
{
	public function consultation(
		$ravitaillement,
		$entrepot = 0
	)
	{
		$em = $this->getEntityManager();
		
		$query = "	select ap.*, p.nom as produit, vp.prix_vente, p.unite, e.nom as entrepot, ap.charge, ap.prix_revient, vp.marge_type, vp.marge_valeur
					from approvisionnement ap
					left join variation_produit vp on (ap.variation_produit = vp.id)
					left join produit_entrepot pe on (vp.produit_entrepot = pe.id)
					left join entrepot e on (pe.entrepot = e.id)
					left join produit p on (pe.produit = p.id)
					where ap.id is not null ";

		if ($ravitaillement) {
			$query .= "	and ap.ravitaillement = " . $ravitaillement ;
		}

		if ($entrepot) {
			$query .= "	and e.id = " . $entrepot ;
		}

		$query .= "	and p.is_delete IS NULL";

		$query .= "	order by p.nom asc";

        $statement = $em->getConnection()->prepare($query);

        $statement->execute();

        $result = $statement->fetchAll();

        return $result;
	}

	public function entreesSorties(
		$produit_id, 
		$type = null, 
		$annee = null,
		$entrepot = 0,
		$agence = 0,
		$ajourdhui = '',
		$mois = ''
	)
	{

		$entrees = $this->entrees(
			$produit_id, 
			$annee, 
			$entrepot,
			$agence,
			$ajourdhui,
			$mois
		);

		$sorties = $this->sorties(
			$produit_id, 
			$annee, 
			$entrepot,
			$agence,
			$ajourdhui,
			$mois
		);

		if ($type == 1) {
			return $entrees;
		}

		if ($type == 2) {
			return $sorties;
		}

		$data =  array_merge( $entrees, $sorties );

		$id = array_column($data, 'date');

		array_multisort($id, SORT_ASC, $data);

		return $data;
	}

	public function entrees($produit_id, $annee, $entrepot, $agence, $ajourdhui, $mois)
	{

		$em = $this->getEntityManager();
		
		$query = "	select ap.id, date_format(ap.date, '%d/%m/%Y') as date, ap.qte, ap.prix_achat as prix, ap.total, 1 as type, date_format(ap.date, '%m') as mois, p.unite, p.nom, vp.prix_vente, CONCAT(p.code_produit,'/',vp.id) as code_variation, e.nom as entrepot, ap.charge
					from approvisionnement ap
					inner join ravitaillement r on (ap.ravitaillement = r.id)
					left join variation_produit vp on (ap.variation_produit = vp.id)
					left join produit_entrepot pe on (vp.produit_entrepot = pe.id)
					left join entrepot e on (pe.entrepot = e.id)
					left join produit p on (pe.produit = p.id)
					where ap.id is not null ";

		if ($produit_id) {
			$query .= "	and p.id = " . $produit_id ;
		}

		if ($agence) {
			$query .= "	and r.agence = " . $agence ;
		}	

		if ($annee) {
			$query .= "	and date_format(ap.date, '%Y') = " . $annee ;
		}

		if ($entrepot) {
			$query .= "	and e.id = " . $entrepot ;
		}

		$query .= "	and p.is_delete IS NULL";

		$query .= "	order by ap.date desc";

        $statement = $em->getConnection()->prepare($query);

        $statement->execute();

        $result = $statement->fetchAll();

        return $result;
	}

	public function sorties($produit_id, $annee, $entrepot, $agence, $ajourdhui, $mois)
	{

		$em = $this->getEntityManager();
		
		$query = "	select pa.id, date_format(pa.date, '%d/%m/%Y') as date, pa.qte, pa.pu as prix, pa.total, 2 as type, date_format(pa.date, '%m') as mois, p.unite, p.nom, vp.prix_vente, CONCAT(p.code_produit,'/',vp.id) as code_variation, e.nom as entrepot
					from pannier pa
					inner join commande c on (pa.commande = c.id)
					left join variation_produit vp on (pa.variation_produit = vp.id)
					left join produit_entrepot pe on (vp.produit_entrepot = pe.id)
					left join entrepot e on (pe.entrepot = e.id)
					left join produit p on (pe.produit = p.id)
					where pa.id is not null ";

		if ($produit_id) {
			$query .= "	and p.id = " . $produit_id ;
		}

		if ($agence) {
			$query .= "	and c.agence = " . $agence ;

		}	

		if ($annee) {
			$query .= "	and date_format(pa.date, '%Y') = " . $annee ;
			if ($mois) {
				$query .= "	and date_format(pa.date, '%m') = " . $mois ;
			}
			
		}

		if ($ajourdhui) {
			$now = new \DateTime();
        	$dateNow = $now->format('d-m-Y');
			$query .= "	and date_format(pa.date, '%d-%m-%Y') = " . $dateNow ;

		}

		if ($entrepot) {
			$query .= "	and e.id = " . $entrepot ;
		}

		$query .= "	and p.is_delete IS NULL";

		$query .= "	order by pa.date desc";

        $statement = $em->getConnection()->prepare($query);

        $statement->execute();

        $result = $statement->fetchAll();

        return $result;
	}
}
