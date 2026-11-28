<?php

declare(strict_types=1);

namespace Tests\TheIconic\NameParser\Mapper;

use TheIconic\NameParser\Language\English;
use TheIconic\NameParser\Mapper\SuffixMapper;
use TheIconic\NameParser\Part\Firstname;
use TheIconic\NameParser\Part\Lastname;
use TheIconic\NameParser\Part\Suffix;

/** @phpstan-import-type MapperCase from MapperSpec */
class SuffixMapperTest extends MapperSpec
{
    /** @return list<MapperCase> */
    public static function provider(): array
    {
        return [
            [
                'input' => [
                    'Mr.',
                    'James',
                    'Blueberg',
                    'PhD',
                ],
                'expectation' => [
                    'Mr.',
                    'James',
                    'Blueberg',
                    new Suffix('PhD'),
                ],
                'arguments' => [
                    'matchSinglePart' => false,
                    'reservedParts' => 2,
                ],
            ],
            [
                'input' => [
                    'Prince',
                    'Alfred',
                    'III',
                ],
                'expectation' => [
                    'Prince',
                    'Alfred',
                    new Suffix('III'),
                ],
                'arguments' => [
                    'matchSinglePart' => false,
                    'reservedParts' => 2,
                ],
            ],
            [
                'input' => [
                    new Firstname('Paul'),
                    new Lastname('Smith'),
                    'Senior',
                ],
                'expectation' => [
                    new Firstname('Paul'),
                    new Lastname('Smith'),
                    new Suffix('Senior'),
                ],
                'arguments' => [
                    'matchSinglePart' => false,
                    'reservedParts' => 2,
                ],
            ],
            [
                'input' => [
                    'Senior',
                    new Firstname('James'),
                    'Norrington',
                ],
                'expectation' => [
                    'Senior',
                    new Firstname('James'),
                    'Norrington',
                ],
                'arguments' => [
                    'matchSinglePart' => false,
                    'reservedParts' => 2,
                ],
            ],
            [
                'input' => [
                    'Senior',
                    new Firstname('James'),
                    new Lastname('Norrington'),
                ],
                'expectation' => [
                    'Senior',
                    new Firstname('James'),
                    new Lastname('Norrington'),
                ],
                'arguments' => [
                    'matchSinglePart' => false,
                    'reservedParts' => 2,
                ],
            ],
            [
                'input' => [
                    'James',
                    'Norrington',
                    'Senior',
                ],
                'expectation' => [
                    'James',
                    'Norrington',
                    new Suffix('Senior'),
                ],
                'arguments' => [
                    false,
                    2,
                ],
            ],
            [
                'input' => [
                    'Norrington',
                    'Senior',
                ],
                'expectation' => [
                    'Norrington',
                    'Senior',
                ],
                'arguments' => [
                    false,
                    2,
                ],
            ],
            [
                'input' => [
                    new Lastname('Norrington'),
                    'Senior',
                ],
                'expectation' => [
                    new Lastname('Norrington'),
                    new Suffix('Senior'),
                ],
                'arguments' => [
                    false,
                    1,
                ],
            ],
            [
                'input' => [
                    'Senior',
                ],
                'expectation' => [
                    new Suffix('Senior'),
                ],
                'arguments' => [
                    true,
                ],
            ],
        ];
    }

    protected function getMapper(bool $matchSinglePart = false, int $reservedParts = 2): SuffixMapper
    {
        $english = new English();

        return new SuffixMapper($english->getSuffixes(), $matchSinglePart, $reservedParts);
    }
}
