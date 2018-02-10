<?php

namespace Sweetchuck\cdd;

class CircularDependencyDetector implements CircularDependencyDetectorInterface
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * @var string
     */
    protected $itemIdSeparator = '|';

    /**
     * {@inheritdoc}
     */
    public function getItemIdSeparator(): string
    {
        return $this->itemIdSeparator;
    }

    /**
     * {@inheritdoc}
     */
    public function setItemIdSeparator(string $value)
    {
        $this->itemIdSeparator = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function detect(array $items): array
    {
        $this->items = $items;

        return $this
            ->normalizeItems()
            ->expandParents()
            ->getCircularDependencies();
    }

    /**
     * @return $this
     */
    protected function normalizeItems()
    {
        foreach ($this->items as $itemId => $parents) {
            $this->items[$itemId] = array_fill_keys($parents, []);
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function expandParents()
    {
        foreach ($this->items as $childId => $parents) {
            if (!$parents) {
                continue;
            }

            foreach (array_keys($parents) as $parentItemId) {
                if (isset($this->items[$parentItemId])) {
                    $this->items[$childId][$parentItemId] =& $this->items[$parentItemId];
                }
            }
        }

        return $this;
    }

    protected function getCircularDependencies(): array
    {
        $itemIdSeparator = $this->getItemIdSeparator();
        $circularDependencies = [];
        foreach (array_keys($this->items) as $itemId) {
            $children = [$itemId];
            $parents = $this->detectCircularDependencies($children);
            if ($parents && reset($parents) === end($parents)) {
                $parentsOrdered = $parents;
                array_pop($parentsOrdered);
                sort($parentsOrdered);
                $parentsId = implode($itemIdSeparator, $parentsOrdered);
                $circularDependencies[$parentsId] = $parents;
            }
        }

        return $circularDependencies;
    }

    /**
     * Find any of the $children among their parents.
     *
     * @param string[] $children
     *   Lis of child item IDs.
     *
     * @return string[]
     *   List of item IDs which are point to each other.
     */
    protected function detectCircularDependencies(array $children): array
    {
        $childId = end($children);
        $parents = array_keys($this->items[$childId]);
        $intersection = array_intersect($children, $parents);
        if ($intersection) {
            return array_merge($children, $intersection);
        }

        foreach ($parents as $parent) {
            $childParents = $children;
            $childParents[] = $parent;
            $result = $this->detectCircularDependencies($childParents);
            if ($result) {
                return $result;
            }
        }

        return [];
    }
}
