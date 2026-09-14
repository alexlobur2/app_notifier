<?php

/**
 *  Создание / обновление записи об устройстве
 */
class AdmTouchDeviceUC {
    private AnDevicesRepository $repo;

    public function __construct() {
        $this->repo = new AnDevicesRepository();
    }

    /**
     * @throws Exception
     */
    public function execute(string $appId, string $deviceFpt): ?AnDevice {

        try{
            // Обновляем данные об устройстве
            $this->repo->upsert($appId, $deviceFpt);
            // Возврат данных устройства
            return $this->repo->getByFpt($deviceFpt);
        } catch (Exception $e){
            AnUtils::logError($e->getMessage(), $e->getTraceAsString());
        }
    }

}