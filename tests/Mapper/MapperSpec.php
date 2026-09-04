<?php

declare(strict_types=1);

namespace Tests\TheIconic\NameParser\Mapper;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use TheIconic\NameParser\Mapper\AbstractMapper;
use TheIconic\NameParser\Part\AbstractPart;

/**
 * @phpstan-type NamePart string|AbstractPart
 * @phpstan-type Parts list<NamePart>
 * @phpstan-type MapperArguments array<int|string, int|bool>
 * @phpstan-type MapperCase array{
 *     input: Parts,
 *     expectation: Parts,
 *     arguments?: MapperArguments,
 *     0?: MapperArguments
 * }
 */
abstract class MapperSpec extends TestCase
{
    /**
     * @param Parts $input
     * @param Parts $expectation
     * @param MapperArguments $arguments
     */
    #[DataProvider('provider')]
    public function testMap(array $input, array $expectation, array $arguments = []): void
    {
        $mapper = ($this->getMapper(...))(...$arguments);

        $this->assertEquals($expectation, $mapper->map($input));
    }

    abstract protected function getMapper(): AbstractMapper;
}
