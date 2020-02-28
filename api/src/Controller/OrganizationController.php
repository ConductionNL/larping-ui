<?php
// src/Controller/DefaultController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use App\Service\CommonGroundService;

/**
 * Class OrganisationController
 * @package App\Controller
 * @Route("/organizations")
 */
class OrganizationController extends AbstractController
{
	/**
	 * @Route("/")
	 * @Template
	 */
	public function indexAction(Request $request, CommonGroundService $commonGroundService)
	{
		$groups = $request->request->get('groups');
		$query = ["groups"=>$groups];
		
		$organizations= $commonGroundService->getResourceList('https://wrc.larping.eu/organizations', $query)['hydra:member'];
		
		return ["organizations"=>$organizations];
	}
	
	/**
	 * @Route("/{id}")
	 * @Template
	 */
	public function viewAction(CommonGroundService $commonGroundService, $id)
	{
		$organization = $commonGroundService->getResource('https://wrc.larping.eu/organizations/'.$id);
		
		return ["organization"=>$organization];
	}
}
