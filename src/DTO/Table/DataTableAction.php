<?php

declare(strict_types=1);

namespace App\DTO\Table;

use App\Entity\Action;

class DataTableAction implements DataTableInterface
{
    private const COLS = ['name'];
    private const OBJECT = 'actions';

    public function getNecessaryValues(
        /** @var Action[] $actions */
        array $actions
    ): array {
        $actionsValues = [];
        foreach ($actions as $action) {
            $actionsValues[$action->getId()] = [$action->getAction()];
        }
        return $actionsValues;
    }

    public function getNecessaryColumns(): array
    {
        return self::COLS;
    }

    public function getTableName(): string
    {
        return self::OBJECT;
    }
}