<?php

namespace App\DataFixtures;

use App\Factory\ConferenceFactory;
use App\Factory\OrganizationFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->loadOrganizations();
        $this->loadConferences();
    }

    private function loadOrganizations(): void
    {
        $data = json_decode(
            file_get_contents(__DIR__.'/data/organizations.json'),
            true,
            flags: JSON_THROW_ON_ERROR
        );

        foreach ($data as $row) {
            OrganizationFactory::createOne([
                'name' => $row['name'],
                'presentation' => $row['presentation'],
                'createdAt' => new \DateTimeImmutable($row['createdAt']),
            ]);
        }
    }

    private function loadConferences(): void
    {
        $data = json_decode(
            file_get_contents(__DIR__.'/data/conferences.json'),
            true,
            flags: JSON_THROW_ON_ERROR
        );

        foreach ($data as $row) {
            $params = [
                'name' => $row['name'],
                'description' => $row['description'],
                'accessible' => $row['accessible'],
                'startAt' => new \DateTimeImmutable($row['startAt']),
                'endAt' => new \DateTimeImmutable($row['endAt']),
            ];

            try {
                $params['organizations'] = [
                    OrganizationFactory::find(['name' => $row['organization']['name']]),
                ];
            } catch (\Exception $e) {
            }

            if (isset($row['prerequisites'])) {
                $params['prerequisites'] = $row['prerequisites'];
            }

            ConferenceFactory::createOne($params);
        }
    }
}
