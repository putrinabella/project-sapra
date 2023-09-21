<?php
    function userLogin(){
        $db = \Config\Database::connect();
        return $db->table('tblUser')->where('idUser', session('id_user'))->get()->getRow();
    }
?>