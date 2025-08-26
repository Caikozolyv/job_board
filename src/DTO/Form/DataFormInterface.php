<?php

declare(strict_types=1);

namespace App\DTO\Form;

interface DataFormInterface
{
    public function getFieldsType(): array;
}