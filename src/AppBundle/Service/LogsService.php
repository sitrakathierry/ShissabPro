<?php

namespace AppBundle\Service;
use AppBundle\Entity\Logs;
use Doctrine\ORM\EntityManagerInterface;

class LogsService
{
	private $em;

	public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

	public function setStory($user,$motif)
	{
		$logs = new Logs();
		$dateCreation = new \DateTime('now');

		$logs->setUser($user);
		$logs->setDateModification($dateCreation);
		$logs->setMotif($motif);

		// $this->em->getRepository('AppBundle:User');

        $this->em->persist($logs);
        $this->em->flush();
	}
}