<?php

declare(strict_types=1);

namespace TheIconic\NameParser\Mapper;

use TheIconic\NameParser\Part\AbstractPart;
use TheIconic\NameParser\Part\Initial;

/**
 * single letter, possibly followed by a period
 */
class InitialMapper extends AbstractMapper
{
    public function __construct(
        private readonly int $combinedMax = 2,
        protected bool $matchLastPart = false,
    ) {}

    /**
     * map intials in parts array
     *
     * @param array $parts the name parts
     * @return array the mapped parts
     */
    public function map(array $parts): array
    {
        $last = \count($parts) - 1;
        $counter = \count($parts);

        for ($k = 0; $k < $counter; $k++) {
            $part = $parts[$k];

            if ($part instanceof AbstractPart) {
                continue;
            }

            if (!$this->matchLastPart && $k === $last) {
                continue;
            }

            if (\strtoupper((string) $part) === $part) {
                $stripped = \str_replace('.', '', $part);
                $length = \strlen($stripped);

                if ($length > 1 && $length <= $this->combinedMax) {
                    \array_splice($parts, $k, 1, \str_split($stripped));
                    $counter = \count($parts);
                    $last = \count($parts) - 1;
                    $part = $parts[$k];
                }
            }

            if ($this->isInitial($part)) {
                $parts[$k] = new Initial($part);
            }
        }

        return $parts;
    }

    protected function isInitial(string $part): bool
    {
        $length = \strlen($part);

        if ($length === 1) {
            return true;
        }

        return $length === 2 && \str_ends_with($part, '.');
    }
}
