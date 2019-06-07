<?php


require_once 'vendor/autoload.php';

$app = new \Slim\Slim();

$db  = new mysqli('localhost', 'root', '' , 'veranumdb');

//configuracion de cabezeras
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
$method = $_SERVER['REQUEST_METHOD'];
if($method == "OPTIONS") {
    die();
}




// $app->get("/pruebas", function() use ($app, $db){
//     echo "probanding";
//     var_dump($db);
// });


// $app->get("/probando", function() use ($app){
//     echo "holitas";
// });


//aqui esta el insert de la reserva
$app->post('/reserva' , function() use($app, $db){

        $total = $app->request->post('total');
        $fechas = $app->request->post('fecha');
        $dias = $app->request->post('dias');
        $id_habitacion = $app->request->post('id_habitacion');
        $id_usuario = $app->request->post('id_usuario');
        
        //$data = json_decode($json,true);
    
        //     if(!isset($data['fecha'])){
         //         $data['fecha'] = null;
    //    }
    //    if(!isset($data['total'])){
    //     $data['total'] = null;
    //     }
    //     if(!isset($data['dias'])){
    //     $data['dias'] = null;
    //     }

        // print_r($total);
        // print_r($fechas);
        // print_r($dias);
        // print_r($data);

        $query = "INSERT INTO reserva VALUES(NULL,".
        "'{$total}',".
        "'{$fechas}',".
        "'{$id_habitacion}',".
        "'{$id_usuario}',".
        "'{$dias}'".
        ");";


        $insert = $db->query($query);

        $result =  array(
            'status' => 'error',
            'code' => 404,
            'message' => 'Fallo'
        );
        
        if($insert){
            $result =  array(
                'status' => 'success',
                'code' => 200,
                'message' => 'Creado'
            );
        }
        echo json_encode($result);
});

//Metodo GET reserva

$app->get('/reserva' , function() use ($db,$app){
    $sql = 'SELECT * FROM reserva ORDER BY id DESC;';
    $query = $db -> query($sql);

    $productos = array();
    while($producto = $query->fetch_assoc()){
        $productos[] = $producto;
    }
    $result =  array(
        'status' => 'success',
        'code' => 200,
        'data' => $productos
    );

    echo json_encode($result);

});

//actualizar PUT reserva

$app->post('/update-reserva/:id' , function($id) use($app, $db){

    $total = $app->request->post('total');
    $fecha = $app->request->post('fecha');
    $dias = $app->request->post('dias');
    $id_habitacion = $app->request->post('id_habitacion');
    $id_usuario = $app->request->post('id_usuario');
    
    // $json = $app->request->post('json');
    // $data = json_decode($json,true);
    $reservaOrigin = $db->query("SELECT * FROM reserva WHERE id = $id")->fetch_array();

    $total = $total ?:  $reservaOrigin['total'];
    $fecha = $fecha ?:  $reservaOrigin['fecha'];
    $id_habitacion = $id_habitacion ?:  $reservaOrigin['id_habitacion'];
    $id_usuario = $id_usuario ?:  $reservaOrigin['id_usuario'];
    $dias = $dias ?:  $reservaOrigin['dias'];

    $sql = "UPDATE reserva SET total = $total,  fecha = '$fecha', id_habitacion = $id_habitacion, id_usuario = $id_usuario, dias =  $dias WHERE id = $id";
    // "'{$total}',".
    // "'{$fechas}',".
    // "'{$id_habitacion}',".
    // "'{$id_usuario}',".
    // "'{$dias}'".

    
    // var_dump($sql);
    $query = mysqli_query($db, $sql);

    // $query = $db->query($sql);

    //  print_r($query);
    //  die();
    
    if($query){
        $result =  array(
            'status' => 'success',
            'code' => 200,
            'message' => 'actualizado'
        );
    }else{
        $result =  array(
            'status' => 'failed',
            'code' => 404,
            'message' => 'no actualizado'
        );
    }

    echo json_encode($result);
});


//Eliminar DELETE reserva

$app->get('/delete-reserva/:id' , function($id) use ($db, $app){
    $sql = 'DELETE FROM reserva WHERE id = '.$id;
    $query = $db->query($sql);

    if($query){
        $result =  array(
            'status' => 'success',
            'code' => 200,
            'message' => 'Borrado'
        );
    }else{
        $result =  array(
            'status' => 'failed',
            'code' => 404,
            'message' => 'no se a borrado'
        );
    }

    echo json_encode($result);
});
//PAGO
//insert pago
$app->post('/pago' , function() use($app, $db){

    $fecha = $app->request->post('fecha');
    $costo = $app->request->post('costo');
    $id_reserva = $app->request->post('id_reserva');

    $query = "INSERT INTO pago VALUES(NULL,".
    "'{$fecha}',".
    "'{$costo}',".
    "'{$id_reserva}'".
    ");";


    $insert = $db->query($query);

    $result =  array(
        'status' => 'error',
        'code' => 404,
        'message' => 'Fallo'
    );
    
    if($insert){
        $result =  array(
            'status' => 'success',
            'code' => 200,
            'message' => 'Creado'
        );
    }
    echo json_encode($result);
});

//Metodo GET pago

$app->get('/pago' , function() use ($db,$app){
    $sql = 'SELECT * FROM pago ORDER BY id DESC;';
    $query = $db -> query($sql);

    $productos = array();
    while($producto = $query->fetch_assoc()){
        $productos[] = $producto;
    }
    $result =  array(
        'status' => 'success',
        'code' => 200,
        'data' => $productos
    );

    echo json_encode($result);

});


//actualizar PUT pago

$app->post('/update-pago/:id' , function($id) use($app, $db){

    $fecha = $app->request->post('fecha');
    $costo = $app->request->post('costo');
    $id_reserva = $app->request->post('id_reserva');
    
    // $json = $app->request->post('json');
    // $data = json_decode($json,true);
    $pagoOrigin = $db->query("SELECT * FROM pago WHERE id = $id")->fetch_array();

    $fecha = $fecha ?:  $pagoOrigin['fecha'];
    $costo = $costo ?:  $pagoOrigin['costo'];
    $id_reserva = $id_reserva ?:  $pagoOrigin['id_reserva'];

    $sql = "UPDATE pago SET fecha = '$fecha',  costo = '$costo', id_reserva = '$id_reserva' WHERE id = $id";

    
    // var_dump($sql);
    $query = mysqli_query($db, $sql);

    // $query = $db->query($sql);

    //  print_r($query);
    //  die();
    
    if($query){
        $result =  array(
            'status' => 'success',
            'code' => 200,
            'message' => 'actualizado'
        );
    }else{
        $result =  array(
            'status' => 'failed',
            'code' => 404,
            'message' => 'no actualizado'
        );
    }

    echo json_encode($result);
});

//Eliminar DELETE pago

$app->get('/delete-pago/:id' , function($id) use ($db, $app){
    $sql = 'DELETE FROM pago WHERE id = '.$id;
    $query = $db->query($sql);

    if($query){
        $result =  array(
            'status' => 'success',
            'code' => 200,
            'message' => 'Borrado'
        );
    }else{
        $result =  array(
            'status' => 'failed',
            'code' => 404,
            'message' => 'no se a borrado'
        );
    }

    echo json_encode($result);
});



//HABITACION
//insert habitacion
$app->post('/habitacion' , function() use($app, $db){

    $tipo = $app->request->post('tipo');
    $cantCamas = $app->request->post('cantCamas');
    $disponible = $app->request->post('disponible');
    $hotel_id = $app->request->post('hotel_id');
    

    $query = "INSERT INTO habitacion VALUES(NULL,".
    "'{$tipo}',".
    "'{$cantCamas}',".
    "'{$disponible}',".
    "'{$hotel_id}'".    
    ");";


    $insert = $db->query($query);

    $result =  array(
        'status' => 'error',
        'code' => 404,
        'message' => 'Fallo'
    );
    
    if($insert){
        $result =  array(
            'status' => 'success',
            'code' => 200,
            'message' => 'Creado'
        );
    }
    echo json_encode($result);
});


//Metodo GET habitacion

$app->get('/habitacion' , function() use ($db,$app){
    $sql = 'SELECT * FROM habitacion ORDER BY id DESC;';
    $query = $db -> query($sql);

    $productos = array();
    while($producto = $query->fetch_assoc()){
        $productos[] = $producto;
    }
    $result =  array(
        'status' => 'success',
        'code' => 200,
        'data' => $productos
    );

    echo json_encode($result);

});

//actualizar PUT habitacion

$app->post('/update-habitacion/:id' , function($id) use($app, $db){

    $tipo = $app->request->post('tipo');
    $cantCamas = $app->request->post('cantCamas');
    $disponible = $app->request->post('disponible');
    $hotel_id = $app->request->post('hotel_id');
    
    
    // $json = $app->request->post('json');
    // $data = json_decode($json,true);
    $habitacionOrigin = $db->query("SELECT * FROM habitacion WHERE id = $id")->fetch_array();

    $tipo = $tipo ?:  $habitacionOrigin['tipo'];
    $cantCamas = $cantCamas ?:  $habitacionOrigin['cantCamas'];
    $disponible = $disponible ?:  $habitacionOrigin['disponible'];
    $hotel_id = $hotel_id ?:  $habitacionOrigin['hotel_id'];
    

    $sql = "UPDATE habitacion SET tipo = '$tipo', cantCamas = '$cantCamas', disponible = '$disponible', hotel_id = '$hotel_id' WHERE id = $id";

    
    // var_dump($sql);
    $query = mysqli_query($db, $sql);

    // $query = $db->query($sql);

    //  print_r($query);
    //  die();
    
    if($query){
        $result =  array(
            'status' => 'success',
            'code' => 200,
            'message' => 'actualizado'
        );
    }else{
        $result =  array(
            'status' => 'failed',
            'code' => 404,
            'message' => 'no actualizado'
        );
    }

    echo json_encode($result);
});

//Eliminar DELETE pago

$app->get('/delete-habitacion/:id' , function($id) use ($db, $app){
    $sql = 'DELETE FROM habitacion WHERE id = '.$id;
    $query = $db->query($sql);

    if($query){
        $result =  array(
            'status' => 'success',
            'code' => 200,
            'message' => 'Borrado'
        );
    }else{
        $result =  array(
            'status' => 'failed',
            'code' => 404,
            'message' => 'no se a borrado'
        );
    }

    echo json_encode($result);
});

$app->run();
