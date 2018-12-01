
# Circular dependency detector

[![CircleCI](https://circleci.com/gh/Sweetchuck/cdd.svg?style=svg)](https://circleci.com/gh/Sweetchuck/cdd)
[![codecov](https://codecov.io/gh/Sweetchuck/cdd/branch/master/graph/badge.svg)](https://codecov.io/gh/Sweetchuck/cdd)

## Install

    composer require sweetchuck/cdd


## Usage

```php
<?php

use Sweetchuck\cdd\CircularDependencyDetector;

$detector = new CircularDependencyDetector();

$items = [
    // Item "a" has no any dependencies.
    'a' => [],

    // Item "b" depends on "c" and "d".
    'b' => ['c', 'd'],

    // Item "c" has no any dependencies.
    'c' => [],

    // Item "d" has no any dependencies.
    'd' => [],
];

$loops = $detector->detect($items);

/**
 * $loops = [];
 */
var_dump($loops);

$items = [
    // Item "a" depends on "b".
    'a' => ['b'],

    // Item "b" depends on "a".
    'b' => ['a'],
];

$loops = $detector->detect($items);

/**
 * $loops = [
 *   'a|b' => ['b', 'a', 'b'],
 * ];
 */
var_dump($loops);

$items = [
    // Item "a" depends on "b".
    'a' => ['b'],

    // Item "b" depends on "c".
    'b' => ['c'],

    // Item "c" depends on "a".
    'c' => ['a'],
];

$loops = $detector->detect($items);

/**
 * $loops = [
 *   'a|b|c' => ['c', 'a', 'b', 'c'],
 * ];
 */
var_dump($loops);
```
