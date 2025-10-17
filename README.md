# Circular dependency detector

[![CircleCI](https://circleci.com/gh/Sweetchuck/cdd/tree/2.x.svg?style=svg)](https://circleci.com/gh/Sweetchuck/cdd/?branch=2.x)
[![codecov](https://codecov.io/gh/Sweetchuck/cdd/branch/2.x/graph/badge.svg?token=HSF16OGPyr)](https://app.codecov.io/gh/Sweetchuck/cdd/branch/2.x)


A lightweight PHP library for detecting circular dependencies in complex systems.
This library provides a simple and efficient API to identify loops and cycles within dependency chains,
making it an essential tool for maintaining clean architecture and preventing infinite loops in your applications.


## When to Use

This library is ideal for projects that need to:

- **Validate dependency graphs** - Ensure there are no circular references in your dependency structures
- **Build system management** - Detect cycles in build task dependencies to prevent infinite loops
- **Module/package validation** - Verify that plugin systems or modular architectures don't have circular imports
- **Code analysis tools** - Power dependency analysis features in linters and static analysis tools
- **Architecture enforcement** - Maintain strict layered architecture by preventing unwanted circular dependencies
- **Plugin systems** - Validate plugin dependencies before loading them


## Features

- Zero runtime dependencies - lightweight and dependency-free
- Detects complex cycles - identifies loops of any depth
- Returns cycle paths - shows exactly which items form the cycle


## Usage

```php
<?php

declare(strict_types = 1);

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
