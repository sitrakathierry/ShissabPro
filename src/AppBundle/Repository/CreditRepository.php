<?php

namespace AppBundle\Repository;

/**
 * CreditRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CreditRepository extends \Doctrine\ORM\EntityRepository
{
    public function consultation(
        $agence,
        $statut = 100,
        $statut_paiement = 100, 
        $id = 0,
        $type_date = '', 
        $mois = '', 
        $annee = '', 
        $date_specifique = '', 
        $debut_date = '', 
        $fin_date = '',
        $recherche_par = '',
        $a_rechercher = ''
    )
    {
        $em = $this->getEntityManager();
        
        $query = "  select cr.id, date_format(cr.date, '%d/%m/%Y') as date, cr.total as montant_total, LPAD(cr.id, 6, '0') as recu, IF(c.statut = 1,cm.nom_societe,cp.nom) as client, cr.statut, cr.remise, cr.montant_remise, cr.tva, cr.montant_tva, cr.ht, cr.statut_paiement
                    from credit cr
                    left join client c on (cr.client = c.num_police)
                    left join client_morale cm on (c.id_client_morale=cm.id)
                    left join client_physique cp on (c.id_client_physique=cp.id)
                    where cr.id is not null";

        if ($agence) {
            $query .= " and cr.agence = " . $agence ;
        }

        if ($id) {
            $query .= " and cr.id = " . $id ;
        }

        if ($statut != 100 && $statut_paiement != 100) {
            $query .= " and (cr.statut = ".$statut." and cr.statut_paiement=".$statut_paiement.")" ;
        }

        if ($type_date) {
            switch ($type_date) {
                case '1':
                    $now = new \DateTime();
                    $dateNow = $now->format('d-m-Y');
                    $query .= " and date_format(cr.date,'%d-%m-%Y') = '" . $dateNow ."'";
                    break;
                
                case '2':
                    $moisAnnee = $mois . "-" . $annee;
                    $query .= " and date_format(cr.date,'%m-%Y') = '" . $moisAnnee ."'";
                    break;

                case '3':
                    $query .= " and date_format(cr.date,'%Y') = '" . $annee ."'";
                    break;

                case '4':
                    // $date = \DateTime::createFromFormat('j/m/Y', $date_specifique);
                    $query .= " and date_format(cr.date,'%d/%m/%Y') = '" . $date_specifique ."'";
                    break;

                case '5':
                    $query .= " and date_format(cr.date,'%d/%m/%Y') >= '" . $debut_date ."'";
                    $query .= " and date_format(cr.date,'%d/%m/%Y') <= '" . $fin_date ."'";
                    break;
            }
        }

        if($recherche_par){
            if($recherche_par == 1){
                $query .= "  and LPAD(cr.id, 6, '0') LIKE '%" . $a_rechercher . "%'";
            }
            if($recherche_par == 2){
                $query .="  and (cm.nom_societe LIKE '%" . $a_rechercher . "%' or cp.nom LIKE '%" . $a_rechercher . "%')";
            }
        }

        $query .= " order by cr.id desc";

        $statement = $em->getConnection()->prepare($query);

        $statement->execute();

        $result = $statement->fetchAll();

        return $result;
    }
}