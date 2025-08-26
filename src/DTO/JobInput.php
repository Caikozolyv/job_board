<?php

declare(strict_types=1);

namespace App\DTO;

class JobInput
{
    public string $name;
    public string $company;
    public string $url;
    public string $city;
    public int $presence;
    public int $website;
    public ?string $salary = null;
    public ?string $asked_salary = null;
    public ?\DateTime $creation_date = null;
    public ?\DateTime $application_date = null;
    public array $actions_to_take = [];
}