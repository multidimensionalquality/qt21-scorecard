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

class CreateSuperuserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('scorecard:promote-superuser')
            ->setDescription('Create superuser')
            ->addArgument(
                'user',
                InputArgument::REQUIRED,
                'username'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('user');
        $em = $this->getContainer()->get("doctrine.orm.entity_manager");
    	$user = $em->getRepository("DFKIScorecardBundle:User")->findOneByUsername($username);
    	
    	if( !is_object($user)){
    		$output->writeln("user \"$username\" not found");
    	} else{
    		$user->setRoles(array("ROLE_SUPER_ADMIN"));
    		$em->persist($user);
    		$em->flush();
    		$output->writeln("user \"$username\" was promoted to super admin");
    	}
    }
}