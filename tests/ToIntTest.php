<?php

namespace theodorejb\polycast;

use PHPUnit\Framework\TestCase;

class ToIntTest extends TestCase
{
    /**
     * @return list<array{0: int, 1: int|float|string}>
     */
    public function shouldPass(): array
    {
        return [
            [0, "0"],
            [0, 0],
            [0, 0.0],
            [10, "10"],
            [10, "+10"],
            [-10, "-10"],
            [10, 10],
            [10, 10.0],
            [PHP_INT_MAX, (string)PHP_INT_MAX],
            [PHP_INT_MAX, PHP_INT_MAX],
            [PHP_INT_MIN, (string)PHP_INT_MIN],
            [PHP_INT_MIN, PHP_INT_MIN],
        ];
    }

    /**
     * @dataProvider shouldPass
     * @param mixed $val
     */
    public function testShouldPass(int $expected, $val): void
    {
        $this->assertTrue(safe_int($val));
        $this->assertSame($expected, to_int($val));
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
            [new \stdClass()],
            [new NotAnInt()], // FILTER_VALIDATE_INT accepts this
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
        $this->assertFalse(safe_int($val));
        $this->expectException(CastException::class);
        to_int($val);
    }

    /**
     * @return list<array{0: string}>
     */
    public function invalidFormats(): array
    {
        return [
            [""],
            ["10.0"],
            ["75e-5"],
            ["31e+7"],
            ["0x10"],
            ["1.5"],
            ["010"],
            ["10abc"],
            ["abc10"],
            ["   100    "], // FILTER_VALIDATE_INT accepts this
            ["\n\t\v\r\f   78 \n \t\v\r\f   \n"],
            ["\n\t\v\r\f78"],
            ["78\n\t\v\r\f"],
        ];
    }

    /**
     * @dataProvider invalidFormats
     */
    public function testInvalidFormats(string $val): void
    {
        $this->assertFalse(safe_int($val));
        $this->expectException(CastException::class);
        to_int($val);
    }

    /**
     * @return list<array{0: float}>
     */
    public function unsafeValues(): array
    {
        return [
            [1.000000000000001], // FILTER_VALIDATE_INT accepts this
            [NAN],
            [1.5],
        ];
    }

    /**
     * @dataProvider unsafeValues
     */
    public function testUnsafeValues(float $val): void
    {
        $this->assertFalse(safe_int($val));
        $this->expectException(CastException::class);
        to_int($val);
    }

    /**
     * @return list<array{0: float|string}>
     */
    public function overflowValues(): array
    {
        return [
            [INF],
            [-INF],
            [PHP_INT_MAX * 2],
            [PHP_INT_MIN * 2],
            [(string)(PHP_INT_MAX * 2)],
            [(string)(PHP_INT_MIN * 2)],
        ];
    }

    /**
     * @dataProvider overflowValues
     * @param float|string $val
     */
    public function testOverflowValues($val): void
    {
        $this->assertFalse(safe_int($val));
        $this->expectException(CastException::class);
        to_int($val);
    }
}

class NotAnInt
{
    function __toString(): string
    {
        return "1";
    }
}
