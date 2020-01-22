<?php
// Conduction/CommonGroundBundle/Service/RequestTypeService.php

/*
 * This file is part of the Conduction Common Ground Bundle
 *
 * (c) Conduction <info@conduction.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;

use App\Entity\Request;

class OrganizationService
{
	private $em;

	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
	}

	public function getAll()
	{
		$organizations = [];

		$organization["name"] = "q date";
		$organization["desription"] = "q date";
		$organization["data"] = new \DateTime;
		$organizations[] = $organization;


		$organization["name"] = "C date";
		$organization["desription"] = "q date";
		$organization["data"] = new \DateTime;
		$organizations[] = $organization;

		return $organizations;
	}

}
