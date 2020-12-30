<?php

namespace theodorejb\polycast;

use PHPUnit\Framework\TestCase;

class ToStringTest extends TestCase
{
    /**
     * @return list<array{0: string, 1: mixed}>
     */
    public function shouldPass(): array
    {
        return [
            ["foobar", "foobar"],
            ["123", 123],
            ["123.45", 123.45],
            ["INF", INF],
            ["-INF", -INF],
            ["NAN", NAN],
            ["", ""],
            ["foobar", new Stringable()],
        ];
    }

    /**
     * @dataProvider shouldPass
     * @param mixed $val
     */
    public function testShouldPass(string $expected, $val): void
    {
        $this->assertTrue(safe_string($val));
        $this->assertSame($expected, to_string($val));
    }

    /**
     * @return list<array{0: mixed}>
     */
    public function disallowedTypes(): array
    {
        return [
            [null],
            [true],
            [false],
            [fopen("data:text/html,foobar", "r")],
            [[]],
        ];
    }

    /**
     * @dataProvider disallowedTypes
     * @param mixed $val
     */
    public function testDisallowedTypes($val): void
    {
        $this->assertFalse(safe_string($val));
        $this->expectException(CastException::class);
        to_string($val);
    }

    /**
     * @return list<array{0: object}>
     */
    public function invalidObjects(): array
    {
        return [
            [new \stdClass()],
            [new NotStringable()],
        ];
    }

    /**
     * @dataProvider invalidObjects
     * @param object $val
     */
    public function testInvalidObjects($val): void
    {
        $this->assertFalse(safe_string($val));
        $this->expectException(CastException::class);
        to_string($val);
    }
}

class NotStringable {}

class Stringable
{
    public function __toString(): string
    {
        return "foobar";
    }
}
