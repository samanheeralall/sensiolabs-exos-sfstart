<?php

namespace App\Factory;

use App\Entity\Conference;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Conference>
 */
final class ConferenceFactory extends PersistentObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    #[\Override]
    public static function class(): string
    {
        return Conference::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        $startAt = \DateTimeImmutable::createFromMutable(
            self::faker()->dateTimeBetween('now', '+6 months')
        );

        return [
            'name' => self::faker()->sentence(3),
            'description' => self::faker()->paragraph(),
            'accessible' => self::faker()->boolean(80),
            'startAt' => $startAt,
            'endAt' => $startAt->modify('+'.self::faker()->numberBetween(1, 3).' days'),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Conference $conference): void {})
        ;
    }
}
