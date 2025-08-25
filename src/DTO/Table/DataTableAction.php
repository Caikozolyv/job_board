<?php

declare(strict_types=1);

namespace App\DTO\Table;

use App\Entity\Action;

class DataTableAction implements DataTableInterface
{
    public function getNecessaryValues(
        /** @var Action[] $actions */
        array $actions
    ): array {
        $actionsValues = [];
        foreach ($actions as $action) {
            $actionsValues[] = [
                'id' => $action->getId(),
                'name' => $action->getName()
            ];
        }
        return $actionsValues;
    }

    public function getFieldsType(): array
    {
        return [
            'name' => [
                'type' => 'text',
                'content' => ''
            ]
        ];
    }
}