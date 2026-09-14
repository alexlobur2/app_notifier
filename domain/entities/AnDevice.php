<?php
declare(strict_types=1);


/**
 *  Устройства
 */
final readonly class AnDevice {
    public function __construct(
        public string            $appId,
        public string            $deviceFpt,
        public DateTimeImmutable $firstSeenAt,
        public DateTimeImmutable $lastSeenAt,
        public int               $requestCount,
    ) {}


    static public function fromArray(array $data): self {
        return new self(
            $data['app_id'],
            $data['device_fpt'],
            DateTimeImmutable::createFromTimestamp($data['first_seen_at']),
            DateTimeImmutable::createFromTimestamp($data['last_seen_at']),
            $data['request_count'],
        );
    }


    public function toArray(): array {
        return [
            'app_id'        => $this->appId,
            'device_fpt'    => $this->deviceFpt,
            'first_seen_at' => $this->firstSeenAt->getTimestamp(),
            'last_seen_at'  => $this->lastSeenAt->getTimestamp(),
            'request_count' => $this->requestCount,
        ];
    }


    public function copyWith(
        ?string             $appId = null,
        ?string             $deviceFpt = null,
        ?DateTimeImmutable  $firstSeenAt = null,
        ?DateTimeImmutable  $lastSeenAt = null,
        ?int                $requestCount = null,
    ): self {
        return new self(
            $appId ?? $this->appId,
            $deviceFpt ?? $this->deviceFpt,
            $firstSeenAt ?? $this->firstSeenAt,
            $lastSeenAt ?? $this->lastSeenAt,
            $requestCount ?? $this->requestCount,
        );
    }

}
