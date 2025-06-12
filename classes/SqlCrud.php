<?php
require_once(dirname(__FILE__) . '/../config/db_credential.php');

class SqlCrud
{
    public $db;
    public $table;
    public $where;
    public $action;
    public $describe;
    public $query;  // Changed from private to public
    public $record = [];  // Changed from private to public

    // Connect to the database
    public function connect($messages = null)
    {
        $this->db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        if ($this->db->connect_errno) {
            $error_msg = $messages ? $messages->db_connection_err : '{"h_message":"Database connection failed","h_errcode":666}';
            throw new Exception($error_msg);
        }
        $this->db->set_charset("utf8");
        return $this->db;
    }

    // Fetch rows from a query
    public function get_rows()
    {
        if (empty($this->query)) {
            throw new Exception('{"h_message":"Query is empty","h_errcode":664}');
        }

        $result = $this->db->query($this->query);
        if (!$result) {
            throw new Exception('{"h_message":"Query execution failed: ' . $this->db->error . '","h_errcode":665}');
        }
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    // Get count of records
    private function get_count()
    {
        $count = "SELECT count(1) from $this->table where $this->where";
        $result = $this->db->query($count);
        if (!$result) {
            return 0;
        }
        $row = mysqli_fetch_assoc($result);
        return $row['count(1)'];
    }

    // Fetch records (with pagination)
    public function get_records($input_obj)
    {
        if (isset($input_obj->id)) {
            $this->query = "SELECT * FROM $this->table where id = " . intval($input_obj->id);
        } else {
            $perPage = isset($input_obj->perPage) ? intval($input_obj->perPage) : 10;
            $page = isset($input_obj->page) ? intval($input_obj->page) : 1;
            $offset = $perPage * ($page - 1);
            $this->query = "SELECT * FROM $this->table WHERE $this->where LIMIT $perPage OFFSET $offset";
            $output['count'] = $this->get_count();
        }
        $output['data'] = $this->get_rows();
        echo json_encode($output);
    }

    // Validate and prepare data for INSERT/UPDATE
    public function check_prepare($input_json, $messages)
    {
        $this->query = 'DESCRIBE ' . $this->table;
        $this->describe = $this->get_rows();
        $inputArray = json_decode($input_json, true);

        foreach ($this->describe as $key => $value) {
            if ($value['Field'] == "id") {
                $this->action = isset($inputArray['id']) ? "UPDATE" : "INSERT";
                continue;
            }
            if ($value['Null'] == 'NO' && !isset($inputArray[$value['Field']])) {
                throw new Exception(str_replace("#field", $value['Field'], $messages->mandatory_field));
            }
        }

        return $inputArray;
    }

    // Generate INSERT/UPDATE query
    public function insertUpdate($record, $messages)
    {
        if ($this->action == "INSERT") {
            $output = "INSERT INTO $this->table (";
            $values = '(';
            foreach ($this->describe as $key => $value) {
                if ($value['Field'] != "id" && isset($record[$value['Field']])) {
                    $output .= $value['Field'] . ', ';
                    if (strpos($value['Type'], 'int') !== false) {
                        $values .= intval($record[$value['Field']]) . ", ";
                    } else {
                        $values .= "'" . $this->db->real_escape_string($record[$value['Field']]) . "', ";
                    }
                }
            }
            $sql = substr($output, 0, -2) . ") values " . substr($values, 0, -2) . ")";
        } else {
            $output = "UPDATE $this->table SET ";
            foreach ($this->describe as $key => $value) {
                if ($value['Field'] != "id" && isset($record[$value['Field']])) {
                    if (strpos($value['Type'], 'int') !== false) {
                        $output .= $value['Field'] . ' = ' . intval($record[$value['Field']]) . ', ';
                    } else {
                        $output .= $value['Field'] . " = '" . $this->db->real_escape_string($record[$value['Field']]) . "', ";
                    }
                }
            }
            $sql = substr($output, 0, -2) . " WHERE id = " . intval($record['id']);
        }

        if ($this->db->query($sql)) {
            echo ($this->action == "INSERT") ? $messages->user_ins : $messages->user_upd;
        } else {
            echo ($this->action == "INSERT") ? $messages->user_err : $messages->user_upderr;
        }
    }

    // Soft delete a record
    public function delete($ID, $messages)
    {
        if (isset($ID)) {
            $sql = "UPDATE $this->table SET deleted = 1 WHERE ID = " . intval($ID);
            echo ($this->db->query($sql)) ? $messages->user_del : $messages->user_delerr;
        } else {
            echo $messages->user_delerr;
        }
    }

    // Hard delete a record (permanent deletion)
    public function hard_delete($ID, $messages)
    {
        if (isset($ID)) {
            $sql = "DELETE FROM $this->table WHERE ID = " . intval($ID);
            echo ($this->db->query($sql)) ? $messages->user_del : $messages->user_delerr;
        } else {
            echo $messages->user_delerr;
        }
    }
}
