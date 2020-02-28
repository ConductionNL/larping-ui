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
        $application = $commonGroundService->getResource('https://wrc.larping.eu/applications/71f9f51f-ab58-4b58-9035-b295db48a302');
        $menu = $commonGroundService->getResource('https://wrc.larping.eu/menus/505b716c-9461-4588-95d7-8279b3042807');
        $organization = $commonGroundService->getResource('https://wrc.larping.eu'.$application['organization']['@id']);

        $menuItems = $menu['menuItem'];

		$organizations= $commonGroundService->getResourceList('https://wrc.larping.eu/organizations', $query)['hydra:member'];

		return ['organization'=>$organization, "items"=>$organizations,'application'=>$application,'menuItems'=>$menuItems];
	}

	/**
	 * @Route("/{id}")
	 * @Template
	 */
	public function viewAction(Request $request, CommonGroundService $commonGroundService, $id)
	{
	    $groups = $request->request->get('groups');
        $query = ["groups"=>$groups];

        $application = $commonGroundService->getResource('https://wrc.larping.eu/applications/71f9f51f-ab58-4b58-9035-b295db48a302');
        $organization = $commonGroundService->getResource('https://wrc.larping.eu'.$application['organization']['@id']);

		$events = $commonGroundService->getResourceList('https://pdc.larping.eu/products', $query)['hydra:member'];
		$products = $commonGroundService->getResourceList('https://pdc.larping.eu/products', $query)['hydra:member'];

		return ["item"=>$organization,'$events'=>$events,'application'=>$application,'products'=>$products];
	}
}
