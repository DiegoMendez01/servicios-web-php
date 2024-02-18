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
        if(!empty($status) AND !empty($message)){
            $response = array("status" => $status,"message"=>$message);
            echo json_encode($response, JSON_PRETTY_PRINT);
        }
    }
    
    function processList(){
        //se verifica la accion y se    verifica que actue sobre la tabla tareas
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
    
    function processSave()
    {
        // Se comprueba que trabaja en la tabla tareas
        if($_GET['action']=='tareas'){
            //Decodifica un string de JSON
            $obj    = json_decode( file_get_contents('php://input') );
            $objArr = (array)$obj;
            
            if(empty($objArr)){
                $this->response(422,"error","Nothing to add. Check json");
            }else if(isset($obj->titulo)){
                $tareasDB = new TareasDB();
                $tareasDB->create($obj->titulo, $obj->descripcion, $obj->prioridad );
                $this->response(200,"success","new record added");
            }else{
                $this->response(422,"error","The property is not defined");
            }
        } else{
            $this->response(400);
        }
    }
    
    function processUpdate()
    {
        if(isset($_GET['action']) AND isset($_GET['id'])){
            if($_GET['action']=='tareas'){
                $obj = json_decode( file_get_contents('php://input') );
                $objArr = (array)$obj;
                
                if(empty($objArr)){
                    $this->response(422,"error","Nothing to add. Check json");
                }else if(isset($obj->titulo)){
                    $tareasDB = new TareasDB();
                    $tareasDB->update($_GET['id'], $obj->titulo, $obj->descripcion, $obj->prioridad );
                    $this->response(200,"success","Record updated");
                }else{
                    $this->response(422,"error","The property is not defined");
                }
                exit;
            }
        }
        $this->response(400);
    }
    
    function processDelete(){
        if(isset($_GET['action']) AND isset($_GET['id'])){
            if($_GET['action']=='tareas'){
                $tareasDB = new TareasDB();
                $tareasDB->delete($_GET['id']);
                $this->response(204);
                exit;
            }
        }
        $this->response(400);
    }
}

?>