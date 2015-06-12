<?php

namespace MyApp\SiteBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware,
    Symfony\Component\HttpFoundation\RedirectResponse;

use MyApp\SiteBundle\Entity\Categorie,
    MyApp\SiteBundle\Entity\Acteur,
    MyApp\SiteBundle\Entity\Film
    ;


class DefaultController extends ContainerAware
{
    public function indexAction()
    {
            $em = $this->container->get('doctrine')->getEntityManager();

            $categories = $em->getRepository('MyAppSiteBundle:Categorie')->findAll();

            //$this->enregistrerDonnees();
                    
            return $this->container->get('templating')->renderResponse('MyAppSiteBundle:Default:index.html.twig',array(
                     'categories' => $categories)
            );
    }
    
    public function choisirLangueAction($langue = null)
    {
        if($langue != null)
        {
            // On enregistre la langue en session
            $this->container->get('request')->setLocale($langue);
        }

        // on tente de rediriger vers la page d’origine
        $url = $this->container->get('request')->headers->get('referer');
        if(empty($url)) {
            $url = $this->container->get('router')->generate('myapp_accueil');
        }
        return new RedirectResponse($url);
    }
    
    public function enregistrerDonnees() {
        $em = $this->container->get('doctrine')->getEntityManager();

        // Categories
        $categorie1 = new Categorie();
        $categorie1->setNom('Comédie');
        $em->persist($categorie1);

        $categorie2 = new Categorie();
        $categorie2->setNom('Science-fiction');
        $em->persist($categorie2);

        $categorie3 = new Categorie();
        $categorie3->setNom('Policier');
        $em->persist($categorie3);

        $categorie4 = new Categorie();
        $categorie4->setNom('Drame');
        $em->persist($categorie4);

        // Acteurs
        $acteur1 = new Acteur();
        $acteur1->setNom('Reno');
        $acteur1->setPrenom('Jean');
        $acteur1->setSexe('M');
        $acteur1->setDateNaissance(new \DateTime('1948/07/31'));
        $em->persist($acteur1);

        $acteur2 = new Acteur();
        $acteur2->setNom('Deneuve');
        $acteur2->setPrenom('Catherine');
        $acteur2->setSexe('F');
        $acteur2->setDateNaissance(new \DateTime('1943-10-22'));
        $em->persist($acteur2);

        $acteur3 = new Acteur();
        $acteur3->setNom('Dujardin');
        $acteur3->setPrenom('Jean');
        $acteur3->setSexe('M');
        $acteur3->setDateNaissance(new \DateTime('1972-06-19'));
        $em->persist($acteur3);

        $acteur4 = new Acteur();
        $acteur4->setNom('Portman');
        $acteur4->setPrenom('Natalie');
        $acteur4->setSexe('F');
        $acteur4->setDateNaissance(new \DateTime('1981-05-09'));
        $em->persist($acteur4);

        // Films
        $film1 = new Film();
        $film1->setTitre('Léon');
        $film1->setDescription('');
        $film1->setCategorie($categorie3);
        $film1->addActeurs($acteur1);
        $film1->addActeurs($acteur4);
        $em->persist($film1);

        $film2 = new Film();
        $film2->setTitre('Brice de Nice');
        $film2->setDescription('');
        $film2->setCategorie($categorie1);
        $film2->addActeurs($acteur3);
        $em->persist($film2);

        $film3 = new Film();
        $film3->setTitre('Le Dernier Métro');
        $film3->setDescription('');
        $film3->setCategorie($categorie4);
        $film3->addActeurs($acteur2);
        $em->persist($film3);        
        
        $em->flush();

        /*
         * $userManager = $this->container->get('fos_user.user_manager');
            $user = $userManager->createUser();
         */
        echo 'Données par défaut créés avec succès';
    }
}
