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

    /**
     * @Route("/")
     * @Template
     */
	public function indexAction(Session $session, Request $request, CommonGroundService $commonGroundService)
    {
		// Wat doen we hier eigenlijk met organizations en groups?
    	$organizations = $commonGroundService->getResourceList('https://cc.zaakonline.nl/organizations',["name"=>"fc"]);
    	$groups = $commonGroundService->getResourceList('https://pdc.larping.online/groups',["sourceOrganization"=>"816802828"]);
    	$products = $commonGroundService->getResourceList('https://pdc.larping.online/products?sourceOrganization=816802828');

    	$orderUri = $session->get('order');
    	$order = false; // Aangezien we de order variable aan hettemplate passeeren moetdie zowiezo bestaan
    	if($orderUri){
    		$order = $commonGroundService->getResource($orderUri);
    	}


    	// Kijken of het formulier is getriggerd
    	if ($request->isMethod('POST')){
    		// kijken of er in de sessie al een order zit, zo nee order aan maken. We slaan hier alleen de order ID (URI) op. Het bijhouden van het order object laten we via de commonground controller aan de cache
    		if(!$orderUri){

    			$order = [];
    			$order['targetOrganization'] = '122432234'; // Ditmoet de RSIN van zijn
    			$order['name'] = 'Website Order';

    			$order = $commonGroundService->createResource($order, 'https://orc.larping.online/orders');
    			$orderUri = $order['@id'];
    			$session->set('order', $orderUri);
    		}

    		// order regel aanmaken op order met de gevraagde gegevens
            $orderPrice = 0;
    		foreach($request->request->get('offers') as $offer)
    		{
                $orderItem= [];
                $orderItem['order'] = $order['@id']; // de vraag is of we hier de baseurl zouden moeten strippen via php pathinfo
                $orderItem['offer'] = $offer; // etc

                $offer = $commonGroundService->getResource($offer, 'https://pdc.larping.online/offers');

                $orderItem['quantity'] = 1;
                $orderItem['price'] = $offer['price'];
                $orderPrice += $offer['price']; //dit wordt rommelig bij verschillende currencies
                $orderItem['priceCurrency'] = $offer['priceCurrency'];
                //$orderItem['tax'] = $offer['taxes']; //dit gaat zo niet werken, de taxes in onze offers zijn wellicht een voorbeeld van hoe het moet, maar maken communicatie met de andere componenten lastig zolang dat niet gelijk is
                $orderItem = $commonGroundService->createResource($orderItem, 'https://orc.larping.online/orderItems'); // diedit uit mijn hoofd dus weet niet of het werkelijk order lines is
    		}
    		// Omdat we een order line hebben toegeveogd willen we het order opnieuw ophalen EN een cash refresh afdwingen
    		$order = $commonGroundService->getResource($orderUri, true);
    		$order['price'] = $orderPrice;
    		$order['priceCurrency'] = $orderItem['priceCurrency'];
            $order = $commonGroundService->updateResource($order, 'https://orc.larping.online/orders');

    		// flashban zetten met eindresultaat
    		$this->addFlash('success', 'Uw product is toegevoegd');
    	}

    	return ['organisations'=>$organizations,'groups'=>$groups,'products'=> $products,'order'=> $order, $this->redirect('producten')];
    }

    /**
     * @Route("/betalen")
     * @Template
     */
    public function betalenAction(Session $session, Request $request, CommonGroundService $commonGroundService)
    {
    	// Wat doen we hier  eigenlijk met organisations en groups?
    	$organizations = $commonGroundService->getResourceList('https://cc.zaakonline.nl/organizations',["name"=>"fc"]);
    	$groups = $commonGroundService->getResourceList('https://pdc.larping.online/groups',["sourceOrganization"=>"816802828"]);

    	$orderUri = $session->get('order');
    	$order = false; // Aangezien we de order variable aan het template passeren moet die sowieso bestaan
    	if($orderUri){
    		$order = $commonGroundService->getResource($orderUri);
    	}

    	// Kijken of het formulier is getriggerd
    	if($request->isMethod('POST')){
    		// contact persoon aanmaken op order
    		$contact = [];
    		$contact['givenName'] = 'voornaam'; // etc
    		$contact['additionalName'] = ''; //n/a
    		$contact['familyName'] = 'tussenvoegsel'.'achternaam';

            $address = [
    		    'street'            =>  '',
                'houseNumber'       =>  '',
                'houseNumberSuffix' =>  '',
                'postalCode'        =>  '',
                'locality'          =>  '',
                ];
            $address= $commonGroundService->createResource($address, 'https://cc.larping.online/addresses');
            $email = [
                'name'  =>  'e-mail 1',
                'email' =>  '',
            ];
            $email = $commonGroundService->createResource($email, 'https://cc.larping.online/emails');
            $telephone = [
                'name'  =>  'phone number 1',
                'telephone' =>  '',
            ];
            $telephone = $commonGroundService->createResource($telephone, 'https://cc.larping.online/telephone');
            $contact['addresses'][0] = $address['@id'];
            $contact['emails'][0] = $email['@id'];
            $contact['telephones'][0] = $telephone['@id'];
            $contact= $commonGroundService->createResource($contact, 'https://cc.larping.online/people');

            $order['remarks'] = '';
            $order['customer'] = $contact['@id'];

    		// order updaten
    		$order = $commonGroundService->updateResource($order);

    		// order naar bc sturen
    		$invoice= $commonGroundService->createResource($order, 'https://bc.larping.online/invoice/order'); //10 minuten klusje, maar hiervoor moet pre validate wss naar pre deserialize
    		$session->set('invoice',$invoice['@id']);

    		// gebruikerdoorsturen naar terug gegeven responce
    		return $this->redirect($invoice['paymentUrl']);
    	}

    	return ['organisations'=>$organizations,'groups'=>$groups,'order'=>$order,$this->redirect('producten/betalen')];
    }

    /**
     * @Route("/betalen/bevestiging/{uuid}")
     * @Template
     */
    public function bevestigingAction(Session $session, Request $request, CommonGroundService $commonGroundService, $uuid)
    {
    	// Wederom wat doen organizations en groups hier
    	$organizations = $commonGroundService->getResourceList('https://cc.zaakonline.nl/organizations',["name"=>"fc"]);
    	$groups = $commonGroundService->getResourceList('https://pdc.larping.online/groups',["sourceOrganization"=>"816802828"]);

    	// Factuur ophalen aan de hand van id
    	$invoice = $commonGroundService->getResource('https://bc.larping.online/invoices/'.$uuid);

    	// We willen voorkomen dat je via deze route elke factuur kan opvragen
    	if($invoice['@id'] != $session->get('invoice')){
    		// Throw auth error
    	}

    	// info renderen
    	$template = $commonGroundService->getResource('https://wrc.larping.online/templates/??????/render',["invoice"=>$invoice]);

    	// mail versturen

    	return ['organisations'=>$organizations,'groups'=>$groups,'invoice'=>$invoice, $this->redirect('producten/betalen/bevestiging')];
    }
}
