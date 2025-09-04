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
    public ?string $askedSalary = null;
    public ?\DateTime $creationDate = null;
    public ?\DateTime $applicationDate = null;
    public array $actions = [];
}