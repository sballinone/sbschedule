<?php
class CAppointment {
    public $appointmentid;
    public $userid;
    public $title;
    public $address;
    public $apptDate;
    public $created;
    public $updated;
    public $response;
    public $max;
    public $enabled;


    public function __construct($db, $appointmentid) {
        $sql = "SELECT * FROM appointment WHERE appointmentid = ".$appointmentid;
        $result = $db->query($sql);
        if($result->num_rows) {
            $data = $result->fetch_assoc();
            
            $this->appointmentid = $data['appointmentid'];
            $this->userid = $data['userid'];
            $this->title = $data['title'];
            $this->address = $data['address'];
            $this->apptDate = $data['apptDate'];
            $this->created = $data['created'];
            $this->updated = $data['updated'];
            $this->response = $data['response'];
            $this->max = $data['max'];
            $this->enabled = $data['enabled'];
        } else {
            $this->err = 1;
        }
    }
}