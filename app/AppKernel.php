<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new AppBundle\AppBundle(),
            new ClientBundle\ClientBundle(),
            new MenuBundle\MenuBundle(),
            new DashboardBundle\DashboardBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new UserBundle\UserBundle(),
            new ParametreBundle\ParametreBundle(),
            new AgenceBundle\AgenceBundle(),
            new Liuggio\ExcelBundle\LiuggioExcelBundle(),
            new PermissionBundle\PermissionBundle(),
            new PermissionUserBundle\PermissionUserBundle(),
            new FactureBundle\FactureBundle(),
            new PdfBundle\PdfBundle(),
            new ProduitBundle\ProduitBundle(),
            new CaisseBundle\CaisseBundle(),
            new ServiceBundle\ServiceBundle(),
            new ComptabiliteBundle\ComptabiliteBundle(),
            new CommercialBundle\CommercialBundle(),
            new SitewebBundle\SitewebBundle(),
            new Api\SitewebBundle\ApiSitewebBundle(),
            new Nelmio\CorsBundle\NelmioCorsBundle(),
            new Api\ConfigBundle\ApiConfigBundle(),
            new BonCommandeBundle\BonCommandeBundle(),
            new MotifDechargeBundle\MotifDechargeBundle(),
            new BonLivraisonBundle\BonLivraisonBundle(),
            new CreditBundle\CreditBundle(),
            new RecetteBundle\RecetteBundle(),
            new RestaurantBundle\RestaurantBundle(),
            new HebergementBundle\HebergementBundle(),
            new StockInterneBundle\StockInterneBundle(),
            new StockInterneGeneralBundle\StockInterneGeneralBundle(),
            new TacheBundle\TacheBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
