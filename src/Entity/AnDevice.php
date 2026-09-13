<?php
declare(strict_types=1);

final class AnDevice {
    public function __construct(
        public readonly string $applicationId,
        public readonly string $deviceFpt,
        public DateTimeImmutable $firstSeenAt,
        public DateTimeImmutable $lastSeenAt,
        public int $requestCount,
    ){}

    public static function create(string $applicationId, string $deviceFpt): self {
        $now = new DateTimeImmutable();
        return new self(
            applicationId: $applicationId,
            deviceFpt: $deviceFpt,
            firstSeenAt: $now,
            lastSeenAt: $now,
            requestCount: 1,
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            applicationId: $data['application_id'],
            deviceFpt: $data['device_fpt'],
            firstSeenAt: new DateTimeImmutable($data['first_seen_at']),
            lastSeenAt: new DateTimeImmutable($data['last_seen_at']),
            requestCount: (int) $data['request_count'],
        );
    }

    public function touch(): void
    {
        $this->lastSeenAt = new DateTimeImmutable();
        $this->requestCount++;
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
    
    public function getUpdateFields(): array
    {
        return [
            'last_seen_at' => $this->lastSeenAt->format('Y-m-d H:i:s'),
            'request_count' => $this->requestCount,
        ];
    }
}
