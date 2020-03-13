<?php

// src/Controller/DefaultController.php

namespace App\Controller;

use App\Service\CommonGroundService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class DefaultController
 * @package App\Controller
 * @Route("/home")
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/")
     * @Template
     */
	public function indexAction(Request $request, CommonGroundService $commonGroundService, ParameterBagInterface $params)
	{
		$commonGroundService->getApplication();
        $organizations = $commonGroundService->getResourceList('https://wrc.larping.eu/organizations')['hydra:member'];
        $groups= $commonGroundService->getResourceList('https://pdc.larping.eu/groups')['hydra:member'];
        
        // Lets get the domain for local development
        $domain = $request->getHost();
        if(!$domain || $domain == "localhost"){
        	$domain = "https://www.larping.eu";
        }

        return ['organizations'=>$organizations,'groups'=>$groups];
    }

    /**
     * @Route ("/terms-of-services")
     * @Template
     */
    public function termsofserviceAction(Session $session, Request $request, CommonGroundService $commonGroundService)
    {
        return [];
    }

    /**
     * @Route ("/privacy-policy")
     * @Template
     */
    public function privacypolicyAction(Session $session, Request $request, CommonGroundService $commonGroundService)
    {
        return [];
    }
}
