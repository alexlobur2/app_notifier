<?php

/**
 *  Добавление Анонса
 *
 *  **На входе в дата**:
 *  ```
 *  {
 *      "announce" : [] AnnounceDTO
 *  }
 *  ```
 *
 * **На выходе**
 * ```
 * {
 *      "announce" : [] AnnounceDTO
 * }
 * ```
 */
class AdmUpsertAnnounceResponseUC extends IsAdmAnResponseUC {
    private AnAnnouncesRepository $repo;
    private AnAppsRepository $appsRepo;

    public function __construct() {
        parent::__construct();
        $this->repo = new AnAnnouncesRepository();
        $this->appsRepo = new AnAppsRepository();
    }

    /**
     * @throws Exception
     */
    public function execute(): AnApiResponse {

        // Начальные данные
        $announceData = $this->assertData('announce', 'array');

        // Парсим через маппер
        try{
            // Если нет UUID - создадим новый
            $announceData['uuid'] = $this->ensureUuid($announceData['uuid']??null);
            $announce = AnDtoMapper::dtoToAnnounce($announceData);
        } catch (Exception $e) {
            throw new AnApiException(AnApiException::API_BAD_REQUEST, $e->getMessage());
        }

        // Проверка наличия приложения
        if(is_null($this->appsRepo->getByAppId($announce->appId))){
            throw new AnApiException(AnApiException::API_BAD_REQUEST, "Application:".$announce->appId." not exist");
        }

        // Обновление данных
        $this->repo->upsert($announce);

        // Получение нового анонса
        $announce = $this->repo->getByUuid($announce->uuid);

        return new AnApiResponse(true, [ "announce" => AnDtoMapper::announceToDto($announce) ]);
    }


    /**
     *  Убеждаемся что есть UUID
     *  @throws Exception
     */
    private function ensureUuid(?string $uuid): string {
        if(!is_null($uuid)) return $uuid;
        // генерация uuid с проверкой на наличие записи с таким UUID
        do{
            $uuid = CodeGenerator::generateUUID(16);
            $exist = $this->repo->getByUuid($uuid);
            if(is_null($exist)) return $uuid;
        } while(true);
    }

}
