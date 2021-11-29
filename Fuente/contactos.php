<?php
  require_once "classes/response.model.php";
  require_once "classes/contactos.model.php";
  
  $_response = new responses;
   $_contacts = new contact;
  
   if($_SERVER['REQUEST_METHOD'] == "POST"){
       //Obtener los datos enviados
       $postBody = file_get_contents("php://input");

       //Enviar datos al modelo
       $dataArray=$_contacts->createContact($postBody);

       //Devolver respuesta
       header("Content-Type: application/json");
       if(isset($dataArray["result"]["error_id"])){
           $responseCode = $dataArray["result"]["error_id"];
           http_response_code($responseCode);
       }else{
           http_response_code(200);
       }
       echo json_encode($dataArray);
       
    //MÉTODO PUT 
    }else if($_SERVER['REQUEST_METHOD'] == "PUT"){
        //Obtener los datos enviados
        $postBody = file_get_contents("php://input");

        //Enviar datos al modelo
        $dataArray = $_contacts->putContact($postBody);

        if($dataArray==0){
            header('Content-Type: application/json');
            $dataArray = $_response->status_400();
            echo json_encode($dataArray);
        }else{

            //Devolver respuesta
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode($dataArray);
        }

    //MÉTODO DELETE
    }else if($_SERVER['REQUEST_METHOD'] == "DELETE"){
        $headers = getallheaders();
        if(isset($headers["contactId"])){
            //Recibir los datos enviados por el header
            $send = [
                "contactId" =>$headers["contactId"]
            ];
            $postBody = json_encode($send);
        }else{
            //Recibir los datos enviados
            $postBody = file_get_contents("php://input");
        }
        
        //Enviar datos al modelo
        $dataArray = $_contacts->deleteContact($postBody);

        //Delvover una respuesta 
        header('Content-Type: application/json');
        if(isset($dataArray["result"]["error_id"])){
            $responseCode = $dataArray["result"]["error_id"];
            http_response_code($responseCode);
        }else{
            http_response_code(200);
        }
        echo json_encode($dataArray);


    //MÉTODO NO PERMITIDO
    }else{
        header('Content-Type: application/json');
        $dataArray = $_response->status_405();
        echo json_encode($dataArray);
    }
?>