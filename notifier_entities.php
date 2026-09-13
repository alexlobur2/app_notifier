================================================================================
ФАЙЛ: src/Entity/AnnounceStatus.php
================================================================================

<?php

declare(strict_types=1);

namespace App\Notifier\Entity;

enum AnnounceStatus: string
{
    case DRAFT = 'draft';
    case LIVE = 'live';
    case ARCHIVED = 'archived';
}


================================================================================
ФАЙЛ: src/Entity/App.php
================================================================================

<?php

declare(strict_types=1);

namespace App\Notifier\Entity;

use DateTimeImmutable;

final class App
{
    public function __construct(
        public readonly string $applicationId,
        public readonly string $token,
        public bool $enabled,
        public readonly DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt,
    ) {
    }

    public static function create(string $applicationId, string $token): self
    {
        $now = new DateTimeImmutable();
        return new self(
            applicationId: $applicationId,
            token: $token,
            enabled: true,
            createdAt: $now,
            updatedAt: $now,
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            applicationId: $data['application_id'],
            token: $data['token'],
            enabled: (bool) $data['enabled'],
            createdAt: new DateTimeImmutable($data['created_at']),
            updatedAt: new DateTimeImmutable($data['updated_at']),
        );
    }

    public function disable(): void
    {
        if (!$this->enabled) {
            return;
        }
        $this->enabled = false;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function enable(): void
    {
        if ($this->enabled) {
            return;
        }
        $this->enabled = true;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function toArray(): array
    {
        return [
            'application_id' => $this->applicationId,
            'token' => $this->token,
            'enabled' => $this->enabled ? 1 : 0,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}


================================================================================
ФАЙЛ: src/Entity/Announce.php
================================================================================

<?php

declare(strict_types=1);

namespace App\Notifier\Entity;

use DateTimeImmutable;

final class Announce
{
    public function __construct(
        public readonly string $uuid,
        public readonly string $applicationId,
        public string $title,
        public string $body,
        public ?DateTimeImmutable $startAt,
        public ?DateTimeImmutable $endAt,
        public AnnounceStatus $status,
        public readonly DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt,
    ) {
    }

    public static function create(
        string $applicationId,
        string $title,
        string $body,
        ?DateTimeImmutable $startAt = null,
        ?DateTimeImmutable $endAt = null,
    ): self {
        $now = new DateTimeImmutable();
        // Генерация UUID из 16 байт (32 hex символа), но для char(16) в БД 
        // нам нужно 16 символов. Если в БД char(16) под бинарные данные - ок.
        // Если под строку - убедитесь, что длина совпадает. 
        // Здесь генерируем 8 байт -> 16 hex символов.
        $uuid = bin2hex(random_bytes(8));

        return new self(
            uuid: $uuid,
            applicationId: $applicationId,
            title: $title,
            body: $body,
            startAt: $startAt,
            endAt: $endAt,
            status: AnnounceStatus::DRAFT,
            createdAt: $now,
            updatedAt: $now,
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            uuid: $data['uuid'],
            applicationId: $data['application_id'],
            title: $data['title'],
            body: $data['body'],
            startAt: $data['start_at'] ? new DateTimeImmutable($data['start_at']) : null,
            endAt: $data['end_at'] ? new DateTimeImmutable($data['end_at']) : null,
            status: AnnounceStatus::from($data['status']),
            createdAt: new DateTimeImmutable($data['created_at']),
            updatedAt: new DateTimeImmutable($data['updated_at']),
        );
    }

    public function publish(?DateTimeImmutable $startAt = null): void
    {
        $this->status = AnnounceStatus::LIVE;
        if ($startAt !== null) {
            $this->startAt = $startAt;
        } elseif ($this->startAt === null) {
            $this->startAt = new DateTimeImmutable();
        }
        $this->updatedAt = new DateTimeImmutable();
    }

    public function archive(): void
    {
        $this->status = AnnounceStatus::ARCHIVED;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function updateContent(string $title, string $body, ?DateTimeImmutable $startAt, ?DateTimeImmutable $endAt): void
    {
        $this->title = $title;
        $this->body = $body;
        $this->startAt = $startAt;
        $this->endAt = $endAt;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'application_id' => $this->applicationId,
            'title' => $this->title,
            'body' => $this->body,
            'start_at' => $this->startAt?->format('Y-m-d H:i:s'),
            'end_at' => $this->endAt?->format('Y-m-d H:i:s'),
            'status' => $this->status->value,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}


================================================================================
ФАЙЛ: src/Entity/Device.php
================================================================================

<?php

declare(strict_types=1);

namespace App\Notifier\Entity;

use DateTimeImmutable;

final class Device
{
    public function __construct(
        public readonly string $applicationId,
        public readonly string $deviceFpt,
        public DateTimeImmutable $firstSeenAt,
        public DateTimeImmutable $lastSeenAt,
        public int $requestCount,
    ) {
    }

    public static function create(string $applicationId, string $deviceFpt): self
    {
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
