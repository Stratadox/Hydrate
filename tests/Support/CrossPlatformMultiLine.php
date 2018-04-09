<?php
declare(strict_types=1);

namespace Stratadox\Hydrate\Test\Support;

use function is_string as isString;
use function method_exists as canThe;
use SebastianBergmann\Comparator\Comparator;
use SebastianBergmann\Comparator\ScalarComparator;
use function str_replace as replace;

class CrossPlatformMultiLine extends Comparator
{
    const CONVERT_TO_STRING = '__toString';

    private $comparator;

    public function __construct(Comparator $comparator)
    {
        $this->comparator = $comparator;
        parent::__construct();
    }

    public static function strings(): self
    {
        return new CrossPlatformMultiLine(new ScalarComparator);
    }

    public function accepts($theOne, $theOther): bool
    {
        return $this->canAccept($theOne) && $this->canAccept($theOther);
    }

    private function canAccept($candidate): bool
    {
        return isString($candidate) || canThe($candidate, self::CONVERT_TO_STRING);
    }

    public function assertEquals(
        $expected,
        $actual,
        $delta = 0.0,
        $sort = false,
        $ignoreCase = false
    ) {
        $this->comparator->assertEquals(
            replace("\r\n", "\n", (string)$expected),
            replace("\r\n", "\n", (string)$actual),
            $delta,
            $sort,
            $ignoreCase
        );
    }
}
