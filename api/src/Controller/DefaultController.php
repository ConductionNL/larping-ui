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
    public function indexAction(Request $request, CommonGroundService $commonGroundService)
    {
        $organizations = $commonGroundService->getResourceList('https://wrc.larping.eu/organizations')['hydra:member'];
        $groups = $commonGroundService->getResourceList('https://pdc.larping.eu/groups')['hydra:member'];
        $application = $commonGroundService->getResource('https://wrc.larping.eu/applications/71f9f51f-ab58-4b58-9035-b295db48a302');
        $menu = $commonGroundService->getResource('https://wrc.larping.eu/menus/505b716c-9461-4588-95d7-8279b3042807');

        $menuItems = $menu['menuItem'];

        return ['organizations'=>$organizations,'groups'=>$groups,'application'=>$application,'menuItems'=>$menuItems];
    }
}
