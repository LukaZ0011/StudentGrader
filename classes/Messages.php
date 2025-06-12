<?php
// Messages class for standardized error and success messages
class Messages
{
    public $login_err = '{"h_message":"login redirect","h_errcode":999}';
    public $request_err = '{"h_message":"request error","h_errcode":998}';
    public $procedure_err = '{"h_message":"method error","h_errcode":997}';
    public $project_err = '{"h_message":"project error","h_errcode":996}';
    public $logout = '{"h_message":"Successfully logged out","h_errcode":0}';
    public $wrong_login = '{"h_message":"Wrong username or password. Please try again","h_errcode":111}';
    public $mandatory_field = '{"h_message":"Field #field is required. Please try again","h_errcode":112}';
    public $numeric_field = '{"h_message":"Field #field must have a numeric value","h_errcode":119}';

    // User messages
    public $user_ins = '{"h_message":"User successfully created.","h_errcode":0}';
    public $user_err = '{"h_message":"Error occurred while creating user.","h_errcode":120}';
    public $user_upd = '{"h_message":"User successfully updated.","h_errcode":0}';
    public $user_upderr = '{"h_message":"Error occurred while updating user.","h_errcode":120}';
    public $user_del = '{"h_message":"User successfully deleted.","h_errcode":0}';
    public $user_delerr = '{"h_message":"Error occurred while deleting user.","h_errcode":120}';

    // Student messages
    public $student_ins = '{"h_message":"Student successfully created.","h_errcode":0}';
    public $student_err = '{"h_message":"Error occurred while creating student.","h_errcode":121}';
    public $student_upd = '{"h_message":"Student successfully updated.","h_errcode":0}';
    public $student_upderr = '{"h_message":"Error occurred while updating student.","h_errcode":121}';
    public $student_del = '{"h_message":"Student successfully deleted.","h_errcode":0}';
    public $student_delerr = '{"h_message":"Error occurred while deleting student.","h_errcode":121}';

    // Grade messages
    public $grade_ins = '{"h_message":"Grade successfully created.","h_errcode":0}';
    public $grade_err = '{"h_message":"Error occurred while creating grade.","h_errcode":122}';
    public $grade_upd = '{"h_message":"Grade successfully updated.","h_errcode":0}';
    public $grade_upderr = '{"h_message":"Error occurred while updating grade.","h_errcode":122}';
    public $grade_del = '{"h_message":"Grade successfully deleted.","h_errcode":0}';
    public $grade_delerr = '{"h_message":"Error occurred while deleting grade.","h_errcode":122}';

    // Subject messages  
    public $subject_ins = '{"h_message":"Subject successfully created.","h_errcode":0}';
    public $subject_err = '{"h_message":"Error occurred while creating subject.","h_errcode":123}';
    public $subject_upd = '{"h_message":"Subject successfully updated.","h_errcode":0}';
    public $subject_upderr = '{"h_message":"Error occurred while updating subject.","h_errcode":123}';
    public $subject_del = '{"h_message":"Subject successfully deleted.","h_errcode":0}';
    public $subject_delerr = '{"h_message":"Error occurred while deleting subject.","h_errcode":123}';

    // Teacher messages
    public $teacher_ins = '{"h_message":"Teacher successfully created.","h_errcode":0}';
    public $teacher_err = '{"h_message":"Error occurred while creating teacher.","h_errcode":124}';
    public $teacher_upd = '{"h_message":"Teacher successfully updated.","h_errcode":0}';
    public $teacher_upderr = '{"h_message":"Error occurred while updating teacher.","h_errcode":124}';
    public $teacher_del = '{"h_message":"Teacher successfully deleted.","h_errcode":0}';
    public $teacher_delerr = '{"h_message":"Error occurred while deleting teacher.","h_errcode":124}';

    // Enrollment messages
    public $enrollment_ins = '{"h_message":"Enrollment successfully created.","h_errcode":0}';
    public $enrollment_err = '{"h_message":"Error occurred while creating enrollment.","h_errcode":125}';
    public $enrollment_upd = '{"h_message":"Enrollment successfully updated.","h_errcode":0}';
    public $enrollment_upderr = '{"h_message":"Error occurred while updating enrollment.","h_errcode":125}';
    public $enrollment_del = '{"h_message":"Enrollment successfully deleted.","h_errcode":0}';
    public $enrollment_delerr = '{"h_message":"Error occurred while deleting enrollment.","h_errcode":125}';

    // General database errors
    public $db_connection_err = '{"h_message":"Database connection failed","h_errcode":666}';
    public $no_data = '{"h_message":"No data found","h_errcode":404}';
    public $access_denied = '{"h_message":"Access denied","h_errcode":403}';
}
