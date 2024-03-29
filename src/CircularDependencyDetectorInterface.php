<?php

declare(strict_types = 1);

namespace Sweetchuck\cdd;

interface CircularDependencyDetectorInterface
{
    public function getItemIdSeparator(): string;

    public function setItemIdSeparator(string $value): static;

    /**
     * @param array<string, array<string>> $items
     *
     * @return array<string, array<string>>
     */
    public function detect(array $items): array;
}
