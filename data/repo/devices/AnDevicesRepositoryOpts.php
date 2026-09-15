<?php
declare(strict_types=1);

/**
 *  Репозиторий Устройств
 */
class AnDevicesRepositoryOpts extends IsAnRepositoryOpts {

    public function __construct(
        // список приложений
        public array $appsIds = [],
        ?int $limit,
        ?int $offset,
        public ?string $order = null,
        public bool $desc = false,
    ){
        parent::__construct($limit, $offset, $order, $desc);
    }


    public function where($prefix = ""): string {
        $appsIds = $this->prepareValue($this->appsIds);
        $conditions = [
            !empty($appsIds) ? 'app_id IN ('.implode(",",$appsIds).')' : null,
        ];
        $conditions = array_filter($conditions, fn($item) => !is_null($item));
        return !empty($conditions) ? $prefix.' '.implode(' AND ', $conditions) : '';
    }

}

