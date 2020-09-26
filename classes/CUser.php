<?php
class CUser {
    public $userid;
    public $firstname;
    public $lastname;
    public $email;
    public $lang;
    public $active;
    public $admin;

    public function __construct($db) {
        $sql = "SELECT * FROM users WHERE userid = ".$_SESSION['userid'];
        $result = $db->query($sql);
        
        if($result->num_rows) {
            $data = $result->fetch_assoc();
            
            $this->userid = $data['userid'];
            $this->email = $data['email'];
            $this->firstname = $data['firstname'];
            $this->lastname = $data['lastname'];
            $this->lang = $data['lang'];
            $this->active = $data['active'];
            $this->admin = $data['admin'];
        } else {
            $this->err = 1;
        }
    }
}