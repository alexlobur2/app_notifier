<?php
declare(strict_types=1);

final class AnAnnounce {

    public function __construct(
        public readonly string $uuid,
        public readonly string $applicationId,
        public string $title,
        public string $body,
        public ?DateTime $startAt,
        public ?DateTime $endAt,
        public AnnounceStatus $status,
        public readonly DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt,
    ){}

    public static function create(
        string $applicationId,
        string $title,
        string $body,
        ?DateTimeImmutable $startAt = null,
        ?DateTimeImmutable $endAt = null,
    ): self {
        $now = new DateTimeImmutable();
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
