<?php

declare(strict_types=1);

namespace TheIconic\NameParser\Mapper;

use TheIconic\NameParser\Part\AbstractPart;
use TheIconic\NameParser\Part\Salutation;

class SalutationMapper extends AbstractMapper
{
    /** @var array<string, string> */
    protected $salutations = [];

    /**
     * @param array<string, string> $salutations
     * @param int $maxIndex
     */
    public function __construct(array $salutations, protected $maxIndex = 0)
    {
        $this->salutations = $salutations;
    }

    /**
     * map salutations in the parts array
     *
     * @param array<int, string|AbstractPart> $parts the name parts
     * @return array<int, string|AbstractPart> the mapped parts
     */
    public function map(array $parts): array
    {
        $max = ($this->maxIndex > 0) ? $this->maxIndex : \floor(\count($parts) / 2);

        for ($k = 0; $k < $max; $k++) {
            if ($parts[$k] instanceof AbstractPart) {
                break;
            }

            $parts = $this->substituteWithSalutation($parts, $k);
        }

        return $parts;
    }

    /**
     * We pass the full parts array and the current position to allow
     * not only single-word matches but also combined matches with
     * subsequent words (parts).
     *
     * @param array<int, string|AbstractPart> $parts
     * @return array<int, string|AbstractPart>
     */
    protected function substituteWithSalutation(array $parts, int $start): array
    {
        /** @var string $part */
        $part = $parts[$start];

        if ($this->isSalutation($part)) {
            $parts[$start] = new Salutation($part, $this->salutations[$this->getKey($part)]);

            return $parts;
        }

        foreach ($this->salutations as $key => $salutation) {
            $keys = \explode(' ', $key);
            $length = \count($keys);

            $subset = \array_slice($parts, $start, $length);

            if ($this->isMatchingSubset($keys, $subset)) {
                \array_splice($parts, $start, $length, [new Salutation(\implode(' ', $subset), $salutation)]);

                return $parts;
            }
        }

        return $parts;
    }

    /**
     * check if the given subset matches the given keys entry by entry,
     * which means word by word, except that we first need to key-ify
     * the subset words
     *
     * @param list<string> $keys
     * @param array<int, string|AbstractPart> $subset
     * @phpstan-assert-if-true =list<string> $subset
     */
    private function isMatchingSubset(array $keys, array $subset): bool
    {
        $counter = \count($subset);
        for ($i = 0; $i < $counter; $i++) {
            /** @var string $part */
            $part = $subset[$i];

            if ($this->getKey($part) !== $keys[$i]) {
                return false;
            }
        }

        return true;
    }

    /**
     * check if the given word is a viable salutation
     *
     * @param string $word the word to check
     */
    protected function isSalutation($word): bool
    {
        return \array_key_exists($this->getKey($word), $this->salutations);
    }
}
