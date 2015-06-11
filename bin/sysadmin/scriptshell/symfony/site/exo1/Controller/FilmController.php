<?php

namespace MyApp\SiteBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use MyApp\SiteBundle\Entity\Film;
use MyApp\SiteBundle\Form\FilmForm;

class FilmController extends ContainerAware
{
    public function listerAction()
    {        
        $em = $this->container->get('doctrine')->getEntityManager();
        $films = $em->getRepository('MyAppSiteBundle:Film')->findAll();

        return $this->container->get('templating')->renderResponse('MyAppSiteBundle:Film:lister.html.twig', array(
            'films' => $films
        ));
    }
    
    public function topAction($max = 5)
    {
        $em = $this->container->get('doctrine')->getEntityManager();

        $qb = $em->createQueryBuilder();
        $qb->select('f')
          ->from('MyAppSiteBundle:Film', 'f')
          ->orderBy('f.titre', 'ASC')
          ->setMaxResults($max);
        
        $query = $qb->getQuery();
        $films = $query->getResult();

        return $this->container->get('templating')->renderResponse('MyAppSiteBundle:Film:liste.html.twig', array(
            'films' => $films,
        ));
    }

    public function voirAction($id = null)
    {
        $em = $this->container->get('doctrine')->getEntityManager();

        if (isset($id)) 
        {
            $film = $em->find('MyAppSiteBundle:Film', $id);
        }            

        return $this->container->get('templating')->renderResponse(
        'MyAppSiteBundle:Film:voir.html.twig',
            array(
            'film' => $film,            
            )
        );
    }
    
    public function editerAction($id = null)
    {
        $message='';
        $em = $this->container->get('doctrine')->getEntityManager();

        if (isset($id)) 
        {
            $film = $em->find('MyAppSiteBundle:Film', $id);

            if (!$film)
            {
                $message='Aucun film trouvé';
            }
        }
        else 
        {
            $film = new Film();
        }

        $form = $this->container->get('form.factory')->create(new FilmForm(), $film);

        $request = $this->container->get('request');

        if ($request->getMethod() == 'POST') 
        {
            $form->bindRequest($request);

            if ($form->isValid()) 
            {
                $em->persist($film);
                $em->flush();
                if (isset($id)) 
                {
                    $message = $this->container->get('translator')->trans('film.modifier.succes',array(
                                '%titre%' => $film->getTitre()
                                ));
                }
                else 
                {
                    $message = $this->container->get('translator')->trans('film.ajouter.succes',array(
                                '%titre%' => $film->getTitre()
                                ));
                }
            }
        }

        return $this->container->get('templating')->renderResponse(
        'MyAppSiteBundle:Film:editer.html.twig',
            array(
            'form' => $form->createView(),
            'message' => $message,
            'film' => $film
            )
        );
    }

    public function supprimerAction($id)
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $film = $em->find('MyAppSiteBundle:Film', $id);

        if (!$film) 
        {
            throw new NotFoundHttpException("Film non trouvé");
        }

        $em->remove($film);
        $em->flush();        


        return new RedirectResponse($this->container->get('router')->generate('myapp_film_lister'));
    }
}