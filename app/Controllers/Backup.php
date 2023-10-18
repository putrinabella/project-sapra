<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Backup extends Controller
{
    public function index()
    {
        $dbuser        = "root";
        $dbpass        = "";
        $dbhost        = "localhost";
        $dbname        = "dbmanajemensapra";
        $timestamp     = date('Y-F-j');
        $backup_name   = "dbmanajemensapra_{$timestamp}.sql";
        $tables = array(
            "tbldokumensekolah",
            "tblidentitasgedung",
            "tblidentitaskelas",
            "tblidentitaslab",
            "tblidentitaslantai",
            "tblidentitasprasarana",
            "tblidentitassarana",
            "tblkategorimanajemen",
            "tbllayananlabaset",
            "tbllayananlabnonaset",
            "tblmanajemenpeminjaman",
            "tblprofilsekolah",
            "tblrincianaset",
            "tblrincianlabaset",
            "tblsaranalayananaset",
            "tblsaranalayanannonaset",
            "tblsosialmedia",
            "tblstatuslayanan",
            "tblsumberdana",
            "tbluser",
            "tblwebsite"
        );

        $this->exportDatabase($dbhost, $dbuser, $dbpass, $dbname, $tables, $backup_name);
    }

    private function exportDatabase($host, $user, $pass, $name, $tables = false, $backup_name = false)
    {
        $mysqli = new \mysqli($host, $user, $pass, $name);
        $mysqli->select_db($name);
        $mysqli->query("SET NAMES 'utf8'");

        $queryTables = $mysqli->query('SHOW TABLES');
        while ($row = $queryTables->fetch_row()) {
            $target_tables[] = $row[0];
        }
        if ($tables !== false) {
            $target_tables = array_intersect($target_tables, $tables);
        }

        $content = "";

        foreach ($target_tables as $table) {
            $result        = $mysqli->query('SELECT * FROM ' . $table);
            $fields_amount = $result->field_count;
            $rows_num      = $mysqli->affected_rows;
            $res           = $mysqli->query('SHOW CREATE TABLE ' . $table);
            $tableMLine    = $res->fetch_row();
            $content       .= "\n\n" . $tableMLine[1] . ";\n\n";

            for ($i = 0, $st_counter = 0; $i < $fields_amount; $i++, $st_counter = 0) {
                while ($row = $result->fetch_row()) {
                    if ($st_counter % 100 == 0 || $st_counter == 0) {
                        $content .= "\nINSERT INTO " . $table . " VALUES";
                    }
                    $content .= "\n(";
                    for ($j = 0; $j < $fields_amount; $j++) {
                        $row[$j] = str_replace("\n", "\\n", addslashes($row[$j]));
                        if (isset($row[$j])) {
                            $content .= '"' . $row[$j] . '"';
                        } else {
                            $content .= '""';
                        }
                        if ($j < ($fields_amount - 1)) {
                            $content .= ',';
                        }
                    }
                    $content .= ")";
                    if ((($st_counter + 1) % 100 == 0 && $st_counter != 0) || $st_counter + 1 == $rows_num) {
                        $content .= ";";
                    } else {
                        $content .= ",";
                    }
                    $st_counter = $st_counter + 1;
                }
            }
            $content .= "\n\n\n";
        }

        $date        = date("Y-m-d");
        $backup_name = $backup_name ? $backup_name : $name . ".$date.sql";

        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"" . $backup_name . "\"");
        echo $content;
        exit;
    }
}
