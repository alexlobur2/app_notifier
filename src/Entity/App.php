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
