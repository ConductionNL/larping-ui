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
use App\Service\CommonGroundService;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

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
	public function login(AuthenticationUtils $authenticationUtils)
	{
		// get the login error if there is one
		$error = $authenticationUtils->getLastAuthenticationError();
		// last username entered by the user
		$lastUsername = $authenticationUtils->getLastUsername();
		
		if($error){
			$this->addFlash('danger', $error);
		}
		
		return [];
	}

    /**
     * @Route("/register")
     * @Template
     */
	public function registerAction(Request $request, CommonGroundService $commonGroundService, ParameterBagInterface $params)
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
    			
    			
    			if($request->request->get('password') != $request->request->get('password2')){
    				$this->addFlash('danger','Password and repeat password do not match');
    				$error = true;
    			}
    			
    			
    			$users = $commonGroundService->getResourceList($params->get('auth_provider_user').'/users',["username"->$credentials["username"]]);
    			$users = $users["hydra:member"];
    			
    			if(count($users) >= 1){
    				$this->addFlash('danger','Username is already taken');
    				$error = true;
    			}
    			
    			if($error){
    				return [];
    			}
    			
    			
    			$application = $commonGroundService->getApplication();
    			
    			// contact persoon aanmaken op order
    			$contact['givenName'] = $request->request->get('givenName');
    			$contact['familyName'] = $request->request->get('familyName');    			
    			$contact['emails'] = [];
    			$contact['emails'][] = ["name" => "primary", "email" => $request->request->get('username')];
    			//$contact['organization'] = 'https://wrc.larping.eu'.$application['organization']['@id'];
    			$contact = $commonGroundService->createResource($contact, 'https://cc.larping.eu/people'); /*@todo awfulle specific */
    			
    			//  Create the uses
    			$user = [];
    			$user['username'] =  $request->request->get('username');
    			$user['password'] =  $request->request->get('password');
    			$user['organization'] = 'https://wrc.larping.eu'.$application['organization']['@id'];
    			$user['perosn'] = $contact['@id']; 
    		}
    	}	
    	
        return [];
    }


    /**
     * @Route("/register-confirm")
     * @Template
     */
    public function confirmAction(Request $request)
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
    public function profileAction(Request $request)
    {
        return [];
    }

    /**
     * @Route("history")
     * @Template
     */
    public function historyAction(Request $request)
    {
        return[];
    }

    /**
     * @Route("settings")
     * @Template
     */
    public function settingsAction(Request $request)
    {
        return[];
    }

}
