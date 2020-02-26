<?php

// src/Controller/DefaultController.php

namespace App\Controller;

use App\Service\CommonGroundService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class ProductController
 * @package App\Controller
 * @Route("/producten")
 */
class ProductenController extends AbstractController
{

//    /**
//     * @Route("/")
//     * @Template
//     */
//	public function indexAction(Session $session, Request $request, CommonGroundService $commonGroundService)
//    {
//		// Wat doen we hier eigenlijk met organizations en groups?
//    	$organizations = []; // $commonGroundService->getResourceList('https://cc.larping.online/organizations',["name"=>"fc"]);
//    	$groups =   []; // $commonGroundService->getResourceList('https://pdc.larping.online/groups',["sourceOrganization"=>"816802828"]);
//    	$products = $commonGroundService->getResourceList('https://pdc.larping.eu/products',["sourceOrganization"=>"816802828"]);
//
//    	$orderUri = $session->get('order');
//    	$order = false; // Aangezien we de order variable aan hettemplate passeeren moetdie zowiezo bestaan
//    	if($orderUri){
//    		$order = $commonGroundService->getResource($orderUri);
//    	}
//
//
//    	// Kijken of het formulier is getriggerd
//    	if ($request->isMethod('POST')){
//    		// kijken of er in de sessie al een order zit, zo nee order aan maken. We slaan hier alleen de order ID (URI) op. Het bijhouden van het order object laten we via de commonground controller aan de cache
//
//    		//if(!$orderUri){
//
//    			$contact = [];
//    			$contact['givenName'] = 'voornaam';
//    			$contact['additionalName'] = 'tussenvoegsel';
//    			$contact['familyName'] = 'achternaam';
//    			$contact['sourceOrganization'] = '816802828'; // Ditmoet de RSIN van zijn
//    			$contact = $commonGroundService->createResource($contact, 'https://cc.larping.eu/people');
//
//    			$order = [];
//    			$order['targetOrganization'] = '816802828'; // Ditmoet de RSIN van zijn
//    			$order['name'] = 'Website Order';
//    			$order['customer'] = $contact['@id']; // Deze zou leeg moeten mogen zijn
//    			$order['stage'] = 'cart'; // Deze zou leeg moeten mogen zijn
//
//    			$order = $commonGroundService->createResource($order, 'https://orc.larping.eu/orders');
//    			$orderUri = $order['@id'];
//    			$session->set('order', $orderUri);
//    		//}
//
//            if($request->request->get('offers')){
//	    		foreach($request->request->get('offers') as $offer)
//	    		{
//	    			// Dit is lelijk, eigenlijk zou de offer id an zich al een uri moeten zijn
//	    			$offer = $commonGroundService->getResource($offer);
//
//	    			// we need to parse the @id becouse this is an component internar reference
//	    			$parsedId = parse_url($order['@id']);
//
//	                $orderItem= [];
//	                $orderItem['order'] = $parsedId['path'];
//	                $orderItem['offer'] = $offer['@id'];
//	                $orderItem['name'] =$offer['name'];
//	                $orderItem['description'] = $offer['description'];
//	                $orderItem['quantity'] = 1;
//	                $orderItem['price'] = number_format($offer['price']/100, 2, '.', ' '); // hier gaat iets mis dat dit nodig is
//	                $orderItem['priceCurrency'] = $offer['priceCurrency'];
//	                //$orderItem['taxPercentage'] = $offer['taxes'][0]['percentage']; // Taxes in orders en invoices moet worden bijgewerkt
//	                $orderItem['taxPercentage'] = 0; /*@todo dit moet dus nog worden gefixed */
//
//	                /*@todo wtf gebruikt het orc snake case?*/
//	                $orderItem = $commonGroundService->createResource($orderItem, 'https://orc.larping.eu/order_items');
//	    		}
//            }
//    		// Omdat we een order line hebben toegeveogd willen we het order opnieuw ophalen EN een cash refresh afdwingen
//    		$order = $commonGroundService->getResource($orderUri, true);
//
//    		// flashban zetten met eindresultaat
//    		$this->addFlash('success', 'Uw product is toegevoegd');
//
//
//    		return $this->redirect($this->generateUrl('app_producten_betalen'));
//    	}
//
//    	return ['organisations'=>$organizations,'groups'=>$groups,'products'=> $products,'order'=> $order, $this->redirect('producten')];
//    }
//
//    /**
//     * @Route("/betalen")
//     * @Template
//     */
//    public function betalenAction(Session $session, Request $request, CommonGroundService $commonGroundService)
//    {
//    	// Als we geen order hebbenkunnen we logischerwijs ook geen betaling verwerken
//    	$orderUri = $session->get('order');
//    	if($orderUri){
//    		$order = $commonGroundService->getResource($orderUri);
//    		$contact = $commonGroundService->getResource($order['customer']);
//    	}
//    	else{
//    		return $this->redirect($this->generateUrl('app_producten_index'));
//    	}
//
//    	// Kijken of het formulier is getriggerd
//    	if($request->isMethod('POST')){
//    		// contact persoon aanmaken op order
//
//    		$order['remarks'] = $request->request->get('offers');
//
//    		// order updaten
//    		$order = $commonGroundService->updateResource($order);
//
//    		// order naar bc sturen
//    		$invoice= $commonGroundService->createResource($order, 'https://bc.larping.eu/invoice/order'); //10 minuten klusje, maar hiervoor moet pre validate was naar pre deserialize
//    		$session->set('invoice',$invoice['@id']);
//
//    		// gebruikerdoorsturen naar terug gegeven responce
//    		return $this->redirect($invoice['paymentUrl']);
//    	}
//
//    	return ['order'=>$order,'contact'=>$contact];
//    }
//
//    /**
//     * @Route("/betalen/bevestiging/{uuid}")
//     * @Template
//     */
//    public function bevestigingAction(Session $session, Request $request, CommonGroundService $commonGroundService, $uuid)
//    {
//    	// Factuur ophalen aan de hand van id
//    	$invoice = $commonGroundService->getResource('https://bc.larping.eu/invoices/'.$uuid);
//
//    	// We willen voorkomen dat je via deze route elke factuur kan opvragen
//    	if($invoice['@id'] != $session->get('invoice')){
//    		// Throw auth error
//    	}
//
//    	// info renderen
//    	$template = $commonGroundService->getResource('https://wrc.larping.online/templates/??????/render',["invoice"=>$invoice]);
//
//    	// mail versturen
//
//    	// Clear the session for a new order
//    	$session->remove('order');
//    	$session->remove('invoice');
//
//    	return ['invoice'=>$invoice];
//    }
}
