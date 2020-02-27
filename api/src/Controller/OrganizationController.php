<?php
// src/Controller/DefaultController.php
namespace App\Controller;

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
	public function indexAction(CommonGroundService $commonGroundService)
	{
		$organizations= $commonGroundService->getResourceList('https://wrc.larping.eu/organizations');
		
		return ["organizations"=>$organizations];
	}
	
	/**
	 * @Route("/{uuid}")
	 * @Template
	 */
	public function viewAction(CommonGroundService $commonGroundService, $uuid)
	{
		$organization = $commonGroundService->getResource('https://wrc.larping.eu/organizations/'.$uuid);
		
		return ["organization"=>$organization];
	}
}
