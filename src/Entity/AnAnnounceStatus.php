<?php
declare(strict_types=1);

enum AnAnnounceStatus: string {
    case DRAFT = 'draft';
    case LIVE = 'live';
    case ARCHIVED = 'archived';
}
