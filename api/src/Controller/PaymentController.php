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

class PaymentController extends AbstractController
{
	/**
	* @Route("/payment")
 	* @Template
	*/
	public function paymentAction(Request $request, EntityManagerInterface $em)
	{
		return [];
	}

    /**
     * @Route("/confirm-payment")
     * @Template
     */
    public function confirmAction(Request $request, EntityManagerInterface $em)
    {
        return [];
    }
}
