<?php

declare(strict_types = 1);

namespace Sweetchuck\cdd;

interface CircularDependencyDetectorInterface
{
    public function getItemIdSeparator(): string;

    /**
     * @return $this
     */
    public function setItemIdSeparator(string $value);

    public function detect(array $items): array;
}
