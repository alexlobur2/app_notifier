<?php
declare(strict_types=1);


/**
 *  Анонсы
 */
final readonly class AnAnnounce {
    public function __construct(
        public string             $uuid,
        public string             $appId,
        public string             $title,
        public string             $body,
        public AnAnnounceStatus   $status,
        public ?DateTimeImmutable $startAt,
        public ?DateTimeImmutable $endAt,
        public DateTimeImmutable  $createdAt,
        public DateTimeImmutable  $updatedAt,
    ) {}


    /**
     * @throws DateMalformedStringException
     */
    static function fromArray(array $data): AnAnnounce {
        return new self(
            $data['uuid'],
            $data['app_id'],
            $data['title'],
            $data['body'],
            AnAnnounceStatus::from($data['status']),
            is_null($data['start_at']) ? null : DateTimeImmutable::createFromTimestamp($data['start_at']),
            is_null($data['end_at']) ? null : DateTimeImmutable::createFromTimestamp($data['end_at']),
            new DateTimeImmutable($data['created_at']),
            new DateTimeImmutable($data['updated_at']),
        );
    }


    /**
     *  Представление в виде массива
     */
    function toArray(): array {
        return [
            "app_id" => $this->appId,
            "uuid"           => $this->uuid,
            "title"          => $this->title,
            "body"           => $this->body,
            "status"         => $this->status->value,
            "start_at"       => $this->startAt->getTimestamp(),
            "end_at"         => $this->endAt->getTimestamp(),
            "created_at"     => $this->createdAt->getTimestamp(),
            "updated_at"     => $this->updatedAt->getTimestamp(),
        ];
    }


    public function copyWith(
        ?string             $uuid = null,
        ?string             $appId = null,
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
            $appId ?? $this->appId,
            $title ?? $this->title,
            $body ?? $this->body,
            $status ?? $this->status,
            $startAt ?? $this->startAt,
            $endAt ?? $this->endAt,
            $createdAt ?? $this->createdAt,
            $updatedAt ?? $this->updatedAt,
        );
    }


}
