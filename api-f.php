<?php 
	session_start();

    ////////////////////////////////////////////////////////////
    // Bootstrap
    ////////////////////////////////////////////////////////////
    $req = explode('/', $_SERVER['PATH_INFO']);
    // echo "req = " ; print_r($req);
    array_shift($req);
    // echo "req shift = "; print_r($req);

    ////////////////////////////////////////////////////////////
    // REQUEST
    ////////////////////////////////////////////////////////////
    $input = file_get_contents('php://input');
    $param = array();
    try {
      $param = json_decode($input, true);
    } catch (Exception $e) {
      print_r($e);
    }
    //echo "param = ";print_r($param);
    ////////////////////////////////////////////////////////////
    // CONTROLLER
    ////////////////////////////////////////////////////////////
    function responseJson($data) {
      header('Content-Type: application/json; charset=UTF-8');
      echo json_encode($data, JSON_UNESCAPED_UNICODE);
      exit;
    }

    ///////////////////////////////////////////////////////////
    //  inclued setting
    ///////////////////////////////////////////////////////////
    include_once 'config.php';
    include_once './jwt/JWT.php';
    include_once './permission/permission.php';
    include_once 'base.php';

    ///////////////////////////////////////////////////////////
    // start function
    ///////////////////////////////////////////////////////////
    switch ($req[1]) {
	    case 'ping':
	        // ping($param);
	        break;
	    case 'getTheerProduct':
	    	getTheerProduct($param);
	    	break;
	}

	//////////////////////////////////////////////////////////
    // START
    //////////////////////////////////////////////////////////

    function getTheerProduct($param) {
    	try{
            global $pdo;

            $sql = "SELECT p.*, MAX(pp.productpic_name) as product_pic, MAX(pp.productpic_path) as product_path FROM product p left join product_pic pp on p.id = pp.product_id group by p.id limit 3";
            
            $three_product = DB::QueryAll($sql);

            responseJson(array(
                'status' => true,
                'data' => $three_product
            ));
        }catch(PDOException $e){
            responseJson(array(
                'status' => false,
                'error' => $e -> getMessage()
            ));
        }
    }
?>