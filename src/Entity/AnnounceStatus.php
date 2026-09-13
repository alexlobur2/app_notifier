<?php

declare(strict_types=1);

namespace App\Notifier\Entity;

enum AnnounceStatus: string
{
    case DRAFT = 'draft';
    case LIVE = 'live';
    case ARCHIVED = 'archived';
}
