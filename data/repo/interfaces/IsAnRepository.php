<?php


/**
 *  Интерфейс репозитория
 */
abstract class IsAnRepository {

    protected AnDb $db;

    protected LPDO $lpdo {
        get => $this->db->db;
    }


    public function __construct() {
        $this->db = AnDb::instance();
    }

}

