<?php
declare(strict_types=1);


final readonly class AnDevice {
    public function __construct(
        public string            $applicationId,
        public string            $deviceFpt,
        public DateTimeImmutable $firstSeenAt,
        public DateTimeImmutable $lastSeenAt,
        public int               $requestCount,
    ) {}

}
