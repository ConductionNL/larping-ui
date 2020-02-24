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
use App\Service\OrganisationService;

/**
 * Class OrganisationController
 * @package App\Controller
 * @Route("/organisations")
 */
class OrganisationController extends AbstractController
{
//    /**
//	* @Route("/")
// 	* @Template
//	*/
//	public function indexAction(Request $request, EntityManagerInterface $em)
//	{
//		return [];
//	}
}
