<?php

declare(strict_types=1);

namespace App\DTO\Form;

class DataFormDefault
{
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