<?php
// src/Controller/AdminController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class AdminController
 * @package App\Controller
 * @Route("/admin")
 */
class AdminController extends AbstractController
{

//	/**
//	* @Route("/")
// 	* @Template
//	*/
//	public function indexAction(Request $request, EntityManagerInterface $em)
//	{
//	    return[];
//	}
//
//    /**
//     * @Route("/dashboard")
//     * @Template
//     */
//    public function dashboardAction(Request $request, EntityManagerInterface $em)
//    {
//        return[];
//    }
//
//    /**
//     * @Route("/all-events")
//     * @Template
//     */
//    public function alleventsAction(Request $request, EntityManagerInterface $em)
//    {
//        return[];
//    }
//
//    /**
//     * @Route("/all-users")
//     * @Template
//     */
//    public function allusersAction(Request $request, EntityManagerInterface $em)
//    {
//        return[];
//    }
//
//    /**
//     * @Route("/all-organisations")
//     * @Template
//     */
//    public function allorganisationsAction(Request $request, EntityManagerInterface $em)
//    {
//        return[];
//    }
}
