<?php

namespace AppBundle\Repository;

// use AppBundle\Entity\Menu;
// use AppBundle\Entity\MenuParRole;
use AppBundle\Entity\MenuUtilisateur;
use AppBundle\Entity\User;

use Doctrine\ORM\EntityRepository;

class MenuUtilisateurRepository extends EntityRepository
{
    /**
     *  Liste des menus complets d'un utilisateur
     *  Sans tenir compte des hierachies des menus
     *  Si vide on retourne les menus du rôle
     *
     * @param User $user
     * @return array
     */
    public function getMenuUtilisateur(User $user)
    {
        $menus = $this->getEntityManager()
            ->getRepository('AppBundle:MenuUtilisateur')
            ->createQueryBuilder('menu_utilisateur')
            ->select('menu_utilisateur')
            ->innerJoin('menu_utilisateur.menu', 'menu')
            ->addSelect('menu')
            ->innerJoin('menu_utilisateur.user', 'user')
            ->addSelect('user')
            ->where('user = :user')
            ->setParameters(array(
                'user' => $user,
            ))
            ->orderBy('menu.rang', 'ASC')
            ->getQuery()
            ->getResult();

        if (count($menus) == 0) {
            $userAgence  = $this->getEntityManager()
                                ->getRepository('AppBundle:UserAgence')
                                ->findOneBy(array(
                                    'user' => $user
                                ));

            $agence = $userAgence->getAgence();
            
            $menus = $this->getEntityManager()
                          ->getRepository('AppBundle:Menu')
                          ->getMenuParAgence($agence);
        }

        return $menus;
    }

    public function getMenuParentUtilisateur(User $user)
    {
        $menus = $this->getEntityManager()
            ->getRepository('AppBundle:MenuUtilisateur')
            ->createQueryBuilder('menu_utilisateur')
            ->select('menu_utilisateur')
            ->innerJoin('menu_utilisateur.menu', 'menu')
            ->addSelect('menu')
            ->innerJoin('menu_utilisateur.user', 'user')
            ->addSelect('user')
            ->where('user = :user')
            ->andWhere('menu.menu IS NULL')
            ->setParameters(array(
                'user' => $user,
            ))
            ->orderBy('menu.rang', 'ASC')
            ->getQuery()
            ->getResult();
        // if (count($menus) == 0) {
        //     $menus = $this->getEntityManager()
        //         ->getRepository('AppBundle:MenuParRole')
        //         ->createQueryBuilder('menuParRole')
        //         ->select('menuParRole')
        //         ->innerJoin('menuParRole.accesUtilisateur', 'accesUtilisateur')
        //         ->addSelect('accesUtilisateur')
        //         ->where('accesUtilisateur = :acces')
        //         ->innerJoin('menuParRole.menu', 'menu')
        //         ->addSelect('menu')
        //         ->andWhere('menu.menu IS NULL')
        //         ->setParameters(array(
        //             'acces' => $user->getAccesUtilisateur(),
        //         ))
        //         ->orderBy('menu.rang', 'ASC')
        //         ->getQuery()
        //         ->getResult();
        // }

        $parents = [];
        /** @var MenuUtilisateur|MenuParRole $menu */
        foreach ($menus as $menu)
        {
            $parents[] = $menu->getMenu();
        }
        return $parents;
    }

    /**
     *  Liste des menus d'un user avec
     *  hierarchies
     *
     * @param User $user
     * @param $menus_id
     * @return array
     */
    public function getMenuUtilisateurEx(User $user, &$menus_id)
    {

        $menus = $this->getEntityManager()
            ->getRepository('AppBundle:MenuUtilisateur')
            ->getMenuUtilisateur($user);
        $menus_id = [];
        /** @var MenuParRole|MenuUtilisateur $menu */
        foreach ($menus as $menu) {
            $menus_id[] = $menu->getMenu()->getId();
        }

        $parents = $this->getEntityManager()
            ->getRepository('AppBundle:MenuUtilisateur')
            ->createQueryBuilder('menu_utilisateur')
            ->select('menu_utilisateur')
            ->innerJoin('menu_utilisateur.menu', 'menu')
            ->addSelect('menu')
            ->innerJoin('menu_utilisateur.user', 'user')
            ->addSelect('user')
            ->where('user = :user')
            ->andWhere('menu.menu IS NULL')
            ->setParameters(array(
                'user' => $user,
            ))
            ->orderBy('menu.rang', 'ASC')
            ->getQuery()
            ->getResult();
        // if (count($parents) == 0) {
        //     $parents = $this->getEntityManager()
        //         ->getRepository('AppBundle:MenuParRole')
        //         ->createQueryBuilder('menuParRole')
        //         ->select('menuParRole')
        //         ->innerJoin('menuParRole.accesUtilisateur', 'accesUtilisateur')
        //         ->addSelect('accesUtilisateur')
        //         ->where('accesUtilisateur = :acces')
        //         ->innerJoin('menuParRole.menu', 'menu')
        //         ->addSelect('menu')
        //         ->andWhere('menu.menu IS NULL')
        //         ->setParameters(array(
        //             'acces' => $user->getAccesUtilisateur(),
        //         ))
        //         ->orderBy('menu.rang', 'ASC')
        //         ->getQuery()
        //         ->getResult();
        // }

        $liste_menus = [];
        if (count($parents) == 0) {
            return [];
        } else {

            /** @var MenuParRole|MenuUtilisateur $parent */
            foreach ($parents as &$parent) {
                $level1 = $parent->getMenu();
                $liste_menus[] = $level1;

                $childs = $this->getEntityManager()
                    ->getRepository('AppBundle:Menu')
                    ->getMenuChild($level1, $user);
                if (count($childs) > 0) {
                    $level1->setChild($childs);
                    /** @var Menu $child */
                    foreach ($childs as &$child) {
                        $childs_2 = $this->getEntityManager()
                            ->getRepository('AppBundle:Menu')
                            ->getMenuChild($child, $user);

                        if (count($childs_2) > 0) {
                            $child->setChild($childs_2);
                            /** @var Menu $child_2 */
                            foreach ($childs_2 as &$child_2) {
                                $childs_3 = $this->getEntityManager()
                                    ->getRepository('AppBundle:Menu')
                                    ->getMenuChild($child_2, $user);
                                if (count($childs_3) > 0) {
                                    $child_2->setChild($childs_3);
                                    /** @var Menu $child_3 */
                                    foreach ($childs_3 as &$child_3) {
                                        $childs_4 = $this->getEntityManager()
                                            ->getRepository('AppBundle:Menu')
                                            ->getMenuChild($child_3, $user);
                                        if (count($childs_4) > 0) {
                                            $child_3->setChild($childs_3);
                                            /** @var Menu $child_4 */
                                            foreach ($childs_4 as &$child_4) {

                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $liste_menus;
    }

    /**
     * Supprimer les menus d'un user
     *
     * @param User $user
     */
    public function removeMenuUtilisateur(User $user)
    {
        $menus = $menus = $this->getEntityManager()
            ->getRepository('AppBundle:MenuUtilisateur')
            ->createQueryBuilder('menu_utilisateur')
            ->select('menu_utilisateur')
            ->innerJoin('menu_utilisateur.menu', 'menu')
            ->addSelect('menu')
            ->innerJoin('menu_utilisateur.user', 'user')
            ->addSelect('user')
            ->where('user = :user')
            ->setParameters(array(
                'user' => $user,
            ))
            ->getQuery()
            ->getResult();

        if (count($menus) > 0) {
            $em = $this->getEntityManager();
            foreach ($menus as $menu) {
                $em->remove($menu);
            }
            $em->flush();
        }
    }
}