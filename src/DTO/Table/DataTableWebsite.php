<?php

declare(strict_types=1);

namespace App\DTO\Table;

use App\Entity\Website;

class DataTableWebsite implements DataTableInterface
{
    public function getNecessaryValues(
        /** @var Website[] $websites */
        array $websites
    ): array {
        $websitesValues = [];
        foreach ($websites as $website) {
            $websitesValues[] =
                [
                    'id' => $website->getId(),
                    'name' => $website->getName()
                ];
        }
        return $websitesValues;
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