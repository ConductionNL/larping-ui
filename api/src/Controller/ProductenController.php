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
        $products = $commonGroundService->getResourceList('https://pdc.larping.online/products?sourceOrganization=816802828');

        $orderUri = $session->get('order');
        $order = false; // Aangezien we de order variable aan hettemplate passeeren moetdie zowiezo bestaan
        if ($orderUri) {
            $order = $commonGroundService->getResource($orderUri);
        }


        // Kijken of het formulier is getriggerd
        if ($request->isMethod('POST')) {
            // kijken of er in de sessie al een order zit, zo nee order aan maken. We slaan hier alleen de order ID (URI) op. Het bijhouden van het order object laten we via de commonground controller aan de cache
            if (!$orderUri) {
                $order = [];
                $order['name'] = "";
                $order['targetOrganization'] = "";
                $order['items'] = [];

                foreach ($_POST['offer'] as $offer) {
                    $orderItem = [];
                    $orderItem['offer'] = $offer; // etc

                    $orderItem = $commonGroundService->getCreateResource($orderItem, 'https://orc.larping.online/orderItems');
                    $orderItemUri = $orderItem['@id'];
                    array_push($order['items'], $orderItemUri);
                }
            } else {
                foreach ($_POST['offer'] as $offer) {
                    $orderItem = [];
                    $orderItem['offer'] = $offer; // etc

                    $orderItem = $commonGroundService->getCreateResource($orderItem, 'https://orc.larping.online/orderItems');
                    $orderItemUri = $orderItem['@id'];
                    array_push($order['items'], $orderItemUri);
                }

                $session->set('order', $orderUri);
                // order regel aanmaken op order met de gevraagde gegevens

                $orderline = [];
                $orderline['order'] = $order['@id']; // de vraag is of we hier de baseurl zouden moeten strippen via php pathinfo
                $orderline[''] = ''; // etc

                $orderline = $commonGroundService->getCreateResource($orderline, 'https://orc.larping.online/orderlines'); // diedit uit mijn hoofd dus weet niet of het werkelijk order linses is

                // Omdat we een order line hebben toegeveogd willen we het order opnieuw ophalen EN een cash refresh afdwingen
                $order = $commonGroundService->getResource($orderUri, true);

                // flashban zetten met eindresultaat
                $this->addFlash('success', 'Uw product is toegevoegd');
            }

            return ['products' => $products, 'order' => $order, $this->redirect('producten')];
        }
    }

    /**
     * @Route("/betalen")
     * @Template
     */
    public function betalenAction(Session $session, Request $request, CommonGroundService $commonGroundService)
    {
        // Wat doen we hier  eigenlijk met organisations en groups?
        $organizations = $commonGroundService->getResourceList('https://cc.zaakonline.nl/organizations', ["name" => "fc"]);
        $groups = $commonGroundService->getResourceList('https://pdc.larping.online/groups', ["sourceOrganization" => "816802828"]);

        $orderUri = $session->get('order');
        $order = false; // Aangezien we de order variable aan hettemplate passeeren moetdie zowiezo bestaan
        if ($orderUri) {
            $order = $commonGroundService->getResource($orderUri);
        }

        // Kijken of het formulier is getriggerd
        if ($request->isMethod('POST')) {
            // contact persoon aanmaken op order
            $contact = [];
            $contact[''] = ''; // etc

            $contact = $commonGroundService->getCreateResource($contact, 'https://cc.larping.online/people');

            $order['contact'] = $contact['@id'];

            // order updaten
            $order = $commonGroundService->getUpdateResource($order);

            // order naar bc sturen

            $invoice = $commonGroundService->getCreateResource($order, 'https://bc.larping.online/invoice');
            $session->set('invoice', $invoice['@id']);

            // gebruikerdoorsturen naar terug gegeven responce
            return $this->redirect($invoice['paymentUrl']);
        }

        return ['organisations' => $organizations, 'groups' => $groups, 'order' => $order, $this->redirect('producten/betalen')];
    }

    /**
     * @Route("/betalen/bevestiging/{uuid}")
     * @Template
     */
    public function bevestigingAction(Session $session, Request $request, CommonGroundService $commonGroundService, $uuid)
    {
        // Wederom wat doen organizations en groups hier
        $organizations = $commonGroundService->getResourceList('https://cc.zaakonline.nl/organizations', ["name" => "fc"]);
        $groups = $commonGroundService->getResourceList('https://pdc.larping.online/groups', ["sourceOrganization" => "816802828"]);

        // Factuur ophalen aan de hand van id
        $invoice = $commonGroundService->getResource('https://bc.larping.online/invoices/' . $uuid);

        // We willen voorkomen dat je via deze route elke factuur kan opvragen
        if ($invoice['@id'] != $session->get('invoice')) {
            // Throw auth error
        }

        // info renderen
        $template = $commonGroundService->getResource('https://wrc.larping.online/templates/??????/render', ["invoice" => $invoice]);

        // mail versturen

        return ['organisations' => $organizations, 'groups' => $groups, 'invoice' => $invoice, $this->redirect('producten/betalen/bevestiging')];
    }
}
