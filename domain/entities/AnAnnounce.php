<?php
declare(strict_types=1);


final readonly class AnAnnounce {

    public function __construct(
        public string             $uuid,
        public string             $applicationId,
        public string             $title,
        public string             $body,
        public ?DateTimeImmutable $startAt,
        public ?DateTimeImmutable $endAt,
        public AnAnnounceStatus   $status,
        public DateTimeImmutable  $createdAt,
        public DateTimeImmutable  $updatedAt,
    ) {}

}
