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
    	// Kijken of het formulier is getriggerd
    	if ($request->isMethod('POST')) {
    		
    		// Lets check on required values
    		$requiredValues = ['"username"','password'];
    		$error = false;
    		foreach($requiredValues as $requiredValue){
    			if(!$request->request->get($requiredValue)|| $request->request->get($requiredValue) == null){
    				$this->addFlash('danger', $requiredValue.' is a required value');
    				$error = true;
    			}
    		}
    	}	
    		
        return [];
    }

    /**
     * @Route("/register")
     * @Template
     */
    public function registerAction(Request $request, EntityManagerInterface $em)
    {
    	// Kijken of het formulier is getriggerd
    	if ($request->isMethod('POST')) {
    		
    		// Lets check on required values
    		$requiredValues = ['givenName','familyName','password','password2','username'];
    		$error = false;
    		foreach($requiredValues as $requiredValue){
    			
    			if(!$request->request->get($requiredValue)|| $request->request->get($requiredValue) == null){
    				$this->addFlash('danger', $requiredValue.' is a required value');
    				$error = true;
    			}
    			
    			
    			// contact persoon aanmaken op order
    			$contact['givenName'] = $request->request->get('givenName');
    			$contact['familyName'] = $request->request->get('familyName');    			
    			$contact['emails'] = [];
    			$contact['emails'][] = ["name" => "primary", "email" => $request->request->get('username')];
    			$contact = $commonGroundService->createResource($contact, 'https://cc.larping.eu/people');
    			
    			// 
    			$user = [];
    			$user['username'] =  $request->request->get('username');
    			$user['password'] =  $request->request->get('password');
    			$user['contact'] = $contact['@id'];
    		}
    	}	
    	
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
    	// Kijken of het formulier is getriggerd
    	if ($request->isMethod('POST')) {
    		
    		// Lets check on required values
    		$requiredValues = ['givenName','familyName','street','street','houseNumber','postalCode','locality','email'];
    		$error = false;
    		foreach($requiredValues as $requiredValue){
    			if(!$request->request->get($requiredValue)|| $request->request->get($requiredValue) == null){
    				$this->addFlash('danger', $requiredValue.' is a required value');
    				$error = true;
    			}
    		}
    	}	
    	
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
