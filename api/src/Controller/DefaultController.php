<?php

// src/Controller/DefaultController.php

namespace App\Controller;

use App\Service\CommonGroundService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        $organizations = $commonGroundService->getResourceList('https://wrc.larping.eu/organizations')['hydra:member'];
        $groups= $commonGroundService->getResourceList('https://pdc.larping.eu/groups')['hydra:member'];
        
        // Lets get the domain for local development
        $domain = $request->getHost();
        if(!$domain || $domain == "localhost"){
        	$domain = "https://www.larping.eu";
        }        
        
        // This lets us fetch the default application for this domains
        $applications = $commonGroundService->getResourceList('https://wrc.larping.eu/applications'); // should take domain into considaration ,["domain"=>"https://www.larping.eu"]
        $application = $applications['hydra:member'][0]; /*@todo this needs an error catch */
        $organization = $application['organization'];      
                
        // This could also be done from our template
        $menu = $commonGroundService->getResource(str_replace ("dev.","",$application['defaultConfiguration']['configuration']['menuPrimary'])); // prevent double dev

        return ['organizations'=>$organizations,'groups'=>$groups,'organization'=>$organization,'application'=>$application,'menu'=>$menu];
    }
}
