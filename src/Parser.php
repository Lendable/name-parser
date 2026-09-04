<?php

declare(strict_types=1);

namespace TheIconic\NameParser;

use TheIconic\NameParser\Language\English;
use TheIconic\NameParser\Mapper\FirstnameMapper;
use TheIconic\NameParser\Mapper\InitialMapper;
use TheIconic\NameParser\Mapper\LastnameMapper;
use TheIconic\NameParser\Mapper\MiddlenameMapper;
use TheIconic\NameParser\Mapper\NicknameMapper;
use TheIconic\NameParser\Mapper\SalutationMapper;
use TheIconic\NameParser\Mapper\SuffixMapper;

class Parser
{
    protected string $whitespace = " \r\n\t";

    /** @var list<Mapper\AbstractMapper> */
    protected array $mappers = [];

    /** @var list<LanguageInterface> */
    protected array $languages = [];

    /** @var array<string, string> */
    protected array $nicknameDelimiters = [];

    protected int $maxSalutationIndex = 0;

    protected int $maxCombinedInitials = 2;

    /** @param list<LanguageInterface> $languages */
    public function __construct(array $languages = [])
    {
        if ($languages === []) {
            $languages = [new English()];
        }

        $this->languages = $languages;
    }

    /**
     * split full names into the following parts:
     * - prefix / salutation  (Mr., Mrs., etc)
     * - given name / first name
     * - middle initials
     * - surname / last name
     * - suffix (II, Phd, Jr, etc)
     */
    public function parse(string $name): Name
    {
        $name = $this->normalize($name);

        $segments = \explode(',', $name);

        if (\count($segments) > 1) {
            return $this->parseSplitName($segments[0], $segments[1], $segments[2] ?? '');
        }

        $parts = \explode(' ', $name);

        foreach ($this->getMappers() as $mapper) {
            $parts = $mapper->map($parts);
        }

        return new Name($parts);
    }

    /**
     * handles split-parsing of comma-separated name parts
     *
     * @param string $first
     * @param string $second
     * @param string $third
     */
    protected function parseSplitName($first, $second, $third): Name
    {
        $parts = \array_merge(
            $this->getFirstSegmentParser()->parse($first)->getParts(),
            $this->getSecondSegmentParser()->parse($second)->getParts(),
            $this->getThirdSegmentParser()->parse($third)->getParts(),
        );

        return new Name($parts);
    }

    protected function getFirstSegmentParser(): self
    {
        $parser = new Parser();

        $parser->setMappers([
            new SalutationMapper($this->getSalutations(), $this->getMaxSalutationIndex()),
            new SuffixMapper($this->getSuffixes(), false, 2),
            new LastnameMapper($this->getPrefixes(), true),
            new FirstnameMapper(),
            new MiddlenameMapper(),
        ]);

        return $parser;
    }

    protected function getSecondSegmentParser(): self
    {
        $parser = new Parser();

        $parser->setMappers([
            new SalutationMapper($this->getSalutations(), $this->getMaxSalutationIndex()),
            new SuffixMapper($this->getSuffixes(), true, 1),
            new NicknameMapper($this->getNicknameDelimiters()),
            new InitialMapper($this->getMaxCombinedInitials(), true),
            new FirstnameMapper(),
            new MiddlenameMapper(true),
        ]);

        return $parser;
    }

    protected function getThirdSegmentParser(): self
    {
        $parser = new Parser();

        $parser->setMappers([
            new SuffixMapper($this->getSuffixes(), true, 0),
        ]);

        return $parser;
    }

    /**
     * get the mappers for this parser
     *
     * @return list<Mapper\AbstractMapper>
     */
    public function getMappers(): array
    {
        if ($this->mappers === []) {
            $this->setMappers([
                new NicknameMapper($this->getNicknameDelimiters()),
                new SalutationMapper($this->getSalutations(), $this->getMaxSalutationIndex()),
                new SuffixMapper($this->getSuffixes()),
                new InitialMapper($this->getMaxCombinedInitials()),
                new LastnameMapper($this->getPrefixes()),
                new FirstnameMapper(),
                new MiddlenameMapper(),
            ]);
        }

        return $this->mappers;
    }

    /**
     * set the mappers for this parser
     *
     * @param list<Mapper\AbstractMapper> $mappers
     */
    public function setMappers(array $mappers): Parser
    {
        $this->mappers = $mappers;

        return $this;
    }

    /**
     * normalize the name
     */
    protected function normalize(string $name): string
    {
        $whitespace = $this->getWhitespace();

        $name = \trim($name);

        $normalized = \preg_replace('/['.\preg_quote($whitespace, '/').']+/', ' ', $name);

        if ($normalized === null) {
            throw new \RuntimeException('Failed to normalize name: '.\preg_last_error_msg());
        }

        return $normalized;
    }

    /**
     * get a string of characters that are supposed to be treated as whitespace
     */
    public function getWhitespace(): string
    {
        return $this->whitespace;
    }

    /**
     * set the string of characters that are supposed to be treated as whitespace
     *
     * @param string $whitespace
     */
    public function setWhitespace($whitespace): self
    {
        $this->whitespace = $whitespace;

        return $this;
    }

    /** @return array<string, string> */
    protected function getPrefixes(): array
    {
        $prefixes = [];

        /** @var LanguageInterface $language */
        foreach ($this->languages as $language) {
            $prefixes += $language->getLastnamePrefixes();
        }

        return $prefixes;
    }

    /** @return array<string, string> */
    protected function getSuffixes(): array
    {
        $suffixes = [];

        /** @var LanguageInterface $language */
        foreach ($this->languages as $language) {
            $suffixes += $language->getSuffixes();
        }

        return $suffixes;
    }

    /** @return array<string, string> */
    protected function getSalutations(): array
    {
        $salutations = [];

        /** @var LanguageInterface $language */
        foreach ($this->languages as $language) {
            $salutations += $language->getSalutations();
        }

        return $salutations;
    }

    /** @return array<string, string> */
    public function getNicknameDelimiters(): array
    {
        return $this->nicknameDelimiters;
    }

    /** @param array<string, string> $nicknameDelimiters */
    public function setNicknameDelimiters(array $nicknameDelimiters): Parser
    {
        $this->nicknameDelimiters = $nicknameDelimiters;

        return $this;
    }

    public function getMaxSalutationIndex(): int
    {
        return $this->maxSalutationIndex;
    }

    public function setMaxSalutationIndex(int $maxSalutationIndex): self
    {
        $this->maxSalutationIndex = $maxSalutationIndex;

        return $this;
    }

    public function getMaxCombinedInitials(): int
    {
        return $this->maxCombinedInitials;
    }

    public function setMaxCombinedInitials(int $maxCombinedInitials): self
    {
        $this->maxCombinedInitials = $maxCombinedInitials;

        return $this;
    }
}
