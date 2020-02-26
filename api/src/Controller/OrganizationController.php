<?php
// src/Controller/DefaultController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use App\Service\OrganizationService;

/**
 * Class OrganizationController
 * @package App\Controller
 * @Route("/organizations")
 */
class OrganizationController extends AbstractController
{
    private $organizationService;

    public function __construct(OrganizationService $organizationService)
    {
        $this->organizationService = $organizationService;
    }

    /**
	* @Route("/")
 	* @Template
	*/
	public function indexAction(Request $request, EntityManagerInterface $em)
	{
	    $organizations = $this->organizationService->getAll();


		return ["organizations"=>$organizations];
	}


	/**
	 * @Route("/{id}")
	 * @Template
	 */
	public function viewAction(Request $request, EntityManagerInterface $em, $id)
	{
		return [];
	}
}
