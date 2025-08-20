<?php

declare(strict_types=1);

namespace App\DTO\Table;

class TableDTO
{
    public function __construct(private DataTableInterface $columnsValues)
    { }

    public function mergeArrays(array $objects): array
    {
        $tableObject = $this->columnsValues->getTableName();
        $cols = $this->columnsValues->getNecessaryColumns();
        $values = $this->columnsValues->getNecessaryValues($objects);

        return ['object' => $tableObject] + ['cols' => $cols] + ['values' => $values];
    }
}