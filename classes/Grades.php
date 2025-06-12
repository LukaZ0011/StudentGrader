<?php
require_once(dirname(__FILE__) . '/SqlCrud.php');

class Grades extends SqlCrud
{
    public function __construct($messages)
    {
        $this->table = "grades";
        $this->where = "1=1";  // No deleted column in this database
        $this->db = $this->connect($messages);
    }

    // Save grade data (INSERT/UPDATE)
    public function save($input_json, $messages)
    {
        $this->record = $this->check_prepare($input_json, $messages);

        if ($this->action == "INSERT") {
            $this->generateDefaults();
        }

        $this->insertUpdate($this->record, $messages);
    }

    // Override insertUpdate to use proper messages for grades
    public function insertUpdate($record, $messages)
    {
        if ($this->action == "INSERT") {
            $output = "INSERT INTO $this->table (";
            $values = '(';
            foreach ($this->describe as $key => $value) {
                if ($value['Field'] != "id" && isset($record[$value['Field']])) {
                    $output .= $value['Field'] . ', ';
                    if (strpos($value['Type'], 'int') !== false || strpos($value['Type'], 'decimal') !== false) {
                        $values .= $record[$value['Field']] . ", ";
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
                    if (strpos($value['Type'], 'int') !== false || strpos($value['Type'], 'decimal') !== false) {
                        $output .= $value['Field'] . ' = ' . $record[$value['Field']] . ', ';
                    } else {
                        $output .= $value['Field'] . " = '" . $this->db->real_escape_string($record[$value['Field']]) . "', ";
                    }
                }
            }
            $sql = substr($output, 0, -2) . " WHERE id = " . intval($record['id']);
        }

        if ($this->db->query($sql)) {
            echo ($this->action == "INSERT") ? $messages->grade_ins : $messages->grade_upd;
        } else {
            echo ($this->action == "INSERT") ? $messages->grade_err : $messages->grade_upderr;
        }
    }

    // Override delete to use proper messages for grades (hard delete)
    public function delete($ID, $messages)
    {
        if (isset($ID)) {
            $sql = "DELETE FROM $this->table WHERE id = " . intval($ID);
            echo ($this->db->query($sql)) ? $messages->grade_del : $messages->grade_delerr;
        } else {
            echo $messages->grade_delerr;
        }
    }

    // Get grades with student and course information
    public function get_grades_with_details($input_obj)
    {
        if (isset($input_obj->id)) {
            $this->query = "SELECT g.*, u.ime, u.prezime, s.naziv as subject_name 
                           FROM grades g 
                           LEFT JOIN students st ON g.student_id = st.id 
                           LEFT JOIN users u ON st.user_id = u.id
                           LEFT JOIN subjects s ON g.subject_id = s.id 
                           WHERE g.id = " . intval($input_obj->id);
        } else {
            $perPage = isset($input_obj->perPage) ? intval($input_obj->perPage) : 10;
            $page = isset($input_obj->page) ? intval($input_obj->page) : 1;
            $offset = $perPage * ($page - 1);

            $this->query = "SELECT g.*, u.ime, u.prezime, s.naziv as subject_name 
                           FROM grades g 
                           LEFT JOIN students st ON g.student_id = st.id 
                           LEFT JOIN users u ON st.user_id = u.id
                           LEFT JOIN subjects s ON g.subject_id = s.id 
                           LIMIT $perPage OFFSET $offset";

            $count_query = "SELECT count(1) from grades";
            $result = $this->db->query($count_query);
            $row = mysqli_fetch_assoc($result);
            $output['count'] = $row['count(1)'];
        }

        $output['data'] = $this->get_rows();
        echo json_encode($output);
    }

    // Helper methods for grade-specific fields
    private function generateDefaults()
    {
        // Database triggers handle created_at and updated_at
        // No other defaults needed for grades
    }
}
