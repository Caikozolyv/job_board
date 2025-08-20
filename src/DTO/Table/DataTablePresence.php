<?php

declare(strict_types=1);

namespace App\DTO\Table;

use App\Entity\Presence;

class DataTablePresence implements DataTableInterface
{
    private const COLS = ['name'];
    private const OBJECT = 'presences';

    public function getNecessaryValues(
        /** @var Presence[] $presences */
        array $presences
    ): array {
        $presencesValues = [];
        foreach ($presences as $presence) {
            $presencesValues[$presence->getId()] = [$presence->getPresence()];
        }
        return $presencesValues;
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