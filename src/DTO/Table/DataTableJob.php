<?php

declare(strict_types=1);

namespace App\DTO\Table;


use App\Entity\Job;

class DataTableJob implements DataTableInterface
{
    private const COLS = [
        'id', 'name', 'url', 'company', 'city',
        'presence', 'website', 'salary', 'asked salary',
        'publication date', 'application date', 'actions to take'
    ];
    private const OBJECT = 'jobs';

    public function getNecessaryValues(
        /** @var Job[] $jobs */
        array $jobs
    ): array {
        $jobsValues = [];
        foreach ($jobs as $job) {
            $jobsValues[$job->getId()] = [
                $job->getId(),
                $job->getName(),
                $job->getCompany(),
                $job->getUrl(),
                $job->getCity(),
                $job->getPresence()->getPresence(),
                $job->getWebsite()->getName(),
                $job->getSalary(),
                $job->getAskedSalary(),
                $job->getCreationDate(),
                $job->getApplicationDate(),
                $job->getActions()
            ];
        }
        return $jobsValues;
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