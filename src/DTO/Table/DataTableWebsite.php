<?php

declare(strict_types=1);

namespace App\DTO\Table;

use App\Entity\Website;

class DataTableWebsite implements DataTableInterface
{
    private const COLS = ['name'];
    private const OBJECT = 'websites';

    public function getNecessaryValues(
        /** @var Website[] $websites */
        array $websites
    ): array {
        $websitesValues = [];
        foreach ($websites as $website) {
            $websitesValues[$website->getId()] = [$website->getName()];
        }
        return $websitesValues;
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