<?php

namespace AppBundle\Repository;

/**
 * FactureRestaurantDetailsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FactureRestaurantDetailsRepository extends \Doctrine\ORM\EntityRepository
{
    public function accompagnementsDetails($accompagnements)
    {

        $data = [];

        $accompagnements = json_decode($accompagnements);

        if (is_object($accompagnements)) {
            $accompagnement_list = $accompagnements->accompagnement;
            $qte_accompagnement_list = $accompagnements->qte_accompagnement;
            $prix_accompagnement_list = $accompagnements->prix_accompagnement;
            $total_accompagnement = $accompagnements->total_accompagnement;

            foreach ($accompagnement_list as $key => $value) {
                $item = array(
                    'accompagnement' => $accompagnement_list[$key],
                    'qte_accompagnement' => $qte_accompagnement_list[$key],
                    'prix_accompagnement' => $prix_accompagnement_list[$key],
                );

                array_push($data, $item);
            }

            return array(
                'accompagnements' => $data,
                'total_accompagnement' => $total_accompagnement,
            );
        }

        return array(
            'accompagnements' => $data,
            'total_accompagnement' => 0,
        );


    }
}