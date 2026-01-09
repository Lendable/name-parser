<?php

declare(strict_types=1);

namespace Tests\TheIconic\NameParser\Mapper;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

abstract class MapperSpec extends TestCase
{
    #[DataProvider('provider')]
    public function testMap($input, $expectation, $arguments = []): void
    {
        $mapper = \call_user_func_array($this->getMapper(...), $arguments);

        $this->assertEquals($expectation, $mapper->map($input));
    }

    abstract protected function getMapper();
}
