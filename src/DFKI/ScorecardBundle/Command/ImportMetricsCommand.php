<?php
/*
 * Copyright 2015 Deutsches Forschungszentrum f�r K�nstliche Intelligenz
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * @author Jan Nehring <jan.nehring@dfki.de>
 */
namespace DFKI\ScorecardBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportMetricsCommand extends ContainerAwareCommand {
	protected function configure() {
		$this->setName ( 'scorecard:import:issues' )->setDescription ( 'Import issue definition file from arle' )->addArgument ( 'file', InputArgument::REQUIRED, 'input file' );
	}
	protected function execute(InputInterface $input, OutputInterface $output) {
		$file = $input->getArgument ( 'file' );
		$importer = $this->getContainer ()->get ( "typologyService" );
		$text = $importer->import ( $file );
		$output->writeln ( $text );
	}
}