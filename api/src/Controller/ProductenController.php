<?php

// src/Controller/DefaultController.php

namespace App\Controller;

use App\Service\CommonGroundService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProductController
 * @package App\Controller
 * @Route("/producten")
 */
class ProductenController extends AbstractController
{

    /**
     * @Route("/")
     * @Template
     */
	public function indexAction(Request $request, CommonGroundService $commonGroundService)
    {
    	$organizations = $commonGroundService->getResourceList('https://cc.zaakonline.nl/organizations',["name"=>"fc"]);
    	$groups = $commonGroundService->getResourceList('https://pdc.larping.online/groups',["sourceOrganization"=>"816802828"]);
    	$products = $commonGroundService->getResourceList('https://pdc.larping.online/products',["sourceOrganization"=>"816802828"]);
		
    	var_dump($products);
    	
    	return ['organisations'=>$organizations,'groups'=>$groups,'products'=> $products, $this->redirect('producten')];
    }

    /**
     * @Route("/betalen")
     * @Template
     */
	public function betalenAction(Request $request, CommonGroundService $commonGroundService)
    {
    	$organizations = $commonGroundService->getResourceList('https://cc.zaakonline.nl/organizations',["name"=>"fc"]);
    	$groups = $commonGroundService->getResourceList('https://pdc.larping.online/groups',["sourceOrganization"=>"816802828"]);

    	return ['organisations'=>$organizations,'groups'=>$groups, $this->redirect('producten/betalen')];
    }

    /**
     * @Route("/betalen/bevestiging")
     * @Template
     */
	public function bevestigingAction(Request $request, CommonGroundService $commonGroundService)
    {
    	$organizations = $commonGroundService->getResourceList('https://cc.zaakonline.nl/organizations',["name"=>"fc"]);
    	$groups = $commonGroundService->getResourceList('https://pdc.larping.online/groups',["sourceOrganization"=>"816802828"]);

    	return ['organisations'=>$organizations,'groups'=>$groups, $this->redirect('producten/betalen/bevestiging')];
    }
}
