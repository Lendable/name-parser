<?php

declare(strict_types=1);

namespace TheIconic\NameParser\Mapper;

use TheIconic\NameParser\Part\AbstractPart;
use TheIconic\NameParser\Part\Suffix;

class SuffixMapper extends AbstractMapper
{
    /** @var array<string, string> */
    protected $suffixes = [];

    /** @var bool */
    protected $matchSinglePart = false;

    /** @var int */
    protected $reservedParts = 2;

    /** @param array<string, string> $suffixes */
    public function __construct(array $suffixes, bool $matchSinglePart = false, int $reservedParts = 2)
    {
        $this->suffixes = $suffixes;
        $this->matchSinglePart = $matchSinglePart;
        $this->reservedParts = $reservedParts;
    }

    /**
     * map suffixes in the parts array
     *
     * @param array<int, string|AbstractPart> $parts the name parts
     * @return array<int, string|AbstractPart> the mapped parts
     */
    public function map(array $parts): array
    {
        if ($this->isMatchingSinglePart($parts)) {
            $parts[0] = new Suffix($parts[0], $this->suffixes[$this->getKey($parts[0])]);

            return $parts;
        }

        $start = \count($parts) - 1;

        for ($k = $start; $k > $this->reservedParts - 1; $k--) {
            $part = $parts[$k];

            if (!$this->isSuffix($part)) {
                break;
            }

            $parts[$k] = new Suffix($part, $this->suffixes[$this->getKey($part)]);
        }

        return $parts;
    }

    /**
     * @param array<int, string|AbstractPart> $parts
     * @phpstan-assert-if-true =array{0: string} $parts
     */
    protected function isMatchingSinglePart(array $parts): bool
    {
        if (!$this->matchSinglePart) {
            return false;
        }

        if (\count($parts) !== 1) {
            return false;
        }

        return $this->isSuffix($parts[0]);
    }

    /**
     * @param string|AbstractPart $part
     * @phpstan-assert-if-true =string $part
     */
    protected function isSuffix($part): bool
    {
        if ($part instanceof AbstractPart) {
            return false;
        }

        return \array_key_exists($this->getKey($part), $this->suffixes);
    }
}
