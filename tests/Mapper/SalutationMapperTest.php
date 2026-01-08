<?php

declare(strict_types=1);

namespace Tests\TheIconic\NameParser\Mapper;

use TheIconic\NameParser\Language\English;
use TheIconic\NameParser\Mapper\SalutationMapper;
use TheIconic\NameParser\Part\Firstname;
use TheIconic\NameParser\Part\Salutation;

class SalutationMapperTest extends MapperSpec
{
    /**
     * @return array
     */
    public static function provider()
    {
        return [
            [
                'input' => [
                    'Mr.',
                    'Pan',
                ],
                'expectation' => [
                    new Salutation('Mr.', 'Mr.'),
                    'Pan',
                ],
            ],
            [
                'input' => [
                    'Mr',
                    'Peter',
                    'Pan',
                ],
                'expectation' => [
                    new Salutation('Mr', 'Mr.'),
                    'Peter',
                    'Pan',
                ],
            ],
            [
                'input' => [
                    'Mr',
                    new Firstname('James'),
                    'Miss',
                ],
                'expectation' => [
                    new Salutation('Mr', 'Mr.'),
                    new Firstname('James'),
                    'Miss',
                ],
            ],
        ];
    }

    protected function getMapper()
    {
        $english = new English();

        return new SalutationMapper($english->getSalutations());
    }
}
