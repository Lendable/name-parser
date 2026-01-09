<?php

declare(strict_types=1);

namespace TheIconic\NameParser\Mapper;

use TheIconic\NameParser\Part\AbstractPart;

abstract class AbstractMapper
{
    /**
     * implements the mapping of parts
     *
     * @param array $parts - the name parts
     * @return array $parts - the mapped parts
     */
    abstract public function map(array $parts): array;

    /**
     * checks if there are still unmapped parts left before the given position
     */
    protected function hasUnmappedPartsBefore(array $parts, $index): bool
    {
        foreach ($parts as $k => $part) {
            if ($k === $index) {
                break;
            }

            if (!($part instanceof AbstractPart)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return int|bool
     */
    protected function findFirstMapped(string $type, array $parts)
    {
        $total = \count($parts);

        for ($i = 0; $i < $total; $i++) {
            if ($parts[$i] instanceof $type) {
                return $i;
            }
        }

        return false;
    }

    /**
     * get the registry lookup key for the given word
     *
     * @param string $word the word
     * @return string the key
     */
    protected function getKey($word): string
    {
        return \strtolower(\str_replace('.', '', $word));
    }
}
