<?php
declare(strict_types=1);


/**
 *  Анонсы
 */
final readonly class AnAnnounce {
    public function __construct(
        public string             $uuid,
        public string             $applicationId,
        public string             $title,
        public string             $body,
        public ?DateTimeImmutable $startAt,
        public ?DateTimeImmutable $endAt,
        public AnAnnounceStatus   $status,
        public DateTimeImmutable  $createdAt,
        public DateTimeImmutable  $updatedAt,
    ) {}


    public function copyWith(
        ?string             $uuid = null,
        ?string             $applicationId = null,
        ?string             $title = null,
        ?string             $body = null,
        DateTimeImmutable|None|null     $startAt = new None(),
        DateTimeImmutable|None|null     $endAt = new None(),
        ?AnAnnounceStatus               $status = null,
        ?DateTimeImmutable              $createdAt = null,
        ?DateTimeImmutable              $updatedAt = null,
    ): self {
        return new self(
            $uuid ?? $this->uuid,
            $applicationId ?? $this->applicationId,
            $title ?? $this->title,
            $body ?? $this->body,
            $startAt ?? $this->startAt,
            $endAt ?? $this->endAt,
            $status ?? $this->status,
            $createdAt ?? $this->createdAt,
            $updatedAt ?? $this->updatedAt,
        );
    }

    /**
     *  Представление в виде DTO
     */
    public function  toDto(): array {
        return [
            'uuid' => $this->uuid,
            'applicationId' => $this->applicationId,
            'title' => $this->title,
            'body' => $this->body,
            'startAt'   => $this->startAt?->getTimestamp(),
            'endAt'     => $this->endAt?->getTimestamp(),
            'status'    => $this->status,
            'createdAt' => $this->createdAt->getTimestamp(),
            'updatedAt' => $this->updatedAt->getTimestamp(),
        ];
    }

}
