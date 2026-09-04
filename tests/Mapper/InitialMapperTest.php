<?php

declare(strict_types=1);

namespace Tests\TheIconic\NameParser\Mapper;

use TheIconic\NameParser\Mapper\InitialMapper;
use TheIconic\NameParser\Part\Initial;
use TheIconic\NameParser\Part\Lastname;
use TheIconic\NameParser\Part\Salutation;

/** @phpstan-import-type MapperCase from MapperSpec */
class InitialMapperTest extends MapperSpec
{
    /** @return list<MapperCase> */
    public static function provider(): array
    {
        return [
            [
                'input' => [
                    'A',
                    'B',
                ],
                'expectation' => [
                    new Initial('A'),
                    'B',
                ],
            ],
            [
                'input' => [
                    new Salutation('Mr'),
                    'P.',
                    'Pan',
                ],
                'expectation' => [
                    new Salutation('Mr'),
                    new Initial('P.'),
                    'Pan',
                ],
            ],
            [
                'input' => [
                    new Salutation('Mr'),
                    'Peter',
                    'D.',
                    new Lastname('Pan'),
                ],
                'expectation' => [
                    new Salutation('Mr'),
                    'Peter',
                    new Initial('D.'),
                    new Lastname('Pan'),
                ],
            ],
            [
                'input' => [
                    'James',
                    'B',
                ],
                'expectation' => [
                    'James',
                    'B',
                ],
            ],
            [
                'input' => [
                    'James',
                    'B',
                ],
                'expectation' => [
                    'James',
                    new Initial('B'),
                ],
                'arguments' => [
                    2,
                    true,
                ],
            ],
            [
                'input' => [
                    'JM',
                    'Walker',
                ],
                'expectation' => [
                    new Initial('J'),
                    new Initial('M'),
                    'Walker',
                ],
            ],
            [
                'input' => [
                    'JM',
                    'Walker',
                ],
                'expectation' => [
                    'JM',
                    'Walker',
                ],
                'arguments' => [
                    1,
                ],
            ],
        ];
    }

    protected function getMapper(int $maxCombined = 2, bool $matchLastPart = false): InitialMapper
    {
        return new InitialMapper($maxCombined, $matchLastPart);
    }
}
