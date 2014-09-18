<?php

namespace App\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class MediaLibraryController extends Controller
{
    /**
     * @Route("/media-library", name="admin_media_library")
     * @Template()
     */
    public function indexAction()
    {
        $parameters = $this->container->getParameter('fm_elfinder');
        $locale = $parameters['locale'] ?: $this->getRequest()->getLocale();
        $fullscreen = $parameters['fullscreen'];
        $includeAssets = $parameters['include_assets'];
        $compression = $parameters['compression'];
        $prefix = ($compression ? '/compressed' : '');
                return $this->render('AppAdminBundle:MediaLibrary:index.html.twig', array(
                    'locale' => $locale,
                    'fullscreen' => $fullscreen,
                    'includeAssets' => $includeAssets,
                ));
        
    }
    

    
    
    
}
