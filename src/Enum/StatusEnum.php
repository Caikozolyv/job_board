<?php

declare(strict_types=1);

namespace App\Enum;

enum StatusEnum: int
{
    case Applied = 1;
    case Answered = 2;
    case Rejected = 3;
    case Meeting = 4;
    case Successful = 5;
}
