<?php


/**
 *  Репозиторий Устройств
 */
class AnDevicesRepository extends IsAnRepository {

    /**
     *  Вставка или обновление устройства
     *  @throws Exception
     */
    public function upsert( string $appId, string $deviceFpt ): void {
        $sql = "INSERT INTO ".AnDb::TABLE_DEVICES." 
            (app_id, device_fpt, request_count, first_seen_at, last_seen_at)
            VALUES (:app_id, :device_fpt, 1, NOW(), NOW())
            ON DUPLICATE KEY UPDATE
                last_seen_at = NOW(),
                request_count = request_count + VALUES(request_count)
            ";
        $params = [
            ':app_id' => $appId,
            ':device_fpt' => $deviceFpt,
        ];
        $this->lpdo->execute($sql, $params);
    }


    /**
     *  Получение устройства по Id
     *  @throws Exception
     */
    public function getByFpt(string $fpt): ?AnDevice {
        // Получение данных из БД
        $row = $this->lpdo->exec2row(
            "SELECT * FROM ".AnDb::TABLE_DEVICES." WHERE device_fpt=?",
            [ $fpt ]
        );

        // Формируем результат
        return is_null($row) ? null : $this->mapToAnDevice($row);
    }


    /**
     *  Получение списка объявлений
     *  @throws Exception
     */
    public function getList(AnDevicesRepositoryOpts $opts): array {
        // sql
        $sql = "SELECT * FROM ".AnDb::TABLE_DEVICES." 
            ".$opts->where("WHERE")."
            ".$opts->orderBy()."
            ".$opts->limits();

        // Получение данных из БД
        $rows = $this->lpdo->query2array($sql);

        // Формируем результат
        return array_map( fn($row) => $this->mapToAnDevice($row), $rows );
    }


    /**
     *  Получение списка объявлений
     *  @throws Exception
     */
    public function count(AnDevicesRepositoryOpts $opts): int {
        return (int) $this->lpdo->query2val(
            "SELECT COUNT(*) FROM ".AnDb::TABLE_DEVICES." ".$opts->where("WHERE")
        );
    }


    /**
     * @throws DateMalformedStringException
     */
    private function mapToAnDevice(array $row): AnDevice {
        return new AnDevice(
            appId:  $row['app_id'],
            deviceFpt:      $row['device_fpt'],
            firstSeenAt:    new DateTimeImmutable($row['first_seen_at']),
            lastSeenAt:     new DateTimeImmutable($row['last_seen_at']),
            requestCount:   $row['request_count'],
        );
    }


}

