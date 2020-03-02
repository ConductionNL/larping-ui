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

/**
 * Class UserController
 * @package App\Controller
 *
 */
class UserController extends AbstractController
{
    /**
     * @Route("/login")
     * @Template
     */

    public function loginAction(Request $request, EntityManagerInterface $em)
    {
        return [];
    }

    /**
     * @Route("/register")
     * @Template
     */
    public function registerAction(Request $request, EntityManagerInterface $em)
    {
        return [];
    }


    /**
     * @Route("/register-confirm")
     * @Template
     */
    public function confirmAction(Request $request, EntityManagerInterface $em)
    {
        return [];
    }
    
    /**
     * @Route("/reminder")
     * @Template
     */
    public function reminderAction(Request $request, EntityManagerInterface $em)
    {
    	return [];
    }

    /**
     * @Route("profile")
     * @Template
     */
    public function profileAction(Request $request, EntityManagerInterface $em)
    {
        return [];
    }

    /**
     * @Route("history")
     * @Template
     */
    public function historyAction(Request $request, EntityManagerInterface $em)
    {
        return[];
    }

    /**
     * @Route("settings")
     * @Template
     */
    public function settingsAction(Request $request, EntityManagerInterface $em)
    {
        return[];
    }

}
