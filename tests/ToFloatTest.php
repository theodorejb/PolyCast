<?php

namespace theodorejb\polycast;

use PHPUnit\Framework\TestCase;

class ToFloatTest extends TestCase
{
    /**
     * @return list<array{0: float, 1: int|float|string}>
     */
    public function shouldPass(): array
    {
        return [
            [0.0, "0"],
            [0.0, 0],
            [0.0, "0.0"],
            [0.0, 0.0],
            [10.0, "10"],
            [10.0, "+10"],
            [-10.0, "-10"],
            [10.0, "10.0"],
            [10.0, 10],
            [10.0, 10.0],
            [1.5, 1.5],
            [1.5, "1.5"],
            [0.00075, "75e-5"],
            [310000000.0, "31e+7"],
            [(float)PHP_INT_MAX, (string)PHP_INT_MAX],
            [(float)PHP_INT_MAX, PHP_INT_MAX],
            [(float)PHP_INT_MAX, (float)PHP_INT_MAX],
            [(float)PHP_INT_MIN, (string)PHP_INT_MIN],
            [(float)PHP_INT_MIN, PHP_INT_MIN],
            [(float)PHP_INT_MIN, (float)PHP_INT_MIN],
        ];
    }

    /**
     * @dataProvider shouldPass
     * @param mixed $val
     */
    public function testShouldPass(float $expected, $val): void
    {
        $this->assertTrue(safe_float($val));
        $this->assertSame($expected, to_float($val));
    }

    public function testOverflowNanInf(): void
    {
        $this->assertSame(INF, to_float(INF));
        $this->assertSame(-INF, to_float(-INF));
        $this->assertTrue(is_nan(to_float(NAN)));
        $this->assertSame((float)(PHP_INT_MAX * 2), to_float(PHP_INT_MAX * 2));
        $this->assertSame((float)(PHP_INT_MIN * 2), to_float(PHP_INT_MIN * 2));
        $this->assertSame((string)(float)(PHP_INT_MAX * 2), (string)to_float((string)(PHP_INT_MAX * 2)));
        $this->assertSame((string)(float)(PHP_INT_MIN * 2), (string)to_float((string)(PHP_INT_MIN * 2)));
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
            [new NotAFloat()], // FILTER_VALIDATE_FLOAT accepts this
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
        $this->assertFalse(safe_float($val));
        $this->expectException(CastException::class);
        to_float($val);
    }

    /**
     * @return list<array{0: string}>
     */
    public function invalidFormats(): array
    {
        return [
            [""],
            ["0x10"],
            ["010"],
            ["10abc"],
            ["abc10"],
            ["   100    "], // FILTER_VALIDATE_FLOAT accepts this
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
        $this->assertFalse(safe_float($val));
        $this->expectException(CastException::class);
        to_float($val);
    }
}

class NotAFloat
{
    function __toString(): string
    {
        return "1.0";
    }
}
