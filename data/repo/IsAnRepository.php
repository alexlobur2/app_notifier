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


    /**
     *  Выдает дату в виде ISO строки
     *  @param DateTimeInterface|null $date
     *  @return string
     */
    protected function date2Iso(?DateTimeInterface $date): string {
        return $date?->format('Y-m-d H:i:s');
    }

}

