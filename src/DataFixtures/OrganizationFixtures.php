<?php

namespace App\DataFixtures;

use App\Entity\Organization;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Finder\Finder;

class OrganizationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $finder = new Finder();
        $finder
            ->files()
            ->in(__DIR__.'/data/')
            ->name('organizations.json')
        ;

        foreach ($finder as $file) {
            $data = json_decode($file->getContents(), true, flags: JSON_THROW_ON_ERROR);

            foreach ($data as $row) {
                $organization = $this->createOrganization($row);

                $manager->persist($organization);

                $this->addReference(
                    OrganizationFixtures::getReferenceKey($row['name']),
                    $organization
                );
            }
        }

        $manager->flush();
    }

    public static function getReferenceKey(string $name): string
    {
        $name = strtr($name, ' ', '_');

        return sprintf('organization_%s', $name);
    }

    private function createOrganization(array $row): Organization
    {
        return (new Organization())
            ->setName($row['name'])
            ->setPresentation($row['presentation'])
            ->setCreatedAt(new \DateTimeImmutable($row['createdAt']))
            ;
    }
}
