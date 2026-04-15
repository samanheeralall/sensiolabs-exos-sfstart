<?php

namespace App\Controller;

use App\Entity\Conference;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class ConferenceController extends AbstractController
{
    #[Route(
        '/conference/{name}/{start}/{end}',
        name: 'app_conference_new',
        requirements: [
            'name' => '[a-zA-Z0-9-_]{3,}',
            'start' => Requirement::DATE_YMD,
            'end' => Requirement::DATE_YMD,
        ],
    )]
    public function newConference(string $name, string $start, string $end, EntityManagerInterface $entityManager): Response
    {
        $conference = new Conference()
            ->setName($name)
            ->setDescription('Some description')
            ->setAccessible(true)
            ->setStartAt(new \DateTimeImmutable($start)->setTime(0, 0, 0))
            ->setEndAt(new \DateTimeImmutable($end)->setTime(23, 59, 59));

        $entityManager->persist($conference);
        $entityManager->flush();

        return new Response('Conference created');
    }
}
