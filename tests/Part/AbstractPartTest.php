<?php

declare(strict_types=1);

namespace Tests\TheIconic\NameParser\Part;

use PHPUnit\Framework\TestCase;
use TheIconic\NameParser\Part\AbstractPart;

class AbstractPartTest extends TestCase
{
    /**
     * make sure the placeholder normalize() method returns the original value
     */
    public function testNormalize(): void
    {
        $part = new class ('abc') extends AbstractPart {};
        $this->assertEquals('abc', $part->normalize());
    }

    /**
     * make sure we unwrap any parts during setValue() calls
     */
    public function testSetValueUnwraps(): void
    {
        $part = new class ('abc') extends AbstractPart {};
        $this->assertEquals('abc', $part->getValue());

        $part = new class ($part) extends AbstractPart {};
        $this->assertEquals('abc', $part->getValue());
    }
}
