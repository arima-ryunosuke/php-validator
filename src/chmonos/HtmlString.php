<?php
namespace ryunosuke\chmonos;

class HtmlString implements \Stringable
{
    public function __construct(private string $string) { }

    public function __toString(): string
    {
        return $this->string;
    }
}
