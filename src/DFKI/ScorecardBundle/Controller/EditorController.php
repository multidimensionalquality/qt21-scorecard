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
namespace DFKI\ScorecardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use DFKI\ScorecardBundle\Entity\Project;
use DFKI\ScorecardBundle\Entity\Segment;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class EditorController extends Controller {
	
	/**
	 * display scorecard editor
	 *
	 * @param unknown $projectId        	
	 */
	public function editorAction(Request $req, $projectId) {
		$project = $this->getDoctrine ()->getRepository ( "DFKIScorecardBundle:Project" )->findOneById ( $projectId );
		
		
		if (! is_object ( $project )) {
			throw new BadRequestHttpException ();
		}
		
		if (false === $this->get ( 'security.context' )->isGranted ( 'view', $project )) {
			throw new AccessDeniedException ( 'Unauthorised access!' );
		}
		
		$session = $req->getSession ();
		$msg = "";
		$projectService = $this->get ( "projectService" );
		$issueDefinitions = $projectService->getProjectIssues ( $project );
		if (empty($issueDefinitions)) {
			$msg .= "The issues associated with this project could not be found in the currently defined error typology. Reloading the metrics file may be necessary.";
		}

		$editorService = $this->get ( "editorService" );
		$issueGrid = $editorService->createIssueGrid($issueDefinitions);

		$issueReports = $editorService->getIssueReports ( $project );
		if (empty($issueDefinitions) && !empty($issueReports)) {
			$msg .= "<br><br>Since issue reports have already been made on this project using the missing issues, replacing the metrics file is impossible and
					the project is irreparable.";
		}

		if (strlen($msg) > 0) {
			$session->getFlashBag ()->add ( 'error', $msg);
		}

		return $this->render ( 'DFKIScorecardBundle:Editor:editor.html.twig', array (
				"project" => $project,
				"issuesGrid" => $issueGrid,
				"issues" => $issueDefinitions,
				"issueReports" => $issueReports
		) );
	}
	
	/**
	 * mark project as finished and open editor
	 *
	 * @param unknown $projectId        	
	 */
	public function markAsFinishedAction(Request $req, $projectId) {
		$em = $this->getDoctrine ()->getEntityManager ();
		$project = $em->getRepository ( "DFKIScorecardBundle:Project" )->findOneById ( $projectId );
		
		if (! is_object ( $project )) {
			throw new BadRequestHttpException ();
		}
		
		if (false === $this->get ( 'security.context' )->isGranted ( 'view', $project )) {
			throw new AccessDeniedException ( 'Unauthorised access!' );
		}
		
		$project->setFinished ( ! $project->getFinished () );
		$em->persist ( $project );
		$em->flush ();
		
		$session = $req->getSession ();
		$msg = "Your changes have been saved";
		$session->getFlashBag ()->add ( 'notice', $msg );
		
		return $this->redirect ( $this->generateUrl ( 'sc_editor', array (
				"projectId" => $project->getId () 
		) ), 301 );
	}
        
        /**
         * generate a report
         * 
         * @param string $reportData
         */
        public function reportAction(Request $req) {
            
                $fileLocator = $this->get('file_locator');
                $path = $fileLocator->locate('@DFKIScorecardBundle/Resources/public/react_apps/ReportGenerator/asset-manifest.json');  
                $json = file_get_contents($path);
                $manifest = json_decode($json, true);
                
                $paths = array(
                    "main_js" => $manifest['main.js'],
                    "main_css" => $manifest['main.css']
                );
            
                return $this->render ( 'DFKIScorecardBundle:Editor:generate_report.html.twig', array(
                                "data" => $req->request->get('data'),
                                "asset_map" => $paths
                ));
        }
}
