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

class ApplyController extends AbstractController
{
	/**
	* @Route("/apply")
 	* @Template
	*/
	public function applyAction(Request $request, EntityManagerInterface $em)
	{
		return [];
	}

    /**
     * @Route("/dashboard")
     * @Template
     */
    public function dashboardAction(Request $request, EntityManagerInterface $em)
    {
        return [];
    }
}
