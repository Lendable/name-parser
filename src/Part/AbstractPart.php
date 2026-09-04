<?php

declare(strict_types=1);

namespace TheIconic\NameParser\Part;

abstract class AbstractPart
{
    /**
     * @var string the wrapped value
     */
    protected string $value;

    /**
     * constructor allows passing the value to wrap
     *
     * @param string|self $value
     */
    public function __construct($value)
    {
        $this->setValue($value);
    }

    /**
     * set the value to wrap
     * (can take string or part instance)
     */
    public function setValue(string|self $value): self
    {
        if ($value instanceof self) {
            $value = $value->getValue();
        }

        $this->value = $value;

        return $this;
    }

    /**
     * get the wrapped value
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * get the normalized value
     */
    public function normalize(): string
    {
        return $this->getValue();
    }

    /**
     * helper for camelization of values
     * to be used during normalize
     *
     * @param string $word
     */
    protected function camelcase($word): string
    {
        if (\preg_match('/\p{L}(\p{Lu}*\p{Ll}\p{Ll}*\p{Lu}|\p{Ll}*\p{Lu}\p{Lu}*\p{Ll})\p{L}*/u', $word) === 1) {
            return $word;
        }

        $camelcased = \preg_replace_callback('/[\p{L}0-9]+/ui', $this->camelcaseReplace(...), $word);

        if ($camelcased === null) {
            throw new \RuntimeException('Failed to camelcase name part: '.\preg_last_error_msg());
        }

        return $camelcased;
    }

    /**
     * camelcasing callback
     *
     * @param array<string> $matches
     */
    protected function camelcaseReplace($matches): string
    {
        if (\function_exists('mb_convert_case')) {
            return \mb_convert_case($matches[0], \MB_CASE_TITLE, 'UTF-8');
        }

        return \ucfirst(\strtolower($matches[0]));
    }
}
