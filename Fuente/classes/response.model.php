<?php 

class responses{

    public  $response = [
        'status' => "ok",
        "result" => array()
    ];


    public function status_405(){
        $this->response['status'] = "status";
        $this->response['result'] = array(
            "status_id" => "405",
            "status_msg" => "Not allowed Method"
        );
        return $this->response;
    }

    public function status_200($value = "OK"){
        $this->response['status'] = "status";
        $this->response['result'] = array(
            "status_id" => "200",
            "status_msg" => $value
        );
        return $this->response;
    }


    public function status_400(){
        $this->response['status'] = "BAD REQUEST";
        $this->response['result'] = array(
            "status_id" => "400",
            "status_msg" => "Bad request"
        );
        return $this->response;
    }


    public function status_500($value = "Internal server error"){
        $this->response['status'] = "status";
        $this->response['result'] = array(
            "status_id" => "500",
            "status_msg" => $value
        );
        return $this->response;
    }


    public function status_401($value = "Not Authorize"){
        $this->response['status'] = "status";
        $this->response['result'] = array(
            "status_id" => "401",
            "status_msg" => $value
        );
        return $this->response;
    }
}

?>