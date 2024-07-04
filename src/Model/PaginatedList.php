<?php

namespace App\Model;

use Symfony\Component\Serializer\Attribute\Groups;

class PaginatedList
{
    #[Groups(['list'])]
    private ?int $previousOffset = null;

    #[Groups(['list'])]
    private ?int $nextOffset = null;

    #[Groups(['list'])]
    private array $items = [];

    public function getPreviousOffset(): ?int
    {
        return $this->previousOffset;
    }

    public function setPreviousOffset(?int $previousOffset): void
    {
        $this->previousOffset = $previousOffset;
    }

    public function getNextOffset(): ?int
    {
        return $this->nextOffset;
    }

    public function setNextOffset(?int $nextOffset): void
    {
        $this->nextOffset = $nextOffset;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(array $items): void
    {
        $this->items = $items;
    }
}
