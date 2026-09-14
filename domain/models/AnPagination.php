<?php
declare(strict_types=1);


/**
 *  Пагинация ввода - вывода
 */
class AnPagination {

    public function __construct(
        public ?int $offset,
        public ?int $limit,
        public ?string $order,
        public bool $desc = false,
        public ?int $total,
    ){}

    static function fromArray(?array $data): self {
        $data ??= [];
        return new self(
            offset: $data['offset'] ?? null,
            limit:  $data['limit'] ?? null,
            order:  $data['order'] ?? null,
            desc:   $data['desc'] ?? null,
            total: $data['total'] ?? null,
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