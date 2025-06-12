<?php
require_once(dirname(__FILE__) . '/SqlCrud.php');

class Students extends SqlCrud
{
    public function __construct($messages)
    {
        $this->table = "students";
        $this->where = "1=1";
        $this->db = $this->connect($messages);
    }

    public function save($input_json, $messages)
    {
        $this->record = $this->check_prepare($input_json, $messages);
        if ($this->action == "INSERT") {
            $this->generateDefaults();
        }
        $this->insertUpdate($this->record, $messages);
    }

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
            echo ($this->action == "INSERT") ? $messages->student_ins : $messages->student_upd;
        } else {
            echo ($this->action == "INSERT") ? $messages->student_err : $messages->student_upderr;
        }
    }

    public function delete($ID, $messages)
    {
        if (isset($ID)) {
            $sql = "DELETE FROM $this->table WHERE id = " . intval($ID);
            echo ($this->db->query($sql)) ? $messages->student_del : $messages->student_delerr;
        } else {
            echo $messages->student_delerr;
        }
    }

    private function generateDefaults()
    {
        // Database triggers handle created_at and updated_at
    }
}
?>
