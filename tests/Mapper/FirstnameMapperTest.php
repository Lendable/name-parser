<?php

declare(strict_types=1);

namespace Tests\TheIconic\NameParser\Mapper;

use TheIconic\NameParser\Mapper\FirstnameMapper;
use TheIconic\NameParser\Part\Firstname;
use TheIconic\NameParser\Part\Lastname;
use TheIconic\NameParser\Part\Salutation;

/** @phpstan-import-type MapperCase from MapperSpec */
class FirstnameMapperTest extends MapperSpec
{
    /** @return list<MapperCase> */
    public static function provider(): array
    {
        return [
            [
                'input' => [
                    'Peter',
                    'Pan',
                ],
                'expectation' => [
                    new Firstname('Peter'),
                    'Pan',
                ],
            ],
            [
                'input' => [
                    new Salutation('Mr'),
                    'Peter',
                    'Pan',
                ],
                'expectation' => [
                    new Salutation('Mr'),
                    new Firstname('Peter'),
                    'Pan',
                ],
            ],
            [
                'input' => [
                    new Salutation('Mr'),
                    'Peter',
                    new Lastname('Pan'),
                ],
                'expectation' => [
                    new Salutation('Mr'),
                    new Firstname('Peter'),
                    new Lastname('Pan'),
                ],
            ],
            [
                'input' => [
                    'Alfonso',
                    new Salutation('Mr'),
                ],
                'expectation' => [
                    new Firstname('Alfonso'),
                    new Salutation('Mr'),
                ],
            ],
        ];
    }

    protected function getMapper(): FirstnameMapper
    {
        return new FirstnameMapper();
    }
}
