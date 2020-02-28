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
		
		return ["items"=>$products,"organizations"=>$organizations,"groups"=>$groups,];
	}
	
	/**
	 * @Route("/{id}")
	 * @Template
	 */
	public function viewAction(CommonGroundService $commonGroundService, $id)
	{
		$product = $commonGroundService->getResource('https://pdc.larping.eu/products/'.$id);
		
		return ["product"=>$product];
	}

}
