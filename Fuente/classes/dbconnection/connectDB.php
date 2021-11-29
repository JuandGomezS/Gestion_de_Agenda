<?php
    class connect{ 
        private $server;
        private $user;
        private $password;
        private $database;
        private $port;
        private $connection;

        function __construct(){
            $listData=$this->connectData();
            foreach($listData as $key=> $value){
                $this->server=$value['server'];
                $this->user=$value['user'];
                $this->password=$value['password'];
                $this->database=$value['database'];
                $this->port=$value['port'];
            }
            
            $this->connection=new mysqli($this->server, $this->user, $this->password, $this->database, $this->port); 
            
            if ($this->connection->connect_errno){
                echo "Conexión a la BD fallida";
                die();
            }
        }

        private function connectData(){
            $path=dirname(__FILE__);
            $jsondata=file_get_contents($path . "/". "config");
            
            return json_decode($jsondata, true); 
        }

        private function toUTF8($array){
            array_walk_recursive($array, function(&$item,$key){
                if(!mb_detect_encoding($item, 'utf-8', true)){
                    $item = utf8_encode($item);
                }
            });
            return $array;
        }

        public function getData($sqlstr){
            $results= $this->connection->query($sqlstr);
            $resulArray= array();
            foreach($results as $key){
                $resulArray[]=$key;
            }
            return $this->toUTF8($resulArray);
        }

        public function nonQuery($sqlstr){
            try {
                $results= $this->connection->query($sqlstr);
                return $this->connection->affected_rows;
            } catch (Exception $e) {
                return 0;
            }
        }

        public function nonQueryId($sqlstr){
            try {
                $results= $this->connection->query($sqlstr);
                $rows=$this->connection->affected_rows;
                if($rows>=1){
                    return $this->connection->insert_id;
                }else{
                    return 0;
                }
            } catch (Exception $e) {
                return 0;
            }
        }


}
