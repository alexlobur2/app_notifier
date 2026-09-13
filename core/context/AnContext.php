<?php

/**
 *  Текущий контекст
 */
class AnContext {
    private static self $_instance;

    public static function instance(): self {
        return self::$_instance ??= new self();
    }

    private function __construct() {
    }

    private AnApiRequest $request {
        get => $this->request;
    }

    /**
     *  Инициализация
     */
    public function init(AnApiRequest $request): self {
        $this->request = $request;
        return self::$_instance;
    }

}
