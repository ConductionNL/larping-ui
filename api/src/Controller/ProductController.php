<?php

// src/Controller/DefaultController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use App\Service\CommonGroundService;

/**
 * Class ProductController
 * @package App\Controller
 * @Route("/products")
 */
class ProductController extends AbstractController
{
	/**
	 * @Route("/")
	 * @Template
	 */
	public function indexAction(Request $request, CommonGroundService $commonGroundService)
	{
		$groups = $request->request->get('groups');
		$query = ["groups"=>$groups,"type"=>"ticket"];

		$products = $commonGroundService->getResourceList('https://pdc.larping.eu/products', $query)['hydra:member'];
		$organizations = $commonGroundService->getResourceList('https://wrc.larping.eu/organizations')['hydra:member'];
		$groups = $commonGroundService->getResourceList('https://pdc.larping.eu/groups')['hydra:member'];
        $application = $commonGroundService->getResource('https://wrc.larping.eu/applications/71f9f51f-ab58-4b58-9035-b295db48a302');
        $menu = $commonGroundService->getResource('https://wrc.larping.eu/menus/505b716c-9461-4588-95d7-8279b3042807');

        $menuItems = $menu['menuItem'];

		return ["items"=>$products,"organizations"=>$organizations,"groups"=>$groups,'application'=>$application,'menuItems'=>$menuItems];
	}

	/**
	 * @Route("/{id}")
	 * @Template
	 */
	public function viewAction(CommonGroundService $commonGroundService, $id)
	{
		$product = $commonGroundService->getResource('https://pdc.larping.eu/products/'.$id);
        $application = $commonGroundService->getResource('https://wrc.larping.eu/applications/71f9f51f-ab58-4b58-9035-b295db48a302');
        $menu = $commonGroundService->getResource('https://wrc.larping.eu/menus/505b716c-9461-4588-95d7-8279b3042807');

        $menuItems = $menu['menuItem'];

		return ["product"=>$product,'application'=>$application,'menuItems'=>$menuItems];
	}

}
