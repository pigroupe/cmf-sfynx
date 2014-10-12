<?php
/**
 * This file is part of the <Translator> project.
 * 
 * @uses abstractController
 * @subpackage   Translator
 * @package    Controller
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-11-14
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\TranslatorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sfynx\AuthBundle\Controller\abstractController;
use Sfynx\ToolBundle\Exception\ControllerException;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use JMS\SecurityExtraBundle\Annotation\Secure;

use Sfynx\TranslatorBundle\Entity\Word;
use Sfynx\TranslatorBundle\Form\WordType;
use Sfynx\TranslatorBundle\Form\WordTranslateType;
use Sfynx\TranslatorBundle\Entity\Translation\WordTranslation;

/**
 * Word controller.
 *
 * @uses abstractController
 * @subpackage   Translator
 * @package    Controller
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class WordController extends abstractController
{
    protected $_entityName = "SfynxTranslatorBundle:Word";

    /**
     * Enabled Word entities.
     *
     * @Route("/bootstrap/translator/enabled", name="bootstrap_translator_enabledentity_ajax")
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     *     
     * @access  public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function enabledajaxAction()
    {
        return parent::enabledajaxAction();
    }

    /**
     * Disable Word entities.
     * 
     * @Route("/bootstrap/translator/disable", name="bootstrap_translator_disablentity_ajax")
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     *     
     * @access  public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function disableajaxAction()
    {
        return parent::disableajaxAction();
    } 

    /**
     * Change the position of a Word entity.
     *
     * @Route("/bootstrap/translator/position", name="bootstrap_translator_position_ajax")
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     *     
     * @access  public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function positionajaxAction()
    {
        return parent::positionajaxAction();
    }   

    /**
     * Delete a Word entity.
     *
     * @Route("/bootstrap/translator/delete", name="bootstrap_translator_deletentity_ajax")
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     *     
     * @access  public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function deleteajaxAction()
    {
        return parent::deletajaxAction();
    }   
    /**
     * Lists all Word entities.
     *
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>   
     */
    public function indexAction()
    {
        $em         = $this->getDoctrine()->getManager();
        $locale     = $this->container->get('request')->getLocale();
        
        $this->checkCsrf('listword'); // name of the generated token, must be equal to the one from Twig

        $this->get('sfynx.annotation.subscriber.encrypters')->_load_enabled = true;
        $entities   = $em->getRepository("SfynxTranslatorBundle:Word")->setContainer($this->container)->findAllByEntity($locale, 'object');        
        
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)     $template = "index.html.twig"; else $template = "index.html.twig";

        return $this->render("SfynxTranslatorBundle:Word:$template", array(
            'entities' => $entities,
            'NoLayout'    => $NoLayout,
        ));
    }

    /**
     * Finds and displays a Word entity.
     *
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>    
     */
    public function showAction($id)
    {
        $em     = $this->getDoctrine()->getManager();
        $locale    = $this->container->get('request')->getLocale();

        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)     $template = "show.html.twig"; else $template = "show.html.twig";
        
        $this->get('sfynx.annotation.subscriber.encrypters')->_load_enabled = true;
        $entity = $em->getRepository("SfynxTranslatorBundle:Word")->findOneByEntity($locale, $id, 'object');
        
        if (!$entity) {
            throw ControllerException::NotFoundEntity('Word');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render("SfynxTranslatorBundle:Word:$template", array(
            'entity'      => $entity,
            'NoLayout'      => $NoLayout,
            'delete_form' => $deleteForm->createView(),

        ));
    }

    /**
     * Displays a form to create a new Word entity.
     *
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>    
     */
    public function newAction()
    {
        $em     = $this->getDoctrine()->getManager();
        $entity = new Word();
        $locale    = $this->container->get('request')->getLocale();
        $form   = $this->createForm(new WordType($em, $locale, $this->container), $entity, array('show_legend' => false));
        
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)    $template = "new.html.twig";  else     $template = "new.html.twig";       

        return $this->render("SfynxTranslatorBundle:Word:$template", array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'NoLayout'  => $NoLayout,
        ));
    }

    /**
     * Creates a new Word entity.
     *
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>     
     */
    public function createAction()
    {
        $em     = $this->getDoctrine()->getManager();
        $locale    = $this->container->get('request')->getLocale();
        
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)    $template = "new.html.twig";  else     $template = "new.html.twig";        

        $this->get('sfynx.annotation.subscriber.encrypters')->_load_enabled = true;
        $entity  = new Word();
        $request = $this->getRequest();
        $form    = $this->createForm(new WordType($em, $locale, $this->container), $entity, array('show_legend' => false));
        $form->bind($request);

        if ($form->isValid()) {
            $entity->setTranslatableLocale($locale);
            $em->persist($entity);
            $em->flush();
            
            return $this->redirect($this->generateUrl('admin_word_edit', array('id' => $entity->getId(), 'NoLayout' => $NoLayout)));
        }

        return $this->render("SfynxTranslatorBundle:Word:$template", array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'NoLayout'  => $NoLayout,
        ));
    }

    /**
     * Displays a form to edit an existing Word entity.
     *
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>    
     */
    public function editAction($id)
    {
        $em     = $this->getDoctrine()->getManager();
        $locale = $this->container->get('request')->getLocale();
        
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)    $template = "edit.html.twig";  else    $template = "edit.html.twig";        

        $this->get('sfynx.annotation.subscriber.encrypters')->_load_enabled = true;
        $entity = $em->getRepository("SfynxTranslatorBundle:Word")->findOneByEntity($locale, $id, 'object');
        if (!$entity) {
            $entity = $em->getRepository("SfynxTranslatorBundle:Word")->find($id);
            $entity->addTranslation(new WordTranslation($locale));            
        }
        
        $editForm   = $this->createForm(new WordType($em, $locale, $this->container), $entity, array('show_legend' => false));
        $deleteForm = $this->createDeleteForm($id);
        
        $this->container->get("sfynx.translator.wordsloader")->wordsTranslation();

        return $this->render("SfynxTranslatorBundle:Word:$template", array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'NoLayout'       => $NoLayout,
        ));
    }

    /**
     * Edits an existing Word entity.
     *
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>   
     */
    public function updateAction($id)
    {
        $em     = $this->getDoctrine()->getManager();
        $locale    = $this->container->get('request')->getLocale();
        $entity = $em->getRepository("SfynxTranslatorBundle:Word")->findOneByEntity($locale, $id, "object"); 
        
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)    $template = "edit.html.twig";  else    $template = "edit.html.twig";        

        $this->get('sfynx.annotation.subscriber.encrypters')->_update_enabled = true;
        if (!$entity) {
            $entity = $em->getRepository("SfynxTranslatorBundle:Word")->find($id);
        }
        $editForm   = $this->createForm(new WordType($em, $locale, $this->container), $entity, array('show_legend' => false));
        $deleteForm = $this->createDeleteForm($id);
        $editForm->bind($this->getRequest(), $entity);
        
        if ($editForm->isValid()) {
            $entity->setTranslatableLocale($locale);
            $em->persist($entity);
            $em->flush();
            
            return $this->redirect($this->generateUrl('admin_word_edit', array('id' => $id, 'NoLayout' => $NoLayout)));
        }

        return $this->render("SfynxTranslatorBundle:Word:$template", array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'NoLayout'       => $NoLayout,
        ));
    }
    
    /**
     * Lists all Word entities.
     *
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>   
     */
    public function translateAction($id)
    {
        $em             = $this->getDoctrine()->getManager();
        $NoLayout       = $this->container->get('request')->query->get('NoLayout');
        
        $locale         = $this->container->get('request')->getLocale();
        $locales        = $this->container->get('sfynx.auth.locale_manager')->getAllLocales(true);
        
        $this->get('sfynx.annotation.subscriber.encrypters')->_load_enabled = true;
        $entity         = $em->getRepository("SfynxTranslatorBundle:Word")->findOneByEntity($locale, $id, 'object');

        return $this->render("SfynxTranslatorBundle:Word:translate.html.twig", array(
            'entity'    => $entity,
            'langs'     => $locales,
            'NoLayout'  => $NoLayout,
        ));
    }
    
    /**
     * Displays a form to edit an existing Word entity.
     *
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>    
     */
    public function editTranslateAction($id, $lang)
    {
        $em         = $this->getDoctrine()->getManager();
        $locale        = $this->container->get('request')->getLocale();
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        $request    = $this->getRequest();
        
        $this->get('sfynx.annotation.subscriber.encrypters')->_load_enabled = true;
        $entity     = $em->getRepository("SfynxTranslatorBundle:Word")->findOneByEntity($lang, $id, 'object');
        if ($lang != $locale) {
            $entity->setTranslatableLocale($lang);
            $em->refresh($entity);
        }
        if (!$entity) {
            $entity = $em->getRepository("SfynxTranslatorBundle:Word")->find($id);
            $entity->addTranslation(new WordTranslation($lang));            
        }
        $editForm   = $this->createForm(new WordTranslateType($em, $lang, $this->container), $entity, array('show_legend' => false));
        
        //exit;

        return $this->render("SfynxTranslatorBundle:Word:editTranslate.html.twig", array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'NoLayout'       => $NoLayout,         
            'lang'             => $lang,         
        ));
    }

    /**
     * Edits an existing Word entity.
     *
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>   
     */
    public function updateTranslateAction($id, $lang)
    {
        $em         = $this->getDoctrine()->getManager();
        $entity     = $em->getRepository("SfynxTranslatorBundle:Word")->findOneByEntity($lang, $id, "object"); 
        $NoLayout     = $this->container->get('request')->query->get('NoLayout');

        $this->get('sfynx.annotation.subscriber.encrypters')->_update_enabled = true;
        if (!$entity) {
            $entity = $em->getRepository("SfynxTranslatorBundle:Word")->find($id);
        }
        $editForm   = $this->createForm(new WordTranslateType($em, $lang, $this->container), $entity, array('show_legend' => false));
        $deleteForm = $this->createDeleteForm($id);
        $editForm->bind($this->getRequest(), $entity);
        
        if ($editForm->isValid()) {
            $entity->setTranslatableLocale($lang);
            $em->persist($entity);
            $em->flush();
                        
            $this->container->get("sfynx.translator.wordsloader")->wordsTranslation();
            
            return $this->redirect($this->generateUrl('admin_word_edit_translate', array('id' => $id, 'lang' => $lang, 'NoLayout' => $NoLayout)));
        }

        return $this->render("SfynxTranslatorBundle:Word:editTranslate.html.twig", array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'NoLayout'       => $NoLayout,
            'lang'             => $lang,              
        ));
    }

    /**
     * Deletes a Word entity.
     *
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *     
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>     
     */
    public function deleteAction($id)
    {
        $em       = $this->getDoctrine()->getManager();
        $locale   = $this->container->get('request')->getLocale();
        $NoLayout = $this->container->get('request')->query->get('NoLayout'); 
        //       
        $form     = $this->createDeleteForm($id);
        $request  = $this->getRequest();
        $form->bind($request);
        if ($form->isValid()) {
            $entity = $em->getRepository("SfynxTranslatorBundle:Word")->findOneByEntity($locale, $id, 'object');
            if (!$entity) {
                throw ControllerException::NotFoundEntity('Word');
            }
            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_word', array('NoLayout' => $NoLayout)));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    /**
     * Sync word entity from file.
     * 
     * @Route("/bootstrap/translator/sync", name="bootstrap_translator_sync")
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function syncAction()
    {
        $em         = $this->getDoctrine()->getManager();
        $locale     = $this->container->get('request')->getLocale();
        if(!$locale){
            $locale = $this->container->getParameter("locale");
        }
        // we get all locales vallues.        
        $languages  = $this->container->get('sfynx.auth.locale_manager')->getAllLocales();
        // We recovery files.
        foreach($languages as $key => $language){
            $basePath = $this->container->get('kernel')->getRootDir() . '/../' . $this->container->parameters["translations"]["file"];
            $filepath_messages = $basePath."messages.".$language.".yml";
            $filepath_category = $basePath."category.".$language.".yml";
            //Si le fichier existe, on part de lui
            if(file_exists($filepath_messages) && file_exists($filepath_category) ){
                $yaml = new \Symfony\Component\Yaml\Parser();
                $value_messages = $yaml->parse(file_get_contents($filepath_messages));
                $value_category = $yaml->parse(file_get_contents($filepath_category));
                ${"messages_".$langue} = $value_messages;
                ${"category_".$langue} = $value_category;
            } //Sinon on le recré depuis la bdd
        }
        // We empty the table.
        $em->getConnection()->executeQuery('SET FOREIGN_KEY_CHECKS = 0;');
        $truncateSql = $em->getConnection()->getDatabasePlatform()->getTruncateTableSQL('pi_word');
        $em->getConnection()->executeUpdate($truncateSql);
        $truncateSqlTranslation = $em->getConnection()->getDatabasePlatform()->getTruncateTableSQL('pi_word_translations');
        $em->getConnection()->executeUpdate($truncateSqlTranslation);        
        // We refilled the table from the files.
        foreach(${"messages_".$locale} as $cle=>$valeur){
            $entity  = new Word();
            foreach($languages as $key => $langue){
                $translate = new WordTranslation();
                $translate->setContent(${"messages_".$langue}[$cle]);
                $translate->setField('label');
                $translate->setCategory(${"category_".$langue}[$cle]);
                $translate->setLocale($langue);
                $entity->addTranslation($translate);
            }
            $entity->setkeyword($cle);
            $entity->setLabel($valeur);
            $entity->setCategory(${"category_".$locale}[$cle]);
            $this->get('sfynx.annotation.subscriber.encrypters')->_load_enabled = true;
            $em->persist($entity);
        }
        $em->flush();
        $this->container->get('request')->getSession()->getFlashBag()->set('success', 'pi.session.flash.right.update');
        
        return $this->redirect($this->generateUrl('admin_word'));
    }    
  
}
