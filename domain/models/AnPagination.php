<?php
declare(strict_types=1);


/**
 *  Пагинация ввода - вывода
 */
class AnPagination {

    public function __construct(
        public ?int $offset = null,
        public ?int $limit = null,
        public ?string $order = null,
        public bool $desc = false,
        public ?int $total = null,
    ){}

    static function fromArray(?array $data): self {
        $data ??= [];
        return new self(
            offset: $data['offset'] ?? null,
            limit:  $data['limit'] ?? null,
            order:  $data['order'] ?? null,
            desc:   $data['desc'] ?? false,
            total:  $data['total'] ?? null,
        );
    }


    function toArray(): array {
        return [
            "offset" => $this->offset,
            "limit" => $this->limit,
            "order" => $this->order,
            "desc" => $this->desc,
            "total" => $this->total
        ];
    }

    public function __serialize(): array {
        return $this->toArray();
    }

}