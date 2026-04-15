<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Repository\ConferenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/conference')]
class ConferenceController extends AbstractController
{
    #[Route(
        '/{name}/{start}/{end}',
        name: 'app_conference_new',
        requirements: [
            'name' => Requirement::CATCH_ALL,
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

    #[Route('/list', name: 'app_conference_list', methods: ['GET'])]
    public function list(ConferenceRepository $conferenceRepository): Response
    {
        $conferences = $conferenceRepository->findAll();

        return $this->render('conference/list.html.twig', [
            'conferences' => $conferences,
        ]);
    }

    #[Route('/{id}', name: 'app_conference_show', requirements: ['id' => '\d+'])]
    public function show(Conference $conference): Response
    {
        return $this->render('conference/show.html.twig', [
            'conference' => $conference,
        ]);
    }
}
