<?php
require_once(dirname(__FILE__) . '/SqlCrud.php');

class Teachers extends SqlCrud
{
    public function __construct($messages)
    {
        $this->table = "teachers";
        $this->where = "1=1";  // No deleted column in this database
        $this->db = $this->connect($messages);
    }

    // Save teacher data (INSERT/UPDATE)
    public function save($input_json, $messages)
    {
        $this->record = $this->check_prepare($input_json, $messages);
        $this->insertUpdate($this->record, $messages);
    }

    // Override insertUpdate to use proper messages for teachers
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
            echo ($this->action == "INSERT") ? $messages->teacher_ins : $messages->teacher_upd;
        } else {
            echo ($this->action == "INSERT") ? $messages->teacher_err : $messages->teacher_upderr;
        }
    }

    // Override delete to use hard delete
    public function delete($ID, $messages)
    {
        if (isset($ID)) {
            $sql = "DELETE FROM $this->table WHERE id = " . intval($ID);
            echo ($this->db->query($sql)) ? $messages->teacher_del : $messages->teacher_delerr;
        } else {
            echo $messages->teacher_delerr;
        }
    }

    // Get teachers with user information
    public function get_teachers_with_user($input_obj)
    {
        if (isset($input_obj->id)) {
            $this->query = "SELECT t.*, u.ime, u.prezime, u.email 
                           FROM teachers t 
                           LEFT JOIN users u ON t.user_id = u.id 
                           WHERE t.id = " . intval($input_obj->id);
        } else {
            $perPage = isset($input_obj->perPage) ? intval($input_obj->perPage) : 10;
            $page = isset($input_obj->page) ? intval($input_obj->page) : 1;
            $offset = $perPage * ($page - 1);

            $this->query = "SELECT t.*, u.ime, u.prezime, u.email 
                           FROM teachers t 
                           LEFT JOIN users u ON t.user_id = u.id 
                           LIMIT $perPage OFFSET $offset";

            $count_query = "SELECT count(1) from teachers";
            $result = $this->db->query($count_query);
            $row = mysqli_fetch_assoc($result);
            $output['count'] = $row['count(1)'];
        }

        $output['data'] = $this->get_rows();
        echo json_encode($output);
    }
}
