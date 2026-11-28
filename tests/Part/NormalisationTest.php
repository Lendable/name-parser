<?php

declare(strict_types=1);

namespace Tests\TheIconic\NameParser\Part;

use PHPUnit\Framework\TestCase;
use TheIconic\NameParser\Part\Firstname;
use TheIconic\NameParser\Part\Lastname;

class NormalisationTest extends TestCase
{
    public function testCamelcasing(): void
    {
        $part = new Lastname('McDonald');
        $this->assertEquals('McDonald', $part->normalize());

        $part = new Lastname('übel');
        $this->assertEquals('Übel', $part->normalize());

        $part = new Firstname('Anne-Marie');
        $this->assertEquals('Anne-Marie', $part->normalize());

        $part = new Firstname('etna');
        $this->assertEquals('Etna', $part->normalize());

        $part = new Firstname('thái');
        $this->assertEquals('Thái', $part->normalize());

        $part = new Lastname('nguyễn');
        $this->assertEquals('Nguyễn', $part->normalize());
    }
}
