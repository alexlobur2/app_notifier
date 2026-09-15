<?php
declare(strict_types=1);


/**
 *  Приложения
 */
final readonly class AnApp extends IsAnEntity{
    public function __construct(
        public string            $appId,
        public string            $token,
        public bool              $enabled,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt,
    ) {}


    static function fromArray(array $data): self {
        return new self(
            $data['app_id'],
            $data['token'],
            (bool) $data['enabled'],
            array_key_exists('created_at', $data)
                ? DateTimeImmutable::createFromTimestamp($data['created_at'])
                : new DateTimeImmutable(),
            array_key_exists('updated_at', $data)
                ? DateTimeImmutable::createFromTimestamp($data['updated_at'])
                : new DateTimeImmutable()
        );
    }


    public function toArray(): array {
        return [
            'app_id'     => $this->appId,
            'token'      => $this->token,
            'enabled'    => $this->enabled,
            'created_at' => $this->createdAt->getTimestamp(),
            'updated_at' => $this->updatedAt->getTimestamp(),
        ];
    }


    public function copyWith(
        ?string             $appId = null,
        ?string             $token = null,
        ?bool               $enabled = null,
        ?DateTimeImmutable  $createdAt = null,
        ?DateTimeImmutable  $updatedAt = null,
    ): self {
        return new self(
            $appId ?? $this->appId,
            $token ?? $this->token,
            $enabled ?? $this->enabled,
            $createdAt ?? $this->createdAt,
            $updatedAt ?? $this->updatedAt,
        );
    }
}
