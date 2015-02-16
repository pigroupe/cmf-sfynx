<?php

namespace Sfynx\BehatBundle\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\DependencyInjection\Container;
use Tools\AcceptanceTestBundle\Behat\MinkExtension\Context\MinkContext;
use Behat\Behat\ApplicationFactory;

/**
 * Behat command with additional options (--server, --locale)
 */
class BehatCommand extends ContainerAwareCommand
{
    /**
     * Behat additional options
     * @var array $options
     */
    public static $options = array('server', 'locale');
    
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('behat:execute')
            ->setDescription('Call Behat with additional options.');
        foreach (self::$options as $option) {
            $this->addOption($option, null, InputOption::VALUE_OPTIONAL, 'Website '.$option.'.');
        }
        $this->addOption('suite', null, InputOption::VALUE_OPTIONAL, 'Specify a test suite to execute.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        MinkContext::$allowed = array(
            'servers' => $this->getContainer()->getParameter('behat.servers'),
            'locales' => $this->getContainer()->getParameter('behat.locales')
        );
        MinkContext::$options = $this->getContainer()->getParameter('behat.options');
        foreach (self::$options as $option) {
            if ($input->hasParameterOption('--'.$option)) {
                MinkContext::$options[$option] = $input->getParameterOption('--'.$option);
            }
        }
        $args = array();
        if ($input->hasParameterOption('--suite')) {
            $args['--suite'] = $input->getParameterOption('--suite');
        }
        $this->runBehatCommand($args);
    }
    
    /**
     * Run behat original command
     * @param Container $container
     */
    private function runBehatCommand(array $args = array()) {
        define('BEHAT_BIN_PATH', $this->getContainer()->getParameter('kernel.root_dir').'/../bin/behat');
        function includeIfExists($file)
        {
            if (file_exists($file)) {
                return include $file;
            }
        }
        $factory = new ApplicationFactory();
        $factory->createApplication()->run(new ArrayInput($args));
    }
}
