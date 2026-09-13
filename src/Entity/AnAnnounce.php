<?php
declare(strict_types=1);

enum UnsetMarker {
    case Instance;
}

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
        DateTimeImmutable|UnsetMarker $startAt = UnsetMarker::Instance,
        DateTimeImmutable|UnsetMarker $endAt = UnsetMarker::Instance,
        AnAnnounceStatus|UnsetMarker $status = UnsetMarker::Instance,
        ?DateTimeImmutable $createdAt = null,
        ?DateTimeImmutable $updatedAt = null,
    ): self {
        return new self(
            uuid: $uuid ?? $this->uuid,
            applicationId: $applicationId ?? $this->applicationId,
            title: $title ?? $this->title,
            body: $body ?? $this->body,
            startAt: $startAt instanceof UnsetMarker ? $this->startAt : $startAt,
            endAt: $endAt instanceof UnsetMarker ? $this->endAt : $endAt,
            status: $status instanceof UnsetMarker ? $this->status : $status,
            createdAt: $createdAt ?? $this->createdAt,
            updatedAt: $updatedAt ?? $this->updatedAt,
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
