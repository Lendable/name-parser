<?php

declare(strict_types=1);

namespace Tests\TheIconic\NameParser;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use TheIconic\NameParser\Language\German;
use TheIconic\NameParser\Name;
use TheIconic\NameParser\Parser;

class GermanParserTest extends TestCase
{
    public static function provider(): array
    {
        return [
            [
                'Herr Schmidt',
                [
                    'salutation' => 'Herr',
                    'lastname' => 'Schmidt',
                ],
            ],
            [
                'Frau Maria Lange',
                [
                    'salutation' => 'Frau',
                    'firstname' => 'Maria',
                    'lastname' => 'Lange',
                ],
            ],
            [
                'Hr. Juergen von der Lippe',
                [
                    'salutation' => 'Herr',
                    'firstname' => 'Juergen',
                    'lastname' => 'von der Lippe',
                ],
            ],
            [
                'Fr. Charlotte von Stein',
                [
                    'salutation' => 'Frau',
                    'firstname' => 'Charlotte',
                    'lastname' => 'von Stein',
                ],
            ],
        ];
    }

    #[DataProvider('provider')]
    public function testParse($input, $expectation): void
    {
        $parser = new Parser([
            new German(),
        ]);
        $name = $parser->parse($input);

        $this->assertInstanceOf(Name::class, $name);
        $this->assertEquals($expectation, $name->getAll());
    }
}
