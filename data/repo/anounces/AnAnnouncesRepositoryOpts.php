<?php
declare(strict_types=1);

/**
 *  Фильтрация данных списков Анонсов
 */
class AnAnnouncesRepositoryOpts extends IsAnRepositoryOpts {

    public function __construct(
        // список приложений
        public array $appsIds = [],
        // список статусов
        public array $statuses = [],
        // список uuid для исключения из выборки
        public array $excludeUuids = [],
        // текущая дата для диапазона (start_at, end_at)
        public ?string $curDate = null,
        ?int $limit = null,
        ?int $offset = null,
        ?string $order = null,
        bool $desc = false,
    ){
        parent::__construct($limit, $offset, $order, $desc);
    }


    public function where($prefix = ""): string {
        $appsIds = $this->prepareValue($this->appsIds);
        $statuses = $this->prepareValue($this->statuses);
        $excludeUuids = $this->prepareValue($this->excludeUuids);
        $curDate = $this->prepareValue($this->curDate);

        $conditions = [
            !empty($appsIds) ? 'app_id IN ('.implode($appsIds).')' : null,
            !empty($statuses) ? 'status IN ('.implode($statuses).')' : null,
            !empty($excludeUuids) ? 'uuid NOT IN ('.implode($excludeUuids).')' : null,
            !is_null($curDate) ? 'start_at >= '.$curDate.' AND end_at <= '.$curDate : null,
        ];
        $conditions = array_filter($conditions, fn($item) => !is_null($item));
        return !empty($conditions) ? $prefix.' '.implode(' AND ', $conditions) : '';
    }

}


