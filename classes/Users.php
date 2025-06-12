<?php
require_once(dirname(__FILE__) . '/SqlCrud.php');

class Users extends SqlCrud
{
    public function __construct($messages)
    {
        $this->table = "users";
        $this->where = "1=1";  // No deleted column in this database
        $this->db = $this->connect($messages);
    }

    // User login (simple version for student project)
    public function login($input_obj, $messages)
    {
        $username = $this->db->real_escape_string($input_obj->username);
        $password = $input_obj->password;

        $this->query = "SELECT * FROM $this->table WHERE email = '$username' AND password = '$password'";
        $rows = $this->get_rows();

        if (!empty($rows)) {
            $_SESSION = $rows[0];
            echo json_encode($rows[0]);
        } else {
            echo $messages->wrong_login;
        }
    }

    // Save user data (INSERT/UPDATE)
    public function save($input_json, $messages)
    {
        $this->record = $this->check_prepare($input_json, $messages);

        if ($this->action == "INSERT") {
            $this->generatePassword();
            $this->generateDefaults();
        }

        $this->insertUpdate($this->record, $messages);
    }

    // Override insertUpdate to use proper messages for users
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

    // Override delete to use proper messages for users (hard delete)
    public function delete($ID, $messages)
    {
        if (isset($ID)) {
            $sql = "DELETE FROM $this->table WHERE id = " . intval($ID);
            echo ($this->db->query($sql)) ? $messages->user_del : $messages->user_delerr;
        } else {
            echo $messages->user_delerr;
        }
    }

    // Helper methods for user-specific fields
    private function generatePassword()
    {
        if (!isset($this->record['password']) || empty($this->record['password'])) {
            $this->record['password'] = '123456'; // Default password (plain text)
        }
        // Keep password as plain text for student project
    }

    private function generateDefaults()
    {
        if (!isset($this->record['role'])) {
            $this->record['role'] = 'teacher'; // Default role
        }
        // No deleted column in this database structure
    }
}
