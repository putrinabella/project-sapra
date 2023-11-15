<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class DatabaseManagement extends BaseController
{
    public function backup()
    {
        $dbuser        = "root";
        $dbpass        = "";
        $dbhost        = "localhost";
        $dbname        = "dbmanajemensapra";
        $timestamp     = date('Y-F-j');
        $backup_name   = "dbmanajemensapra_{$timestamp}.sql";
        $tables = array(
            "tblaplikasi", 
            "tbldetailmanajemenpeminjaman", 
            "tbldokumensekolah",
            "tblidentitasgedung", 
            "tblidentitaskelas", 
            "tblidentitaslab",
            "tblidentitaslantai", 
            "tblidentitasprasarana", 
            "tblidentitassarana",
            "tblkategorimanajemen", 
            "tblkategorimep", 
            "tblkategoripegawai",
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
            "tbltagihanair", 
            "tbltagihaninternet",
            "tbltagihanlistrik", 
            "tbluserlogs", 
            "tblwebsite", 
            "tblinventaris", 
            "tbldatainventaris"
        );

        $this->exportDatabase($dbhost, $dbuser, $dbpass, $dbname, $tables, $backup_name);
    }

    private function exportDatabase($host, $user, $pass, $name, $tables = false, $backup_name = false)
{
    $mysqli = new \mysqli($host, $user, $pass, $name);
    $mysqli->select_db($name);
    $mysqli->query("SET NAMES 'utf8'");

    $content = "";

    $queryTables = $mysqli->query('SHOW TABLES');
    while ($row = $queryTables->fetch_row()) {
        $target_tables[] = $row[0];
    }
    if ($tables !== false) {
        $target_tables = array_intersect($target_tables, $tables);
    }

    foreach ($target_tables as $table) {
        $result        = $mysqli->query('SELECT * FROM ' . $table);
        $fields_amount = $result->field_count;
        $rows_num      = $mysqli->affected_rows;
        $res           = $mysqli->query('SHOW CREATE TABLE ' . $table);
        $tableMLine    = $res->fetch_row();
        $content       .= "\n\n" . preg_replace('/CREATE TABLE/', 'CREATE TABLE IF NOT EXISTS', $tableMLine[1], 1) . ";\n\n";

        for ($i = 0, $st_counter = 0; $i < $fields_amount; $i++, $st_counter = 0) {
            while ($row = $result->fetch_row()) {
                if ($st_counter % 100 == 0 || $st_counter == 0) {
                    $content .= "\nINSERT IGNORE INTO `" . $table . "` VALUES";
                }
                $content .= "\n(";
                for ($j = 0; $j < $fields_amount; $j++) {
                    $row[$j] = str_replace("\n", "\\n", addslashes($row[$j]));
                
                    if ($row[$j] === "") {
                        $content .= 'NULL';
                    } else {
                        $content .= '"' . $row[$j] . '"';
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


    public function restoreView() {
        return view('dbmanagementView/restore/index');
    }
    
    public function restore() {
        $file = $this->request->getFile('database');
    
        // Add drop database
    
        if (!$file->isValid()) {
            return redirect()->to(site_url('restore'))->with('error', 'Invalid File');
        }
    
        $extension = $file->getClientExtension();
        if ($extension !== 'sql') {
            return redirect()->to(site_url('restore'))->with('error', 'Invalid File Type. Please upload an SQL file.');
        }
    
        $db = \Config\Database::connect();
        $output = '';
        $content = '';
        $count = 0;
        $file_data = file($file->getTempName());
    
        foreach ($file_data as $row) {
            $row = preg_replace('/\s*--.*$/', '', $row);
    
            $start_character = substr(trim($row), 0, 2);
    
            if ($start_character !== '/*' && $start_character !== '//' && $row !== '') {
                $output .= $row;
                $end_character = substr(trim($row), -1, 1);
    
                if ($end_character == ';') {
                    $query = trim($output);
                
                    $table = $this->extractTableName($query);
                
                    if ($this->tableExists($table, $db)) {
                        return redirect()->to(site_url('restore'))->with('error', 'Table ' . $table . ' already exists');
                    }
                
                    $content .= "\nINSERT IGNORE INTO `" . $table . "` VALUES"; // Modified line
                
                    $statement = $db->query($query);
                
                    if (!$statement) {
                        $count++;
                
                        $error = $db->error();
                        echo "Error executing query: $query<br>";
                        echo "MySQL Error: " . $error['message'] . "<br>";
                    }
                
                    $output = '';
                }
                
            }
        }
    
        if ($count > 0) {
            return redirect()->to(site_url('restore'))->with('error', 'There is an error in Database Import');
        } else {
            return redirect()->to(site_url('restore'))->with('success', 'Database Successfully Imported');
        }
    }
    
    private function extractTableName($query) {
        $parts = explode(' ', $query);
        return $parts[2]; 
    }
    
    private function tableExists($table, $db) {
        return $db->tableExists($table);
    }
    
}