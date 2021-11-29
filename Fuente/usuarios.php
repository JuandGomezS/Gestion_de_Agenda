<?php
  require_once "classes/response.model.php";
  require_once "classes/usuarios.model.php";
  
  $_response = new responses;
  $_users = new users;
  
    //MÉTODO GET
    if($_SERVER['REQUEST_METHOD'] == "GET"){
        //GET para traer los contactos de un usuario
        if(isset($_GET['id'])){
            $userid = $_GET['id'];
            $userData = $_users->getContacts($userid);
            if(count($userData)==0){
                $res = $_response->status_400();
                header("Content-Type: application/json");
                echo json_encode($res);
                http_response_code(400);
            }else{
                header("Content-Type: application/json");
                echo json_encode($userData);
                http_response_code(200);
            }

        //GET para mostrar todos los usuarios
        }else{
            $listUsers = $_users->getUsers();
            header("Content-Type: application/json");
            echo json_encode($listUsers);
            http_response_code(200);
        }

    //MÉTODO POST
    }else if($_SERVER['REQUEST_METHOD'] == "POST"){
        //Obtener los datos enviados
        $postBody = file_get_contents("php://input");

        //Enviar datos al modelo
        $dataArray=$_users->createUser($postBody);

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
        $dataArray = $_users->putUser($postBody);
        

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
        if(isset($headers["userId"])){
            //Recibir los datos enviados por el header
            $send = [
                "userId" =>$headers["userId"]
            ];
            $postBody = json_encode($send);
        }else{
            //Recibir los datos enviados
            $postBody = file_get_contents("php://input");
        }
        
        //Enviar datos al modelo
        $dataArray = $_users->deleteUser($postBody);

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