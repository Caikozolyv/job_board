<?php

declare(strict_types=1);

namespace App\DTO\Table;


use App\Entity\Job;

class DataTableJob implements DataTableInterface
{
    public function getNecessaryValues(
        /** @var Job[] $jobs */
        array $jobs
    ): array {
        $jobsValues = [];
        foreach ($jobs as $job) {
            $jobsValues[] = [
                'id' => $job->getId(),
                'name' => $job->getName(),
                'company' => $job->getCompany(),
                'url' => $job->getUrl(),
                'city' => $job->getCity(),
                'presence' => $job->getPresence()->getPresence(),
                'website' => $job->getWebsite()->getName(),
                'salary' => $job->getSalary(),
                'asked_salary' => $job->getAskedSalary(),
                'creation_date' => $job->getCreationDate(),
                'application_date' => $job->getApplicationDate(),
                'actions_to_take' => $job->getActions()
            ];
        }
        return $jobsValues;
    }
}