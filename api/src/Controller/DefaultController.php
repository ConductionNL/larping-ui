<?php

// src/Controller/DefaultController.php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/")
     * @Template
     */
	public function indexAction(Request $request, CommonGroundService $commonGroundService)
    {
		//  search = ergens vandaan;
    	
    	$organizations = $commonGroundService->getResourceList('https://cc.zaakonline.nl/organizations',["name"=>"fc"]);
    	$groups = $commonGroundService->getResourceList('https://pdc.zaakonline.nl/groups',["sourceOrganization"=>"002851234"]);
    	
    	return ['organisations'=>$organizations,'groups'=>$groups];
    	
    }
}
