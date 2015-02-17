<?php
namespace NosBelIdees\CMSBundle\Document;
use NosBelIdees\CMSBundle\Document\Page as Page;
use NosBelIdees\CMSBundle\Document\Block as Block;

abstract class Migration
{
    protected $container;  
    
    /** @var \NosBelIdees\CMSBundle\Document\Handler\DocumentHandler */
    protected $documentHandler;

    /** @var \Symfony\Component\Console\Output\OutputInterface */
    protected $output;

    /** @var \Symfony\Component\Console\Helper\DialogHelper */
    protected $dialog;

    protected $migrationPath;

    protected $basePath;
    
    protected $manager;    

    public function __construct($container, $documentHandler, $basePath, $migrationPath, $output, $dialog)
    {
        $this->container = $container;
        $this->documentHandler = $documentHandler;
        $this->basePath = $basePath;
        $this->migrationPath = $migrationPath;
        $this->output = $output;
        $this->dialog = $dialog;
        $this->manager = $this->documentHandler->getPublicManager();

        if ($this->test()) {
            $this->PreUp();
            $this->Up();
            $this->PostUp();

            $this->IncrementMigrationVersion();
        }
    }

    protected function test()
    {
        return true;
    }

    protected function PreUp()
    {
        // do something
    }

    abstract protected function Up();

    protected function PostUp()
    {
        // do something
    }

    private function IncrementMigrationVersion()
    {
        $migrationDoc = $this->documentHandler->getPublicDocument($this->migrationPath);
        $migrationDoc->setTitle(get_class($this));
        $this->documentHandler->saveDocument($migrationDoc);
    }

    protected function log($msg, $test = null)
    {
        if (is_null($test)) {
            $this->output->writeln("  $msg");
        } elseif ($test) {
            $this->output->writeln("  $msg <info>[OK]</info>");
        } else {
            $this->output->writeln("  $msg <error>[KO]</error>");
        }
    }
    
    /**
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2015-01-22
     */      
    protected function getPageById($documentId)
    {
        return $this->documentHandler->getDraftDocument($this->basePath . $documentId);     
    } 
    
    /**
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2015-01-22
     */      
    protected function getSisterPagesById($documentId)
    {
        return $this->documentHandler->getSisterDocument($documentId);  
    }  
    
    /**
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2015-01-22
     */      
    protected function saveDocument($document)
    {
        $this->documentHandler->saveDocument($document);
    }  
    
    /**
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2015-01-22
     */      
    protected function removeDocument($document)
    {
        $this->documentHandler->removeDocument($document);
    }      
    
    /**
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2015-01-22
     */      
    protected function createPage($PageName, $documentId, $title)
    {
        // we set the id Page
        $idPage = $this->basePath . $documentId;
        //
        $this->documentHandler->createPath(dirname($idPage));
        $manager = $this->documentHandler->getPublicManager();
        // page
        if ($PageName == "InnovationsValuesPage") {
            $page = new Page\InnovationsValuesPage();
        }
        if ($PageName == "LandingPage") {
            $page = new Page\LandingPage();
        }
        $page->setId($idPage);
        $page->setTitle($title);
        // we get the parent
        $parent = $this->documentHandler->getPublicDocument(dirname($idPage));
        if ($parent) {
            $page->setPosition($parent, basename($idPage));
        }
        //
        $this->manager->persist($page);  
        
        return $page;
    } 
    
    /**
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2015-01-22
     */      
    protected function createContentBlock($pageParent, $Name, $content = "")
    {
        $wiziwig = new Block\ContentBlock();
        $wiziwig->setParentDocument($pageParent);
        $wiziwig->setName($Name);
        $wiziwig->setContent($content);
        $this->manager->persist($wiziwig);   
        
        return $wiziwig;
    }     

    /**
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2015-01-22
     */      
    protected function createPushBlock($pageParent, $Name, $content = "")
    {
        $push = new Block\PushBlock();
        $push->setParentDocument($pageParent);
        $push->setName($Name);
        $push->setContent($content);
        $this->manager->persist($push);   
        
        return $push;
    }   
    
    /**
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2015-01-22
     */    
    protected function createDossiersBloggerBlock($pageParent, $Name, $DossiersIds, $position = null)
    {
        $Blogger = new Block\DossiersBloggerBlock();
        $Blogger->setParentDocument($pageParent);
        $Blogger->setDossiersIds($DossiersIds);
        $Blogger->setName($Name);
        if (!is_null($position)) {
            $Blogger->setPosition($position);
        }
        $this->manager->persist($Blogger);       
        
        return $Blogger;
    }      
}
