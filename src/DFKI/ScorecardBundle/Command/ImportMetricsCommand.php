<?php 
/**
 * @author Jan Nehring <jan.nehring@dfki.de>
 */
namespace DFKI\ScorecardBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportMetricsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('scorecard:import:issues')
            ->setDescription('Import issue definition file from arle')
            ->addArgument(
                'file',
                InputArgument::REQUIRED,
                'input file'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getArgument('file');
        $importer = $this->getContainer()->get("issueImporterService");
        $text = $importer->import($file);
        $output->writeln($text);
    }
}