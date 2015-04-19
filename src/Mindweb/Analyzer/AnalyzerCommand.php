<?php
namespace Mindweb\Analyzer;

use Pimple\Container;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Mindweb\Modifier;
use Mindweb\Forwarder;
use Mindweb\Collector;

class AnalyzerCommand extends Command
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        parent::__construct();

        $this->container = $container;
    }

    protected function configure()
    {
        $this->setName('mindweb:analyze');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $modifiers = new Modifier\Collection();
        $forwarders = new Forwarder\Collection();

        foreach ($this->container['modifiers'] as $modifier => $options) {
            $modifiers->add(new $modifier($options));

            if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
                $output->writeln(sprintf('<info>Register %s modifier.</info>', $modifier));
            }
        }

        foreach ($this->container['forwarders'] as $forwarder => $options) {
            $forwarders->add(new $forwarder($options));

            if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
                $output->writeln(sprintf('<info>Register %s forwarder.</info>', $forwarder));
            }
        }

        foreach ($this->container['collectors'] as $collectorClassName => $options) {
            /**
             * @var Collector\Collector $collector
             */
            $collector = new $collectorClassName($options);

            if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
                $output->writeln(sprintf('<info>Starting %s collector...</info>', $collectorClassName));
            }

            $collector->run(
                $modifiers,
                $forwarders
            );
        }

        $output->writeln('<error>Exiting now...</error>');
    }
} 