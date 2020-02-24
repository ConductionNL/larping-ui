<?php

// src/Controller/DefaultController.php

namespace App\Controller;

use App\Service\CommonGroundService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/home")
     * @Template
     */
	public function indexAction(Request $request, CommonGroundService $commonGroundService)
    {
    	$organizations = $commonGroundService->getResourceList('https://cc.larping.eu/organizations');
    	$groups = $commonGroundService->getResourceList('https://pdc.larping.eu');
    	
    	return ['organizations'=>$organizations,'groups'=>$groups];
    }
}
