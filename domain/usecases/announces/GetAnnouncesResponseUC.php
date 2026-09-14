<?php

/**
 *  Получение списка активных анонсов для приложения
 *
 *  **На входе в дата:**
 *  ```json
 *  {
 *      "exclude_uuids": []          // список uuid анонсов, которые не нужно показывать
 *      "device_fpt": "XXXXXXXXX"   // отпечаток текущего устройства,
 *  }
 *  ```
 *  **На выходе**
 * ```
 * {
 *      "announces" : []
 * }
 */

class GetAnnouncesResponseUC extends IsAnResponseUC {
    private AnAnnouncesRepository $repo;

    public function __construct() {
        $this->repo = new AnAnnouncesRepository();
    }


    /**
     * @throws Exception
     */
    public function execute(): AnApiResponse {
        // получение начальных данных
        $deviceFpt      = $this->data['device_fpt']??null;
        $excludeUuids   = $this->assertData('exclude_uuids', 'array', true);

        // Обновляем данные об устройстве
        if(!is_null($deviceFpt)){
            new AdmTouchDeviceUC()->execute($this->app->appId, $deviceFpt);
        }

        // Список анонсов
        $announces = $this->repo->getList(
            new AnAnnouncesRepositoryOpts(
                appsIds: [$this->app->appId],
                statuses: [ AnAnnounceStatus::LIVE->value ],
                excludeUuids: $excludeUuids,
                curDate: AnUtils::date2Iso(new DateTime())
            )
        );

        return new AnApiResponse(true, [
            "announces" => AnDtoMapper::announcesToDto($announces),
        ]);

    }

}