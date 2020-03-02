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
		$query = ["groups"=>$groups,"type"=>"ticket"];
		
		$products = $commonGroundService->getResourceList('https://pdc.larping.eu/products')['hydra:member'];
		$organizations = $commonGroundService->getResourceList('https://wrc.larping.eu/organizations')['hydra:member'];
		$groups = $commonGroundService->getResourceList('https://pdc.larping.eu/groups')['hydra:member'];		
		
		return ["products"=>$products,"organizations"=>$organizations,"groups"=>$groups];
	}

	/**
	 * @Route("/{id}")
	 * @Template
	 */
	public function viewAction(Request $request, CommonGroundService $commonGroundService, $id)
	{
		$organization= $commonGroundService->getResource('https://wrc.larping.eu/organizations/'.$id);

		return ["organization"=>$organization];
	}
}
