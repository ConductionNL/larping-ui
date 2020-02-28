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
 * Class HomeController
 * @package App\Controller
 * @Route("/home")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/")
     * @Template
     */
	public function indexAction(Request $request, CommonGroundService $commonGroundService)
    {
    	$organizations = $commonGroundService->getResourceList('https://wrc.larping.eu/organizations')['hydra:member'];
    	$groups = $commonGroundService->getResourceList('https://pdc.larping.eu/groups')['hydra:member'];

    	return ['organizations'=>$organizations,'groups'=>$groups];
    }
}
