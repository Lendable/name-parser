<?php

declare(strict_types=1);

namespace TheIconic\NameParser\Mapper;

use TheIconic\NameParser\Part\AbstractPart;
use TheIconic\NameParser\Part\Nickname;

class NicknameMapper extends AbstractMapper
{
    /**
     * @var array
     */
    protected $delimiters = [
        '[' => ']',
        '{' => '}',
        '(' => ')',
        '<' => '>',
        '"' => '"',
        '\'' => '\'',
    ];

    public function __construct(array $delimiters = [])
    {
        if ($delimiters !== []) {
            $this->delimiters = $delimiters;
        }
    }

    /**
     * map nicknames in the parts array
     *
     * @param array $parts the name parts
     * @return array the mapped parts
     */
    public function map(array $parts): array
    {
        $isEncapsulated = false;

        $regexp = $this->buildRegexp();

        $closingDelimiter = '';

        foreach ($parts as $k => $part) {
            if ($part instanceof AbstractPart) {
                continue;
            }

            if (\preg_match($regexp, (string) $part, $matches)) {
                $isEncapsulated = true;
                $part = \substr((string) $part, 1);
                $closingDelimiter = $this->delimiters[$matches[1]];
            }

            if (!$isEncapsulated) {
                continue;
            }

            if ($closingDelimiter === \substr((string) $part, -1)) {
                $isEncapsulated = false;
                $part = \substr((string) $part, 0, -1);
            }

            $parts[$k] = new Nickname(\str_replace(['"', '\''], '', $part));
        }

        return $parts;
    }

    /**
     * @return string
     */
    protected function buildRegexp()
    {
        $regexp = '/^([';

        foreach ($this->delimiters as $opening => $closing) {
            $regexp .= \sprintf('\\%s', $opening);
        }

        $regexp .= '])/';

        return $regexp;
    }
}
