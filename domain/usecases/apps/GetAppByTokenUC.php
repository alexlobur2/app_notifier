<?php

/**
 *  Получение приложения по токену
 */
class GetAppByTokenUC {
    private AnAppsRepository $repo;

    public function __construct() {
        $this->repo = new AnAppsRepository();
    }


    public function execute(string $token): ?AnApp {
        if(trim($token) == "") return null; // Пустые данные запрещены
        try{
            return $this->repo->getByToken($token);
        } catch (Exception $e){
            AnUtils::logError($e->getMessage(), $e->getTraceAsString());
            return null;
        }
    }

}
