<?php

namespace AppBundle\Repository;

/**
 * MouvementRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MouvementRepository extends \Doctrine\ORM\EntityRepository
{
    public function list(
        $banque, 
        $compte_bancaire, 
        $operation, 
        $type_date, 
        $mois, 
        $annee, 
        $date_specifique, 
        $debut_date, 
        $fin_date,
        $agence_id = 0
    )
    {

        $em = $this->getEntityManager();
        
        $query = "  select mv.id as id, date_format(mv.date,'%d/%m/%Y') as date,IF(mv.operation = 1,'Dépôt','Retrait') as operation, mv.num_operation, If(mv.type_operation = 1,'CASH',IF(mv.type_operation = 2,'Chèque','Virement')) as type_operation, bq.nom as banque, cb.num_compte as compte_bancaire, mv.montant, mv.op_nom
                    from mouvement mv
                    inner join compte_bancaire cb on (mv.compte_bancaire = cb.id)
                    inner join banque bq on (cb.banque = bq.id)
                    left join agence ag on (cb.agence = ag.id)";

        $where = " where cb.id is not null ";

        if ($banque != 0) {
            $where .= " and bq.id = " . $banque;
        }

        if ($compte_bancaire != 0) {
            $where .= " and cb.id = " . $compte_bancaire;
        }

        if ($operation != 0) {
            $where .= " and mv.operation = " . $operation;
        }

        if ($agence_id) {
            $where .= " and ag.id = " . $agence_id;
        }

        if ($type_date) {
            switch ($type_date) {
                case '1':
                    $now = new \DateTime();
                    $dateNow = $now->format('d-m-Y');
                    $where .= " and date_format(mv.date,'%d-%m-%Y') = '" . $dateNow ."'";
                    break;
                
                case '2':
                    $moisAnnee = $mois . "-" . $annee;
                    $where .= " and date_format(mv.date,'%m-%Y') = '" . $moisAnnee ."'";
                    break;

                case '3':
                    $where .= " and date_format(mv.date,'%Y') = '" . $annee ."'";
                    break;

                case '4':
                    // $date = \DateTime::createFromFormat('j/m/Y', $date_specifique);
                    $where .= " and date_format(mv.date,'%d/%m/%Y') = '" . $date_specifique ."'";
                    break;

                case '5':
                    $where .= " and date_format(mv.date,'%d/%m/%Y') >= '" . $debut_date ."'";
                    $where .= " and date_format(mv.date,'%d/%m/%Y') <= '" . $fin_date ."'";
                    break;
            }
        }


        $query .= $where;

        $statement = $em->getConnection()->prepare($query);

        $statement->execute();

        $result = $statement->fetchAll();

        return $result;

    }
}
