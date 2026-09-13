<?php
declare(strict_types=1);

final class AnApp {
    public function __construct(
        public readonly string $applicationId,
        public readonly string $token,
        public readonly bool $enabled,
        public readonly DateTimeImmutable $createdAt,
        public readonly DateTimeImmutable $updatedAt,
    ){}

    public function copyWith(
        ?string $applicationId = null,
        ?string $token = null,
        ?bool $enabled = null,
        ?DateTimeImmutable $createdAt = null,
        ?DateTimeImmutable $updatedAt = null,
    ): self {
        return new self(
            applicationId: $applicationId ?? $this->applicationId,
            token: $token ?? $this->token,
            enabled: $enabled ?? $this->enabled,
            createdAt: $createdAt ?? $this->createdAt,
            updatedAt: $updatedAt ?? $this->updatedAt,
        );
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

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }
}
