<?php

require_once ('TareasDB.php');

class TareasAPI
{
    public function API()
    {
        header('ContentType:application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        
        switch($method)
        {
            case 'GET':
                $this->processList();
                break;
            case 'POST':
                $this->processSave();
                break;
            case 'PUT':
                $this->processUpdate();
            case 'DELETE':
                 $this->processDelete();
            default:
                echo 'METODO NO SOPORTADO';
                break;
        }
    }
    
    function response($code = 200, $status = "", $message = "")
    {
        http_response_code($code);
        if(!empty($status) && !empty($message)){
            $response = array("status" => $status,"message"=>$message);
            echo json_encode($response, JSON_PRETTY_PRINT);
        }
    }
    
    function processList(){
        //se verifica la accion y se verifica que actue sobre la tabla tareas
        if($_GET['action'] == 'tareas'){
            // aquí se instancia un objeto de la clase tareasdb
            $tareasDB = new TareasDB();
            // se solicita un registro por id
            if(isset($_GET['id'])){
                $response = $tareasDB->getOneById($_GET['id']);
                // aquí se muestra la información en formato json un registro por id
                echo json_encode($response, JSON_PRETTY_PRINT);
            }else{
                // de lo contrario, manda la lista completa
                $response = $tareasDB->dameLista();
                // muestra la lista en formato json
                echo json_encode($response, JSON_PRETTY_PRINT);
            }
        }else{
            $this->response(400);
        }
    }
}

?>