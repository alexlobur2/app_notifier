<?php

/**
 *  Изменение данных приложения
 *
 *  **На входе в дата**:
 *  ```
 *  {
 *      "app_id" : "my.app" // Идентификатор приложения
 *      "fields" : []       // Массив изменяемых полей
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
class AdmAppUpdateFieldsResponseUC extends IsAdmAnResponseUC {
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
        $appId = $this->assertData('app_id', 'string');
        $fields = $this->assertData('fields', 'array');

        // проверка разрешенных полей
        $disallow = implode(", ", array_diff(array_keys($fields), ['app_id', 'token', 'enabled']));
        if(!empty($disallow)){
            throw new AnApiException(AnApiException::API_BAD_REQUEST, "Fields not allowed: $disallow");
        }

        // проверка полей
        $this->assertValue($fields['app_id']??null,  "string", true);
        $this->assertValue($fields['token']??null,   "string", true);
        $this->assertValue($fields['enabled']??null, "bool",   true);

        // Обновление данных
        $this->repo->update( $fields, $appId );

        // Получение данных приложения
        $app = $this->repo->getByAppId($fields['app_id'] ?? $appId);

        return new AnApiResponse(true, [ "app" => AnDtoMapper::appToDto($app) ]);
    }

}


