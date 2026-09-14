<?php
declare(strict_types=1);


/**
 *  Приложения
 */
final readonly class AnApp {
    public function __construct(
        public string            $applicationId,
        public string            $token,
        public bool              $enabled,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt,
    ) {}


    public function copyWith(
        ?string             $applicationId = null,
        ?string             $token = null,
        ?bool               $enabled = null,
        ?DateTimeImmutable  $createdAt = null,
        ?DateTimeImmutable  $updatedAt = null,
    ): self {
        return new self(
            $applicationId ?? $this->applicationId,
            $token ?? $this->token,
            $enabled ?? $this->enabled,
            $createdAt ?? $this->createdAt,
            $updatedAt ?? $this->updatedAt,
        );
    }
}
