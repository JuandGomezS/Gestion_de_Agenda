<?php

require_once "dbconnection/connectDB.php";
require_once "response.model.php";

class contact extends connect{
    private $table = "contactos";
    private $contactId = "";
    private $names = "";
    private $tel = "";
    private $telType = "";
    private $kin = "";
    private $fk = "";

    //MÉTODOS

    public function getContact($id){
        $query = "Select * from " . $this->table . " WHERE id_contactos = '$id'";
        return parent::getData($query);
    }

    public function createContact($object){
        $_responses = new responses;
        $data = json_decode($object,true);

        if(!isset($data['names']) || !isset($data['tel'])|| !isset($data['telType'])|| !isset($data['kin']) || !isset($data['fk'])){
            return $_responses->status_400();
        }

        $this->names=$data['names'];
        $this->tel=$data['tel'];
        $this->telType=$data['telType'];
        $this->kin=$data['kin'];
        $this->fk=$data['fk'];

        $resp = $this->insertContact();

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

    private function insertContact(){
        try {
            $query = "INSERT INTO " . $this->table . " (nombre, numero, tipo_numero, parentesco, fk_usuarios)
            values
            ('" . $this->names . "','" . $this->tel . "','" . $this->telType ."','" . $this->kin . "','"  . $this->fk ."')"; 
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

    public function putContact($object){
        $_responses = new responses;
        $data = json_decode($object,true);
        
        if(!isset($data['contactId'])){
            return 0;
        }else{
            $cdata= $this->getContact($data['contactId']);
            $this->contactId= $data['contactId'];
            $this->names= isset($data['names']) ? $data['names'] : $cdata[0]['nombre'];
            $this->tel= isset($data['tel']) ? $data['tel'] : $cdata[0]['numero'];
            $this->telType= isset($data['telType']) ? $data['telType'] : $cdata[0]['tipo_numero'];
            $this->kin= isset($data['kin']) ? $data['kin'] : $cdata[0]['parentesco'];
    
            $resp = $this->modifyContact();
    
            if($resp){
                $response = $_responses->response;
                $response["result"] = array(
                    "contactId" => $this->contactId
                );
                return $response;
            }else{
                return 0;
            }
        }
    }

    private function modifyContact(){
        $query = "UPDATE " . $this->table . " SET nombre ='" . $this->names . "', numero= '" . $this->tel . "', tipo_numero = '" . $this->telType . "', parentesco = '" . $this->kin . "' WHERE id_contactos = " . $this->contactId ; 
        $resp = parent::nonQuery($query);
        if($resp >= 1){
             return $resp;
        }else{
            return 0;
        }
    }

    public function deleteContact($object){
        $_responses = new responses;
        $data = json_decode($object,true);

        if(!isset($data['contactId'])){
            return $_responses->status_400();
        }else{
            $this->contactId = $data['contactId'];
            $resp = $this->removeContact();
            if($resp){
                $response = $_responses->response;
                $response["result"] = array(
                    "contactId" => $this->contactId
                );
                return $response;
            }else{
                return $_responses->status_400();
            }
        }

    }

    private function removeContact(){
        $query = "DELETE FROM " . $this->table . " WHERE id_contactos= '" . $this->contactId . "'";
        $resp = parent::nonQuery($query);
        if($resp >= 1 ){
            return $resp;
        }else{
            return 0;
        }
    }






}


?>