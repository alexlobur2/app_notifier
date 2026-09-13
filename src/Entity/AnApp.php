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
        $args = \func_get_args();
        
        return new self(
            applicationId: $args[0] !== null || \array_key_exists(0, $args) ? $applicationId : $this->applicationId,
            token: $args[1] !== null || \array_key_exists(1, $args) ? $token : $this->token,
            enabled: $args[2] !== null || \array_key_exists(2, $args) ? $enabled : $this->enabled,
            createdAt: $args[3] !== null || \array_key_exists(3, $args) ? $createdAt : $this->createdAt,
            updatedAt: $args[4] !== null || \array_key_exists(4, $args) ? $updatedAt : $this->updatedAt,
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
