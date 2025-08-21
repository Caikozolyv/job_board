<?php

declare(strict_types=1);

namespace App\DTO\Table;

interface DataTableInterface
{
    public function getNecessaryValues(array $objects): array;
}