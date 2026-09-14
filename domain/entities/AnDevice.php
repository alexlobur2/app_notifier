<?php
declare(strict_types=1);


/**
 *  Устройства
 */
final readonly class AnDevice {
    public function __construct(
        public string            $applicationId,
        public string            $deviceFpt,
        public DateTimeImmutable $firstSeenAt,
        public DateTimeImmutable $lastSeenAt,
        public int               $requestCount,
    ) {}


    public function copyWith(
        ?string             $applicationId = null,
        ?string             $deviceFpt = null,
        ?DateTimeImmutable  $firstSeenAt = null,
        ?DateTimeImmutable  $lastSeenAt = null,
        ?int                $requestCount = null,
    ): self {
        return new self(
            $applicationId ?? $this->applicationId,
            $deviceFpt ?? $this->deviceFpt,
            $firstSeenAt ?? $this->firstSeenAt,
            $lastSeenAt ?? $this->lastSeenAt,
            $requestCount ?? $this->requestCount,
        );
    }

}
