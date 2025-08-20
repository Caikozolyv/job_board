<?php

declare(strict_types=1);

namespace App\DTO\Table;

interface DataTableInterface
{
    public function getNecessaryValues(array $objects): array;
    public function getNecessaryColumns(): array;
    public function getTableName(): string;
}