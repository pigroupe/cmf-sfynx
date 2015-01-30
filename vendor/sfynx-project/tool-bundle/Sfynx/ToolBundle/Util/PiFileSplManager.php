<?php
/**
 * This file is part of the <Tool> project.
 *
 * @subpackage Tool
 * @package    Util
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2015-01-18
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\ToolBundle\Util;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use \SplFileObject;

/**
 * Description of the file manager
 *
 * <code>
 *     $fsm = $this->getContainer()->get('sfynx.tool.file_sqp_manager');
 *     if ($file = $fsm->getFile($this->filePath)) {
 *          if ($fsm->mustBeSplit($file, $this->maxLines)) {
 *              $files = $fsm->splitFile($file, $this->maxLines);
 *              $filesCount = $files->count();
 *              foreach ($files as $file) {
 *                  ...
 *              }
 *          } else {
 *              $parsed = $fsm->parseCvs($file, 5);
 *              foreach ($parsed as $data) {
 * 
 *              }
 *          }
 *     } 
 * </code>
 * 
 * @subpackage Tool
 * @package    Util
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiFileSplManager
{
    /**
     * @var string $cacheDir
     */
    private $cacheDir;

    /**
     * @var int $tmpFileNameNum
     */    
    private $tmpFileNameNum = 0;
    
    /**
     * @var string $tmpDir
     */    
    public $tmpDir;

    /** 
     * @var \Symfony\Component\HttpKernel\Log\LoggerInterface 
     */
    private $logger;

    /**
     * Constructor.
     *
     * @param string $cacheDir
     * @param Object $logger
     * 
     * @return void
     */    
    public function __construct($cacheDir, $logger)
    {
        $this->cacheDir = $cacheDir;
        $this->logger   = $logger;
    }

    /**
     * @param string $suffix
     * 
     * @return string
     */     
    public function createTmpDir($suffix = 'createTmpDir')
    {
        $this->tmpDir = $this->cacheDir . '/' . microtime(true) . '_' . $suffix;

        $this->removeTmpDir();

        $fs = new Filesystem();
        $fs->mkdir($this->tmpDir);

        return $this->tmpDir;
    }

    /**
     * @return void
     */    
    public function removeTmpDir()
    {
        $fs = new Filesystem();
        if ($fs->exists($this->tmpDir)) {
            $fs->remove($this->tmpDir);
        }
    }

    /**
     * @param string $realPath
     * 
     * @return null|SplFileInfo
     */
    public function getFile($realPath)
    {
        $fs = new Filesystem();
        if ($fs->exists($realPath)) {
            return new SplFileInfo($realPath, $realPath, $realPath);
        }

        return null;
    }

    /**
     * @param SplFileInfo $file
     * @param integer     $maxLine
     * 
     * @return bool
     */
    public function mustBeSplit(SplFileInfo $file, $maxLine)
    {
        /** @var $splFileObject \SplFileObject */
        $splFileObject = $file->openFile();
        $splFileObject->seek($maxLine);
        if ($splFileObject->current()) {
            return true;
        }

        return false;
    }

    /**
     * @param SplFileInfo $file
     * @param integer     $max_line
     * 
     * @return Finder
     */
    public function splitFile(SplFileInfo $file, $max_line)
    {
        $this->createTmpDir();
        $fileTmp = $this->createTmpFile();
        $line = 1;
        $f = $file->openFile();
        while (!$f->eof()) {
            if ($line > $max_line) {
                $line = 1;
                $fileTmp = $this->createTmpFile();
            }
            // write in file tmp
            $fileTmp->fwrite($f->current());
            // go next line
            $f->next();
            $line++;
        }

        return $this->getTmpFiles();
    }

    /**
     * @param  SplFileInfo $file
     * @param  string      $reg_exp
     * @param  Object      $output
     * 
     * @return array
     */
    public function parse(SplFileInfo $file, $reg_exp, $output)
    {
        $parsed = array();
        $f = $file->openFile();
        while (!$f->eof()) {
            $line = $f->current();
            if (preg_match($reg_exp, $line, $data)) {
                $parsed[] = $data;
            } elseif ($output) {
                $output->write($line);
            }
            $f->next();
        }

        return $parsed;
    }

    /**
     * @param  SplFileInfo $file
     * @param  string      $column
     * @param  null|Object $output
     * @param  string      $delimiter
     * 
     * @return array
     */
    public function parseCvs(SplFileInfo $file, $column, $output = null, $delimiter = '|')
    {
        $parsed = array();
        $f = $file->openFile();
        while (!$f->eof()) {
            $data = $f->fgetcsv($delimiter);

            if (count($data) == $column) {
                $parsed[] = $data;
            } elseif ($output && $data[0]) {
                $output->writeln(var_dump($data));
            }
            $f->next();
        }

        return $parsed;
    }

    /**
     * @return Finder
     */
    public function getTmpFiles()
    {
        $finder = new Finder();
        $finder->files()->name('*.tmp')->depth('== 0')->in($this->tmpDir)->sortByName();

        return $finder;
    }

    /**
     * @return SplFileObject
     */
    public function createTmpFile()
    {
        $tmpFile = $this->tmpDir . '/' . $this->tmpFileNameNum . '_file_' . microtime(true) . '.tmp';
        // create tmp file
        $fileInfo = new SplFileInfo($tmpFile, $tmpFile, $tmpFile);
        $file = $fileInfo->openFile('a');
        // increment for the next file
        $this->tmpFileNameNum++;

        return $file;
    }
}
