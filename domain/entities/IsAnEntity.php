<?php
declare(strict_types=1);


/**
 *  Устройства
 */
abstract readonly class IsAnEntity {


    abstract function toArray(): array;

    abstract function copyWith(): self;

    public function __serialize(): array {
        return $this->toArray();
    }

    public function __toString(): string {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    }

}
