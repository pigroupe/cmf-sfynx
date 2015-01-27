<?php
/**
 * This file is part of the <Tool> project.
 * 
 * @subpackage   Script
 * @package    Extension
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-01-11
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\ToolBundle\Twig\Node;

/**
 * Javascript Node.
 *
 * @subpackage   Script
 * @package    Extension
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class JavascriptNode extends \Twig_Node
{
    /**
     * @var string $extensionName
     */
    protected $extensionName;
        
    /**
     * @param     \Twig_NodeInterface     $value
     * @param     integer                             $lineno
     * @param     string                                 $tag (optional)
     * @return     void
     */
    public function __construct($extensionName, \Twig_NodeInterface $value, $lineno, $tag = null)
    {
        $this->extensionName = $extensionName;
        
        parent::__construct(array('value' => $value), array(), $lineno, $tag);
    }

    /**
     * @param \Twig_Compiler $compiler
     * @return void
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this);

        $compiler
            ->write("echo \$this->env->getExtension('".$this->extensionName."')->addJsFile(")
            ->subcompile($this->getNode('value'))
            ->raw(");\n");
    }
}