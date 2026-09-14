<?php

/**
 *  Добавление приложения
 *
 *  **На входе в дата**:
 *  ```
 *  {
 *      "app" : [] AppDTO
 *  }
 *  ```
 *
 * **На выходе**
 * ```
 * {
 *      "app" : [] AppDTO
 * }
 * ```
 */
class AdmUpsertAppResponseUC extends IsAdmAnResponseUC {
    private AnAppsRepository $repo;

    public function __construct() {
        parent::__construct();
        $this->repo = new AnAppsRepository();
    }

    /**
     * @throws Exception
     */
    public function execute(): AnApiResponse {

        // Начальные данные
        $appData = $this->assertData('app', 'array');

        // Парсим через маппер
        try {
            $app = AnDtoMapper::dtoToApp($appData);
        } catch (Exception $e) {
            throw new AnApiException(AnApiException::API_BAD_REQUEST, $e->getMessage());
        }

        // Обновление данных
        $this->repo->upsert(
            appId:   $app->appId,
            token:   $app->token,
            enabled: $app->enabled,
        );

        // Получение данных приложения
        $app = $this->repo->getByAppId($app->appId);

        return new AnApiResponse(true, [ "app" => AnDtoMapper::appToDto($app) ]);
    }

}


