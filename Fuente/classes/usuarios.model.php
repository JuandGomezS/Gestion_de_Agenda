<?php
require_once "dbconnection/connectDB.php";
require_once "response.model.php";

class users extends connect{
    private $table = "usuarios";
    private $contacts = "contactos";
    private $userid = "";
    private $identification = "";
    private $names = "";
    private $lastName = "";
    private $birthDate = "";
    private $gender = "";

    public function getUsers(){
        $query = "Select * from " . $this->table;
        $data = parent::getData($query);
        return ($data);
    }

    public function getUser($id){
        $query = "Select * from " . $this->table . " WHERE id_usuarios = '$id'";
        return parent::getData($query);
    }

    public function getContacts($id){
        $query = "Select id_contactos, nombre, numero, tipo_numero, parentesco, fk_usuarios from ". $this->table . " AS u INNER JOIN " . $this->contacts . " AS c ON c.fk_usuarios = u.id_usuarios WHERE u.id_usuarios = ". $id;
        return parent::getData($query);
    }

    public function createUser($object){
        $_responses = new responses;
        $data = json_decode($object,true);

        if(!isset($data['identification']) || !isset($data['names']) || !isset($data['lastName'])|| !isset($data['birthDate'])|| !isset($data['gender'])){
            return $_responses->status_400();
        }

        $this->identification= $data['identification'];
        $this->names= $data['names'];
        $this->lastName= $data['lastName'];
        $this->birthDate= $data['birthDate'];
        $this->gender= $data['gender'];


        $resp = $this->insertUser();


        if($resp){
            $response = $_responses->response;
            $response["result"] = array(
                "userid" => $resp
            );
            return $response;
        }else{
            return $_responses->status_400();
        }
    }

    private function insertUser(){
        try {
            $query = "INSERT INTO " . $this->table . " (identificacion, nombres, apellidos, fecha_nacimiento, genero)
            values
            ('" . $this->identification . "','" . $this->names . "','" . $this->lastName ."','" . $this->birthDate . "','"  . $this->gender ."')"; 
            $resp = parent::nonQueryId($query);
            if($resp){
                 return $resp;
            }else{
                return 0;
            }
        } catch (Exception $e) {
            return 0;
        }
    }

    public function putUser($object){
        $_responses = new responses;
        $data = json_decode($object,true);
        
        
        if(!isset($data['userId'])){
            return 0;
        }else{
            
            $cdata= $this->getUser($data['userId']);
            $this->userid= $data['userId'];
            $this->identification= isset($data['identification']) ? $data['identification'] : $cdata[0]['identificacion'];
            $this->names= isset($data['names']) ? $data['names'] : $cdata[0]['nombres'];
            $this->lastName= isset($data['lastName']) ? $data['lastName'] : $cdata[0]['apellidos'];
            $this->birthDate= isset($data['birthDate']) ? $data['birthDate'] : $cdata[0]['fecha_nacimiento'];
            $this->gender= isset($data['gender']) ? $data['gender'] : $cdata[0]['genero'];
    
            $resp = $this->modifyUser();
    
            if($resp){
                $response = $_responses->response;
                $response["result"] = array(
                    "userid" => $this->userid
                );
                return $response;
            }else{
                return 0;
            }
        }

    }

    private function modifyUser(){
        $query = "UPDATE " . $this->table . " SET identificacion ='" . $this->identification . "', nombres= '" . $this->names . "', apellidos = '" . $this->lastName . "', fecha_nacimiento = '" .
        $this->birthDate . "', genero = '" . $this->gender . "' WHERE id_usuarios = '" . $this->userid . "'"; 
        $resp = parent::nonQuery($query);
        echo $resp;
        if($resp >= 1){
             return $resp;
        }else{
            return 0;
        }
    }

    public function deleteUser($object){
        $_responses = new responses;
        $data = json_decode($object,true);

        if(!isset($data['userId'])){
            return $_responses->status_400();
        }else{
            $this->userid = $data['userId'];
            $resp = $this->removeUser();
            if($resp){
                $response = $_responses->response;
                $response["result"] = array(
                    "userId" => $this->userid
                );
                return $response;
            }else{
                return $_responses->status_400();
            }
        }

    }

    private function removeUser(){
        $query = "DELETE FROM " . $this->table . " WHERE id_usuarios= '" . $this->userid . "'";
        $resp = parent::nonQuery($query);
        if($resp >= 1 ){
            return $resp;
        }else{
            return 0;
        }
    }

}












?>