<?php

// src/Controller/DefaultController.php

namespace App\Controller;

use App\Service\CommonGroundService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class LandingpageController
 * @package App\Controller
 * @Route("/")
 */
class LandingpageController extends AbstractController
{
    /**
     * @Route("/")
     * @Template
     */
    public function indexAction(Session $session, Request $request, CommonGroundService $commonGroundService)
    {
        // Wat doen we hier eigenlijk met organizations en groups?
        $organizations = []; // $commonGroundService->getResourceList('https://cc.larping.online/organizations',["name"=>"fc"]);
        $groups = []; // $commonGroundService->getResourceList('https://pdc.larping.online/groups',["sourceOrganization"=>"816802828"]);
        $products = $commonGroundService->getResourceList('https://pdc.larping.eu/products', ["sourceOrganization" => "816802828"]);

        $orderUri = $session->get('order');
        $order = false; // Aangezien we de order variable aan hettemplate passeeren moetdie zowiezo bestaan
        if ($orderUri) {
            $order = $commonGroundService->getResource($orderUri);
        }


        // Kijken of het formulier is getriggerd
        if ($request->isMethod('POST')) {
        	
            // kijken of er in de sessie al een order zit, zo nee order aan maken. We slaan hier alleen de order ID (URI) op. Het bijhouden van het order object laten we via de commonground controller aan de cache
        	
        	$session->set('offers', $request->request->get('offers'));

            // flashban zetten met eindresultaat
            $this->addFlash('success', 'Uw product(en) is toegevoegd');

            return $this->redirect($this->generateUrl('app_landingpage_betalen'));
        }

        return ['organisations' => $organizations, 'groups' => $groups, 'products' => $products, 'order' => $order, $this->redirect('/')];
    }

    /**
     * @Route("/betalen")
     * @Template
     */
    public function betalenAction(Session $session, Request $request, CommonGroundService $commonGroundService)
    {
        // Als we geen order hebbenkunnen we logischerwijs ook geen betaling verwerken
        $offers = $session->get('offers');
        
        // Terug sturen als er geen offers zijn
        if(!$offers || count($offers) < 1) {
        	$this->addFlash('danger', 'There are no products in your basket');        			
        	return $this->redirect($this->generateUrl('app_landingpage_index'));
        } 
        
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
        	
        	if($error){        		
        		return ['offers' => $offers];
        	}
        	
            // contact persoon aanmaken op order
            $contact['givenName'] = $request->request->get('givenName');
            $contact['additionalName'] = $request->request->get('additionalName');
            $contact['familyName'] = $request->request->get('familyName');

            $contact['street'] = $request->request->get('street');
            $contact['houseNumber'] = $request->request->get('houseNumber');
            $contact['houseNumberSuffix'] = $request->request->get('houseNumberSuffix');

            $contact['postalCode'] = $request->request->get('postalCode');
            $contact['locality'] = $request->request->get('locality');


            $contact['emails'] = [];
            $contact['emails'][0] = ["name" => "primary", "email" => $request->request->get('email')];
            $contact['telephones'] = [];
            $contact['telephones'][0] = ["name" => "primary", "telephone" => $request->request->get('telephone')];
            
            $contact['sourceOrganization'] = '816802828'; // Ditmoet de RSIN van zijn
            $contact = $commonGroundService->createResource($contact, 'https://cc.larping.eu/people');

            $order = [];
            $order['targetOrganization'] = '816802828'; // Ditmoet de RSIN van zijn
            $order['name'] = 'Website Order';
            $order['customer'] = $contact['@id']; // Deze zou leeg moeten mogen zijn
            $order['stage'] = 'cart'; // Deze zou leeg moeten mogen zijn
            $order['items'] = [];
            
        	$order['remark'] = $request->request->get('remarks');
        	$order['customer'] = $contact['@id'];
        	
        	if (!in_array('description',$order) || !$order['description']) {
        		$order['description'] = "Website Order";
        	}
        	
        	foreach ($offers as $offer) {
        		
        		// Dit is lelijk, eigenlijk zou de offer id an zich al een uri moeten zijn
        		$offer = $commonGroundService->getResource($offer);
        			
        		$orderItem = [];
        		$orderItem['offer'] = $offer['@id'];
        		$orderItem['name'] = $offer['name'];
        		$orderItem['description'] = $offer['description'];
        		$orderItem['quantity'] = 1;
        		$orderItem['price'] = number_format($offer['price'] / 100, 2, '.', ' '); // hier gaat iets mis dat dit nodig is
        		$orderItem['priceCurrency'] = $offer['priceCurrency'];
        		//$orderItem['taxPercentage'] = $offer['taxes'][0]['percentage']; // Taxes in orders en invoices moet worden bijgewerkt
        		$orderItem['taxPercentage'] = 0; /*@todo dit moet dus nog worden gefixed */
        			
        		/*@todo wtf gebruikt het orc snake case?*/
        		//$orderItem = $commonGroundService->createResource($orderItem, 'https://orc.larping.eu/order_items');
        		$order['items'][] = $orderItem;
        	}
            
            // order updaten
        	$order = $commonGroundService->createResource($order, 'https://orc.larping.eu/orders');
            $session->set('order', $order['@id']);
           
            // We don't want to make an invoice and payment if we do not have an price
            if($order['price'] == "0.00"){
            	return $this->redirect($this->generateUrl('bevestiging'));            	
            }
            
            // order naar bc sturen
            $invoice = $commonGroundService->createResource($order, 'https://bc.larping.eu/order');
            $session->set('invoice', $invoice['@id']);
            // gebruikerdoorsturen naar terug gegeven responce
            return $this->redirect($invoice['paymentUrl']);
        }

        return ['offers' => $offers];
    }

    /**
     * @Route("/betalen/bevestigen/{uuid}")
     * @Template
     */
    public function bevestigingAction(Session $session, Request $request, CommonGroundService $commonGroundService, $uuid)
    {
        sleep(5);

        // Factuur ophalen aan de hand van id
    	if($uuid){
    		$invoice = $commonGroundService->getResource('https://bc.larping.eu/invoices/' . $uuid);
    		
    		// We willen voorkomen dat je via deze route elke factuur kan opvragen
    		if ($invoice['@id'] != $session->get('invoice')) {
    			// Throw auth error
    		}
        }

        if(!in_array("paid", $invoice) || !$invoice["paid"]){
            return ['invoice'=>$invoice];
        }

        $order = $commonGroundService->getResource($invoice['order']);
        $contact = $commonGroundService->getResource($invoice['customer']);

        $payments = $commonGroundService->getResource($invoice['payments'][0]);

        $variables = ['invoice'=>$invoice,'order'=>$order,'contact'=>$contact];

        // mail versturen
        $userMail= [
        		"reciever"=>$invoice['customer'],
        		"sender"=>"https://cc.larping.eu/organizations/27141158-fde5-4e8b-a2b7-07c7765f0c63",
        		"content"=>"https://wrc.larping.eu/templates/cc7d0c70-bb59-4d85-9845-863e896e6ee9",
        		"service"=>"/services/7d48f13b-f44e-495b-b774-3d4f9b994b09",
        		"status"=>"concept",
        		//"externalServiceId"=>"7d48f13b-f44e-495b-b774-3d4f9b994b09",
        		"data"=> $variables
        ];
        $userMail= $commonGroundService->createResource($userMail, 'https://bs.larping.eu/messages');
        $userSMS= [
        		"reciever"=>$invoice['customer'],
        		"sender"=>"https://cc.larping.eu/organizations/27141158-fde5-4e8b-a2b7-07c7765f0c63",
        		"content"=>"https://wrc.larping.eu/templates/3b96e9bc-1d9c-4701-9554-4a597f01f4bf",
        		"service"=>"/services/dfb46b45-0737-4500-b8f9-2f791913c8ad",
        		"status"=>"concept",
        		//"externalServiceId"=>"dfb46b45-0737-4500-b8f9-2f791913c8ad",
        		"data"=> $variables
        ];
        $userSMS= $commonGroundService->createResource($userSMS, 'https://bs.larping.eu/messages');
        $organisationMail= [
        		"reciever"=>"https://cc.larping.eu/organizations/27141158-fde5-4e8b-a2b7-07c7765f0c63",
        		"sender"=>"https://cc.larping.eu/organizations/27141158-fde5-4e8b-a2b7-07c7765f0c63",
        		"content"=>"https://wrc.larping.eu/templates/e287f1f4-704e-49e3-8a33-eab955ff2158",
        		"service"=>"/services/7d48f13b-f44e-495b-b774-3d4f9b994b09",
        		"status"=>"concept",
        		//"externalServiceId"=>"7d48f13b-f44e-495b-b774-3d4f9b994b09",
        		"data"=> $variables
        ];
        $organisationMail= $commonGroundService->createResource($organisationMail, 'https://bs.larping.eu/messages');
        $organisationSMS= [
        		"reciever"=>"https://cc.larping.eu/organizations/27141158-fde5-4e8b-a2b7-07c7765f0c63",
        		"sender"=>"https://cc.larping.eu/organizations/27141158-fde5-4e8b-a2b7-07c7765f0c63",
        		"content"=>"https://wrc.larping.eu/templates/db583bf1-22ab-47d5-8656-a6faf95a1f7f",
        		"service"=>"/services/dfb46b45-0737-4500-b8f9-2f791913c8ad",
        		"status"=>"concept",
        		//"externalServiceId"=>"dfb46b45-0737-4500-b8f9-2f791913c8ad",
        		"data"=> $variables
        ];
        $organisationSMS= $commonGroundService->createResource($organisationSMS, 'https://bs.larping.eu/messages');

        // Clear the session for a new order

        //todo check if the payment status is payed, if so remove order and invoice, if not don't.
        $session->remove('order');
        $session->remove('invoice');

        return $variables;
    }

    /**
     * @Route ("/terms-of-services")
     * @Template
     */
    public function termsofserviceAction(Session $session, Request $request, CommonGroundService $commonGroundService)
    {
        return [];
    }


    /**
     * @Route ("/privacy-policy")
     * @Template
     */
    public function privacypolicyAction(Session $session, Request $request, CommonGroundService $commonGroundService)
    {
        return [];
    }
}
