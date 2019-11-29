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
use App\Service\EventService;

/**
 * Class EventController
 * @package App\Controller
 * @Route("/events")
 */
class EventController extends AbstractController
{

    private $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

	/**
	* @Route("/")
 	* @Template
	*/
	public function indexAction(Request $request, EntityManagerInterface $em)
	{
	    $events = $this->eventService->getAll();

		return ["events"=>$events];
	}

    /**
     * @Route("/view")
     * @Template
     */
    public function viewAction(Request $request, EntityManagerInterface $em)
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
