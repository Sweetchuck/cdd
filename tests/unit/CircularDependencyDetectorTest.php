<?php

declare(strict_types = 1);

namespace Sweetchuck\cdd\Tests\Unit;

use Codeception\Test\Unit;
use Sweetchuck\cdd\Tests\UnitTester;
use Sweetchuck\cdd\CircularDependencyDetector;

class CircularDependencyDetectorTest extends Unit
{

    protected UnitTester $tester;

    /**
     * @return array<string, mixed>
     */
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
     * @param array<string, array<string>> $expected
     * @param array<string, array<string>> $items
     *
     * @dataProvider casesDetect
     */
    public function testDetect(array $expected, array $items, ?string $itemIdSeparator = null): void
    {
        $detector = new CircularDependencyDetector();
        if ($itemIdSeparator !== null) {
            $detector->setItemIdSeparator($itemIdSeparator);
        }

        $this->tester->assertSame($expected, $detector->detect($items));
    }
}
