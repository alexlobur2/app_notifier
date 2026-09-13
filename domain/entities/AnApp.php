<?php
declare(strict_types=1);


final readonly class AnApp {
    public function __construct(
        public string            $applicationId,
        public string            $token,
        public bool              $enabled,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt,
    ) {}

}
