<?php
declare(strict_types=1);


/**
 *  Статусы анонсов
 */
enum AnAnnounceStatus: string {
    case DRAFT = 'draft';
    case LIVE = 'live';
    case ARCHIVED = 'archived';
}


