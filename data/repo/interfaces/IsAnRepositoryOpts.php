<?php
declare(strict_types=1);


/**
 *  Интерфейс фильтра репозитория
 */
abstract class IsAnRepositoryOpts {
    protected LPDO $lpdo;

    public function __construct(
        public ?int    $limit = null,
        public ?int    $offset = null,
        public ?string $order = null,
        public bool    $desc = false,
    ) {
        $this->lpdo = AnDb::instance()->db;
    }


    /**
     *  Построение блока where
     */
    public abstract function where($prefix = ""): string;


    /**
     *  Построение блока LIMIT
     * @throws Exception
     */
    public function limits(): string {
        if (is_null($this->limit) && is_null($this->offset)) return "";
        if (!is_null($this->offset) && is_null($this->limit)) throw new Exception("Cannot set `Offset` without `Limit`");
        return implode("", [
            "LIMIT " . $this->prepareValue($this->limit),
            is_null($this->offset) ? "" : " OFFSET " . $this->prepareValue($this->offset)
        ]);
    }


    /**
     *  Сортировка
     */
    public function orderBy(): string {
        if (is_null($this->order)) return "";
        return "ORDER BY ".$this->prepareValue($this->order).( $this->desc ? " DESC" : "" );
    }


    /**
     *  Подготовка значения
     *  - если строка, то будет quote
     *  - если массив, то будет подготовка значений
     *  - иначе вернет значение
     */
    protected function prepareValue(mixed $value): mixed {
        if (is_array($value)) {
            return array_map(fn($item) => $this->prepareValue($item), $value);
        }
        return is_string($value) ? $this->lpdo->quote($value) : $value;
    }

}

