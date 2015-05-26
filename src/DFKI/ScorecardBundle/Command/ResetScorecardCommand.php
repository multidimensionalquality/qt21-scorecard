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
use DFKI\ScorecardBundle\Entity\Project;

class ResetScorecardCommand extends ContainerAwareCommand {
	protected function configure() {
		$this->setName ( 'scorecard:reset' )->setDescription ( 'Reset Scorecard - delete all projects' );
	}
	protected function execute(InputInterface $input, OutputInterface $output) {
		$em = $this->getContainer ()->get ( "doctrine.orm.entity_manager" );
		$projects = $em->getRepository ( "DFKIScorecardBundle:Project" )->findAll ();
		$count = count ( $projects );
		for($i = 0; $i < sizeof ( $projects ); $i ++) {
			$em->remove ( $projects [$i] );
			$em->flush ();
		}
		$output->writeln ( "deleted $count projects" );
	}
}