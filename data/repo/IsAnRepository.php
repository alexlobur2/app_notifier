<?php

/**
 *  Интерфейс репозитория
 */
abstract class IsAnRepository {

    protected AnDb $db;

    protected LPDO $lpdo{
        get => $this->db->db;
    }


    public function __construct() {
        $this->db = AnDb::instance();
    }


    /**
     *  Кидает исключение, если было ошибка БД
     *  @throws Exception
     */
    protected function throwOnDbError(): void {
        if($this->db->db->isError()){
            throw new Exception();
        }
    }

}

