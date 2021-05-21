<?php

declare(strict_types = 1);

namespace Sweetchuck\cdd\Tests\Unit;

use Sweetchuck\cdd\CircularDependencyDetector;
use PHPUnit\Framework\TestCase;

class CircularDependencyDetectorTest extends TestCase
{

    public function casesDetect(): array
    {
        return [
            'empty' => [
                [],
                [],
            ],
            'no dependencies - 1' => [
                [],
                [
                    'a' => [],
                ]
            ],
            'no dependencies - 2' => [
                [],
                [
                    'a' => [],
                    'b' => [],
                ]
            ],
            'no dependencies - 3' => [
                [],
                [
                    'a' => [],
                    'b' => [],
                    'c' => [],
                ]
            ],
            'dependencies - 1' => [
                [],
                [
                    'a' => [],
                    'b' => [],
                    'c' => ['a', 'b'],
                ]
            ],
            'dependencies - 2' => [
                [],
                [
                    'a' => [],
                    'b' => ['a'],
                    'c' => ['b'],
                ]
            ],
            'a|b' => [
                [
                    'a|b' => ['b', 'a', 'b'],
                ],
                [
                    'a' => ['b'],
                    'b' => ['a'],
                ]
            ],
            'a|b a|c' => [
                [
                    'a|b' => ['b', 'a', 'b'],
                    'a|c' => ['c', 'a', 'c'],
                ],
                [
                    'a' => ['b', 'c'],
                    'b' => ['a'],
                    'c' => ['a'],
                ]
            ],
            'a|b|c|d' => [
                [
                    'a|b|c|d' => ['d', 'a', 'b', 'c', 'd'],
                ],
                [
                    'a' => ['b'],
                    'b' => ['c'],
                    'c' => ['d'],
                    'd' => ['a'],
                ]
            ],
            'a:b:c:d' => [
                [
                    'a:b:c:d' => ['d', 'a', 'b', 'c', 'd'],
                ],
                [
                    'a' => ['b'],
                    'b' => ['c'],
                    'c' => ['d'],
                    'd' => ['a'],
                ],
                ':',
            ],
            'a|a' => [
                [
                    'a' => ['a', 'a'],
                ],
                [
                    'a' => ['a'],
                ],
            ],
        ];
    }

    /**
     * @dataProvider casesDetect
     */
    public function testDetect(array $expected, array $items, ?string $itemIdSeparator = null)
    {
        $detector = new CircularDependencyDetector();
        if ($itemIdSeparator !== null) {
            $detector->setItemIdSeparator($itemIdSeparator);
        }

        $this->assertEquals($expected, $detector->detect($items));
    }
}
