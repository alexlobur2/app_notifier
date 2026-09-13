<?php
declare(strict_types=1);

final class AnDevice {
    public function __construct(
        public readonly string $applicationId,
        public readonly string $deviceFpt,
        public readonly DateTimeImmutable $firstSeenAt,
        public readonly DateTimeImmutable $lastSeenAt,
        public readonly int $requestCount,
    ){}

    public function copyWith(
        ?string $applicationId = null,
        ?string $deviceFpt = null,
        ?DateTimeImmutable $firstSeenAt = null,
        ?DateTimeImmutable $lastSeenAt = null,
        ?int $requestCount = null,
    ): self {
        return new self(
            applicationId: $applicationId ?? $this->applicationId,
            deviceFpt: $deviceFpt ?? $this->deviceFpt,
            firstSeenAt: $firstSeenAt ?? $this->firstSeenAt,
            lastSeenAt: $lastSeenAt ?? $this->lastSeenAt,
            requestCount: $requestCount ?? $this->requestCount,
        );
    }

    public function toArray(): array
    {
        return [
            'application_id' => $this->applicationId,
            'device_fpt' => $this->deviceFpt,
            'first_seen_at' => $this->firstSeenAt->format('Y-m-d H:i:s'),
            'last_seen_at' => $this->lastSeenAt->format('Y-m-d H:i:s'),
            'request_count' => $this->requestCount,
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }
}
