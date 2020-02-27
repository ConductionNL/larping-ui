<?php

// src/Controller/DefaultController.php

namespace App\Controller;

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
	public function indexAction(CommonGroundService $commonGroundService)
	{
		$products = $commonGroundService->getResourceList('https://pdc.larping.eu/products');
		
		return ["products"=>$products];
	}
	
	/**
	 * @Route("/{uuid}")
	 * @Template
	 */
	public function viewAction(CommonGroundService $commonGroundService, $uuid)
	{
		$product = $commonGroundService->getResource('https://pdc.larping.eu/products/'.$uuid);
		
		return ["product"=>$product];
	}

}
