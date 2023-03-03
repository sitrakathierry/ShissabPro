<?php
/**
 * Created by PhpStorm.
 * User: SITRAKA
 * Date: 06/02/2020
 * Time: 10:25
 */

namespace ClientBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Client;
use AppBundle\Entity\TypeSociete;
use AppBundle\Entity\ClientMorale;
use AppBundle\Entity\ClientPhysique;
use AppBundle\Entity\Contact;
use Symfony\Component\HttpFoundation\Response;
use MenuBundle\Controller\BaseController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ClientController extends Controller
{
    public function indexAction()
    {
        return $this->render('@Client/Client/index.html.twig');
    }

    public function dashboardAction(Request $request)
    {

        //$this->verifyPermission($request);

        return $this->render('@Client/Client/dashboard.html.twig');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function listeAction(Request $request)
    {
        $types = json_decode($request->request->get('types'));

        $clients = $this->getDoctrine()->getRepository('AppBundle:Client')
            ->liste($types);

        $results = [];

        foreach ($clients as $client)
        {

            if ($client->getArchive() == 0) {

                if ($client->getType() == 0) {
                    $prenom = $client->getPrenom();
                } else {
                    $prenom = $client->getFormeJuridique();
                }

                $results[] = (object)
                [
                    'id' => $client->getId(),
                    'n' => $client->getNom(),
                    'p' => $prenom
                ];
            }

        }

        return new JsonResponse($results);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function clientOneAction(Request $request)
    {
        $client = $request->request->get('id');
        $client = $this->getDoctrine()->getRepository('AppBundle:Client')
            ->find($client);

        return new JsonResponse((object)
        [
            'id' => $client->getId(),
            'n' => $client->getNom(),
            'p' => $client->getPrenom()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editeurAction(Request $request)
    {
        $client = $request->request->get('id');
        $client = $this->getDoctrine()->getRepository('AppBundle:Client')
            ->find($client);

        /** @var Contact[] $contacts */
        $contacts = [];
        /** @var Contact $adresse */
        $adresse = null;
        /** @var Contact $bp */
        $bp = null;

        if ($client)
        {
            $contacts = $this->getDoctrine()->getRepository('AppBundle:Contact')
                ->getContacts($client,false);

            $adresse = $this->getDoctrine()->getRepository('AppBundle:Contact')
                ->getOneType($client, 0);
            $bp = $this->getDoctrine()->getRepository('AppBundle:Contact')
                ->getOneType($client, 4);
        }

        return $this->render('@Client/Client/editeur.html.twig',[
            'client' => $client,
            'contacts' => $contacts,
            'adresse' => $adresse,
            'bp' => $bp
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function editAction(Request $request)
    {
        $action = intval($request->request->get('act'));
        $client = intval($request->request->get('id'));
        $props = json_decode($request->request->get('props'));
        $contacts = json_decode($request->request->get('contacts'));

        $type = $props->t;
        $nom = $props->n;
        $prenom = $props->p;
        $adresse = $props->a;
        $bp = $props->bp;

        /** @var Client $client */
        $client = $this->getDoctrine()->getRepository('AppBundle:Client')
            ->find($client);

        $em = $this->getDoctrine()->getManager();
        if ($action == 0)
        {
            $add = false;

            if (!$client)
            {
                $client = new Client();
                $add = true;
            }

            $client
                ->setType($type)
                ->setNom($nom)
                ->setPrenom($prenom);

            if ($add) $em->persist($client);
        }
        elseif ($action == 2 && $client) $em->remove($client);
        
        $em->flush();
        
        if ($action == 0)
        {
            $this->getDoctrine()->getRepository('AppBundle:Contact')
                ->updateOneType($client, 0, $adresse);
            $this->getDoctrine()->getRepository('AppBundle:Contact')
                ->updateOneType($client, 4, $bp);

            foreach ($contacts as $ct)
            {
                $this->getDoctrine()->getRepository('AppBundle:Contact')
                    ->updateContact($client, $ct->t, $ct->v, $ct->id);
            }
        }
        return new Response(1);
    }

    public function addAction($fact)
    {
        $typeSocieteList = $this->getDoctrine()
                    ->getRepository('AppBundle:TypeSociete')
                    ->findAll();

        $typeSocialList = $this->getDoctrine()
                    ->getRepository('AppBundle:TypeSocial')
                    ->findAll();

        $agences = $this->getDoctrine()
                    ->getRepository('AppBundle:Agence')
                    ->findAll();

        $permission_user = $this->get('app.permission_user');
        $user = $this->getUser();
        $permissions = $permission_user->getPermissions($user);

        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        // if (!$permissions->client->create) {
        //     throw new AccessDeniedException('Accès non autorisé');
        // }

        return $this->render('ClientBundle:Client:add.html.twig',[
            'typeSocieteList' => $typeSocieteList,
            'typeSocialList' => $typeSocialList,
            'agences' => $agences,
            'userAgence' => $userAgence,
            'fact' => $fact
        ]);
    }

    public function saveAction(Request $request)
    {


        $si_facture = $request->request->get('si_facture');
        $statut = $request->request->get('statut');
        $agence = $request->request->get('agence');
        $num_police = $request->request->get('num_police');

        // personne morale
        $clm_nom_societe = $request->request->get('clm_nom_societe');
        $clm_nom_gerant = $request->request->get('clm_nom_gerant');
        $clm_adresse = $request->request->get('clm_adresse');
        $clm_tel_fixe = $request->request->get('clm_tel_fixe');
        $clm_fax = $request->request->get('clm_fax');
        $clm_email = $request->request->get('clm_email');
        $clm_domaine = $request->request->get('clm_domaine');
        $clm_num_registre = $request->request->get('clm_num_registre');
        $clm_type_societe = $request->request->get('clm_type_societe');
        $clm_nom_pers_contact = $request->request->get('clm_nom_pers_contact');
        $clm_tel_contact = $request->request->get('clm_tel_contact');
        $clm_email_contact = $request->request->get('clm_email_contact');
        $clp_ddn = $request->request->get('clp_ddn');
        $clp_ldn = $request->request->get('clp_ldn');
        $clp_profession = $request->request->get('clp_profession');

        // personne physique
        $clp_nom = $request->request->get('clp_nom');
        $clp_nin = $request->request->get('clp_nin');
        $clp_adresse = $request->request->get('clp_adresse');
        $clp_quartier = $request->request->get('clp_quartier');
        $clp_tel = $request->request->get('clp_tel');
        $clp_email = $request->request->get('clp_email');
        $clp_sexe = $request->request->get('clp_sexe');
        $clp_situation = $request->request->get('clp_situation');
        $clp_lieu_travail = $request->request->get('clp_lieu_travail');
        $clp_lien_parente = $request->request->get('clp_lien_parente');
        $clp_type_social = $request->request->get('clp_type_social');
        $clp_observation = $request->request->get('clp_observation');
        $clp_nom_pers_contact = $request->request->get('clp_nom_pers_contact');
        $clp_tel_pers_contact = $request->request->get('clp_tel_pers_contact');
        $clp_email_pers_contact = $request->request->get('clp_email_pers_contact');
        $clp_adresse_pers_contact = $request->request->get('clp_adresse_pers_contact');

        if ($num_police) {
            $client = $this->getDoctrine()
                            ->getRepository('AppBundle:Client')
                            ->find($num_police);
        } else {
            $client = new Client();

            $exist =  $this->checkExist( $request );

            if (!!$exist) {
                return new JsonResponse(array(
                    'success' => false,
                    'id' => $exist
                ));
            }

            

        }


        $client->setStatut($statut);

        $agence = $this->getDoctrine()
                            ->getRepository('AppBundle:Agence')
                            ->find($agence);

        $client->setAgence($agence);

        // personne morale
        if ($statut == 1) {
            if ($num_police) {
                $client_morale = $client->getIdClientMorale();
            } else {
                $client_morale = new ClientMorale();
            }
            $client_morale->setNomSociete($clm_nom_societe);
            $client_morale->setNomGerant($clm_nom_gerant);
            $client_morale->setAdresse($clm_adresse);
            $client_morale->setTelFixe($clm_tel_fixe);
            $client_morale->setFax($clm_fax);
            $client_morale->setEmail($clm_email);
            $client_morale->setDomaine($clm_domaine);
            $client_morale->setNumRegistre($clm_num_registre);
            $client_morale->setNomPersContact($clm_nom_pers_contact);
            $client_morale->setTelPersContact($clm_tel_contact);
            $client_morale->setEmailPersContact($clm_email_contact);
            $typeSociete = $this->getDoctrine()
                            ->getRepository('AppBundle:TypeSociete')
                            ->find($clm_type_societe);
            $client_morale->setIdTypeSociete($typeSociete);
            $em = $this->getDoctrine()->getManager();
            $em->persist($client_morale);
            $em->flush();
            $client->setIdClientMorale($client_morale);
            $em->persist($client);
            $em->flush();
        } else {
            if ($num_police) {
                $client_physique = $client->getIdClientPhysique();
            } else {
                $client_physique = new ClientPhysique();
            }
            $client_physique->setNom($clp_nom);
            $client_physique->setNin($clp_nin);
            $client_physique->setAdresse($clp_adresse);
            $client_physique->setQuartier($clp_quartier);
            $client_physique->setTel($clp_tel);
            $client_physique->setEmail($clp_email);
            $client_physique->setSexe($clp_sexe);
            $client_physique->setSituation($clp_situation);
            $client_physique->setLieuTravail($clp_lieu_travail);
            $client_physique->setNomPersContact($clp_nom_pers_contact);
            $client_physique->setTelPersContact($clp_tel_pers_contact);
            $client_physique->setEmailPersContact($clp_email_pers_contact);
            $client_physique->setAdressePersContact($clp_adresse_pers_contact);
            $client_physique->setLienParente($clp_lien_parente);
            $client_physique->setObservation($clp_observation);
            if ($clp_ddn) {
                $client_physique->setDdn(\DateTime::createFromFormat('j/m/Y', $clp_ddn));
            }
            $client_physique->setLdn($clp_ldn);
            $client_physique->setProfession($clp_profession);

            // $typeSocial = $this->getDoctrine()
            //                 ->getRepository('AppBundle:TypeSocial')
            //                 ->find($clp_type_social);
            // $client_physique->setIdTypeSocial($typeSocial);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($client_physique);
            $em->flush();
            $client->setIdClientPhysique($client_physique);
            $em->persist($client);
            $em->flush();
        }

        if ($num_police) {

            $logs = $this->get('app.logs');
            $user = $this->getUser();
            $logs->setStory($user,'Modification Fiche Client N°' . $client->getFormattedNum());

            // return $this->redirectToRoute('client_show',array(
            //     'id' => $client->getNumPolice()
            // ));
        } else {
            $logs = $this->get('app.logs');
            $user = $this->getUser();
            $logs->setStory($user,'Création Fiche Client N°' . $client->getFormattedNum());
            // return $this->redirectToRoute('client_add');
        }

        return new JsonResponse(array(
            'success' => true,
            'id' => $num_police,
            'si_facture' => $si_facture
        ));

    }

    public function showAction($id)
    {
        $client  = $this->getDoctrine()
                        ->getRepository('AppBundle:Client')
                        ->find($id);

        $client_morale = $client
                        ->getIdClientMorale();

        $client_physique = $client
                        ->getIdClientPhysique();

        $typeSocieteList = $this->getDoctrine()
                    ->getRepository('AppBundle:TypeSociete')
                    ->findAll();

        $typeSocialList = $this->getDoctrine()
                    ->getRepository('AppBundle:TypeSocial')
                    ->findAll();

        $agences = $this->getDoctrine()
                    ->getRepository('AppBundle:Agence')
                    ->findAll();

        $permission_user = $this->get('app.permission_user');
        $user = $this->getUser();
        $permissions = $permission_user->getPermissions($user);

         return $this->render('ClientBundle:Client:show.html.twig', array(
            'client' => $client,
            'client_morale' => $client_morale,
            'client_physique' => $client_physique,
            'typeSocieteList' => $typeSocieteList,
            'typeSocialList' => $typeSocialList,
            'agences' => $agences,
            'permissions' => $permissions
        ));
    }

    // public function clientListAction(Request $request)
    // {
    //     //$this->verifyPermission($request);
        
    //     return $this->render('ClientBundle:Client:list.html.twig');
    // }

    public function findAction(Request $request)
    {
        $value = $request->request->get('value');
        $by = $request->request->get('by');

        // var_dump($by);die();

        if ($by == 1) {
            $data  = $this->getDoctrine()
                            ->getRepository('AppBundle:Client')
                            ->byName($value);
        } else {
            $data  = $this->getDoctrine()
                            ->getRepository('AppBundle:Client')
                            ->byPolice($value);
        }


        return $this->render('ClientBundle:Client:result.html.twig', array(
            'data' => $data
        ));
    }

    public function clientAdresseAction($id)
    {

        $client  = $this->getDoctrine()
                        ->getRepository('AppBundle:Client')
                        ->find($id);

        $adresse = $this->getDoctrine()
                        ->getRepository('AppBundle:Contact')
                        ->findOneBy(array(
                            'client' => $client,
                            'type' => 0
                        ));

         return new JsonResponse($adresse->getValeur());
    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $bondCommande = $this->getDoctrine()
            ->getRepository('AppBundle:BonCommande')
            ->findBy(array(
                'client' => $id
            ));
        foreach ($bondCommande as $bondCommande) {
            $em->remove($bondCommande);
            $em->flush();
        }

        $bondLivraison = $this->getDoctrine()
            ->getRepository('AppBundle:BonLivraison')
            ->findBy(array(
                'client' => $id
            ));
        foreach ($bondLivraison as $bondLivraison) {
            $em->remove($bondLivraison);
            $em->flush();
        }

        $booking = $this->getDoctrine()
            ->getRepository('AppBundle:Booking')
            ->findBy(array(
                'client' => $id
            ));
        foreach ($booking as $booking) {
            $em->remove($booking);
            $em->flush();
        }

        $facture = $this->getDoctrine()
            ->getRepository('AppBundle:Facture')
            ->findBy(array(
                'client' => $id
            ));
        foreach ($facture as $facture) {
            $em->remove($facture);
            $em->flush();
        }

        $credit = $this->getDoctrine()
            ->getRepository('AppBundle:Credit')
            ->findBy(array(
                'client' => $id
            ));
        foreach ($credit as $credit) {
            $em->remove($credit);
            $em->flush();
        }

        $client  = $this->getDoctrine()
                        ->getRepository('AppBundle:Client')
                        ->find($id);

        $em->remove($client);
        $em->flush();

        $logs = $this->get('app.logs');
        $user = $this->getUser();
        $logs->setStory($user,'Suppression Fiche Client N°' . $client->getFormattedNum());        

        return new Response(200);
        
    }

    public function clientArchivedListAction(Request $request)
    {
        //$this->verifyPermission($request);
        
        return $this->render('ClientBundle:Client:archived.html.twig');

    }

    public function findArchivedAction(Request $request)
    {
        $value = $request->request->get('value');
        $data  = $this->getDoctrine()
                        ->getRepository('AppBundle:Client')
                        ->byName($value,1);

        return $this->render('ClientBundle:Client:result-archived.html.twig', array(
            'data' => $data
        ));
    }

    public function deleteDefinitivelyAction($id, $ajax = 0)
    {
        $client  = $this->getDoctrine()
                        ->getRepository('AppBundle:Client')
                        ->find($id);


        $em = $this->getDoctrine()->getManager();
        
        $contacts = $this->getDoctrine()
                         ->getRepository('AppBundle:Contact')
                         ->findBy(array(
                            'client' => $client
                         ));

        if (!empty($contacts)) {
            foreach ($contacts as $contact) {
                $em->remove($contact);
                $em->flush();
            }
        }

        $dossiers = $this->getDoctrine()
                         ->getRepository('AppBundle:Dossier')
                         ->findBy(array(
                            'client' => $client
                         ));

        if (!empty($dossiers)) {
            foreach ($dossiers as $dossier) {
                $details = $this->getDoctrine()
                         ->getRepository('AppBundle:DossierDetail')
                         ->findBy(array(
                            'dossier' => $dossier
                         ));
                if (!empty($details)) {
                    foreach ($details as $detail) {
                        $em->remove($detail);
                        $em->flush();
                    }
                }
                $em->remove($dossier);
                $em->flush();
            }
        }


        $em->remove($client);
        $em->flush();

        if ($ajax == 1) {
            return new JsonResponse(200);
        }

        return $this->redirectToRoute('client_dashboard');
    }

    public function existAction($id)
    {

        $client  = $this->getDoctrine()
                        ->getRepository('AppBundle:Client')
                        ->find($id);

        return $this->render('ClientBundle:Client:exist.html.twig', array(
            'client' => $client
        ));
        
    }

    public function restoreAction($id, $ajax = 0)
    {
        $client  = $this->getDoctrine()
                        ->getRepository('AppBundle:Client')
                        ->find($id);

        $client->setArchive(0);

        $em = $this->getDoctrine()->getManager();
        $em->persist($client);
        $em->flush();

        if ($ajax == 1) {
            return new JsonResponse(200);
        }

        return $this->redirectToRoute('client_dashboard');
        
    }

    public function clientListAction()
    {
        $agences  = $this->getDoctrine()
                        ->getRepository('AppBundle:Agence')
                        ->findAll();

        $permission_user = $this->get('app.permission_user');
        $user = $this->getUser();
        $permissions = $permission_user->getPermissions($user);

        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        return $this->render('ClientBundle:Client:list.html.twig',array(
            'agences' => $agences,
            'userAgence' => $userAgence,
        ));
    }

    public function clientMoraleListAction(Request $request)
    {

        $recherche_par = $request->request->get('recherche_par');
        $a_rechercher = $request->request->get('a_rechercher');
        $agence = $request->request->get('agence');


        if ($recherche_par != '' && $a_rechercher != '') {
            switch ($recherche_par) {
                case "1":
                    $clientMoraleList = $this->getDoctrine()
                                ->getRepository('AppBundle:Client')
                                ->listByNPolice(1,$a_rechercher,$agence);
                    break;
                case "2":
                    $clientMoraleList = $this->getDoctrine()
                                ->getRepository('AppBundle:Client')
                                ->listByNom(1,$a_rechercher,$agence);
                    break;
            }
        } else{
            $clientMoraleList = $this->getDoctrine()
                                ->getRepository('AppBundle:Client')
                                ->liste(1,$agence);
        }


        return new JsonResponse($clientMoraleList);
    }

    public function clientPhysiqueListAction(Request $request)
    {

        $recherche_par = $request->request->get('recherche_par');
        $a_rechercher = $request->request->get('a_rechercher');
        $agence = $request->request->get('agence');
        // var_dump($recherche_par,$a_rechercher);die();


        if ($recherche_par != '' && $a_rechercher != '') {
            switch ($recherche_par) {
                case "1":
                    $clientMoraleList = $this->getDoctrine()
                                ->getRepository('AppBundle:Client')
                                ->listByNPolice(2,$a_rechercher,$agence);
                    break;
                case "2":
                    $clientMoraleList = $this->getDoctrine()
                                ->getRepository('AppBundle:Client')
                                ->listByNom(2,$a_rechercher,$agence);
                    break;
            }
        } else{

            $clientMoraleList = $this->getDoctrine()
                                ->getRepository('AppBundle:Client')
                                ->liste(2,$agence);
        }


        return new JsonResponse($clientMoraleList);
    }

    public function clientTousListAction(Request $request)
    {

        $recherche_par = $request->request->get('recherche_par');
        $a_rechercher = $request->request->get('a_rechercher');
        $agence = $request->request->get('agence');

        if ($recherche_par != '' && $a_rechercher != '') {
            switch ($recherche_par) {
                case "1":
                    $list = $this->getDoctrine()
                                ->getRepository('AppBundle:Client')
                                ->listByNPolice(0,$a_rechercher,$agence);
                    break;
                case "2":
                    $list = $this->getDoctrine()
                                ->getRepository('AppBundle:Client')
                                ->listByNom(0,$a_rechercher,$agence);
                    break;
            }
        } else{
            $list = $this->getDoctrine()
                                ->getRepository('AppBundle:Client')
                                ->liste(0,$agence);
        }


        return new JsonResponse($list);
    }

    public function getClientDetailsAction($id)
    {
        $client = $this->getDoctrine()
                    ->getRepository('AppBundle:Client')
                    ->details($id);
                    
        return new JsonResponse($client);
    }

    public function checkExist(Request $request)
    {
        $statut = $request->request->get('statut');
        $agence = $request->request->get('agence');
        
        if ($statut == 1) {
            $clm_nom_societe = $request->request->get('clm_nom_societe');

            $morale = $this->getDoctrine()
                    ->getRepository('AppBundle:Client')
                    ->checkMorale(array(
                        'nomSociete' => strtoupper($clm_nom_societe),
                        'agence' => $agence
                    ));

            return $morale;
        } else {
            $clp_nom = $request->request->get('clp_nom');

            $physique = $this->getDoctrine()
                    ->getRepository('AppBundle:Client')
                    ->checkPhysique(array(
                        'nom' => $clp_nom,
                        'agence' => $agence
                    ));

            return $physique;

        }

        return false;
        
    }

}