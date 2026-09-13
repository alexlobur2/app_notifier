<?php
declare(strict_types=1);

final class AnAnnounce {

    public function __construct(
        public readonly string $uuid,
        public readonly string $applicationId,
        public readonly string $title,
        public readonly string $body,
        public readonly ?DateTimeImmutable $startAt,
        public readonly ?DateTimeImmutable $endAt,
        public readonly AnAnnounceStatus $status,
        public readonly DateTimeImmutable $createdAt,
        public readonly DateTimeImmutable $updatedAt,
    ){}

    public function copyWith(
        ?string $uuid = null,
        ?string $applicationId = null,
        ?string $title = null,
        ?string $body = null,
        ?DateTimeImmutable $startAt = null,
        ?DateTimeImmutable $endAt = null,
        ?AnAnnounceStatus $status = null,
        ?DateTimeImmutable $createdAt = null,
        ?DateTimeImmutable $updatedAt = null,
    ): self {
        $args = \func_get_args();
        
        return new self(
            uuid: $args[0] !== null || \array_key_exists(0, $args) ? $uuid : $this->uuid,
            applicationId: $args[1] !== null || \array_key_exists(1, $args) ? $applicationId : $this->applicationId,
            title: $args[2] !== null || \array_key_exists(2, $args) ? $title : $this->title,
            body: $args[3] !== null || \array_key_exists(3, $args) ? $body : $this->body,
            startAt: $args[4] !== null || \array_key_exists(4, $args) ? $startAt : $this->startAt,
            endAt: $args[5] !== null || \array_key_exists(5, $args) ? $endAt : $this->endAt,
            status: $args[6] !== null || \array_key_exists(6, $args) ? $status : $this->status,
            createdAt: $args[7] !== null || \array_key_exists(7, $args) ? $createdAt : $this->createdAt,
            updatedAt: $args[8] !== null || \array_key_exists(8, $args) ? $updatedAt : $this->updatedAt,
        );
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

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }
}
