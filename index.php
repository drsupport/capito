<?php
    header('Access-Control-Allow-Origin: *'); 
    header("Access-Control-Allow-Headers : Content-Type");
    header("Access-Control-Allow-Methods : POST, OPTIONS");  
    header('Content-type: application/json; charset=utf-8');   

    //header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST,GET,OPTIONS');
    header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
    
    //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));

    date_default_timezone_set("Europe/Moscow");
    set_time_limit(0); 
    ini_set('always_populate_raw_post_data', '-1');
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);


    //Require Library
    require_once(__DIR__.'/vendor/autoload.php');
    require_once(__DIR__.'/app/controllers/Index.php');
    require_once(__DIR__.'/app/controllers/Admin.php');
    require_once(__DIR__.'/app/controllers/Site.php');
    require_once(__DIR__.'/app/controllers/User.php');
    require_once(__DIR__.'/app/controllers/Stat.php');
    require_once(__DIR__.'/app/controllers/YWS.php');
    require_once(__DIR__.'/app/controllers/Log.php');
    require_once(__DIR__.'/app/controllers/Offer.php');
    require_once(__DIR__.'/app/controllers/Cron.php');
    require_once(__DIR__.'/app/controllers/Setting.php');
    require_once(__DIR__.'/app/controllers/Task.php');
    require_once(__DIR__.'/app/controllers/Test.php');
    require_once(__DIR__.'/app/controllers/Company.php');

    $f3 = \Base::instance();    
    $f3->config('app/config/system.cfg');         
    $error = false;
    try {
        $db = new PDO("mysql:host=".$f3->get('host').";dbname=".$f3->get('dbname'), $f3->get('dbuser'), $f3->get('dbpass'));
    } 
    catch (PDOException $error) {      
        $error = true; 
    }
    if(!$error) { 
        $f3->config('app/config/routes.cfg');
        $db = new \DB\SQL("mysql:host=".$f3->get('host').";port=".$f3->get('port').";dbname=".$f3->get('dbname'), $f3->get('dbuser'), $f3->get('dbpass'), array(\PDO::ATTR_ERRMODE=>\PDO::ERRMODE_EXCEPTION)); 
    } else {
        //$f3->config('app/config/r.routes.cfg'); $local = new \DB\Jig('jig/');
        //pushJSON("false", "db error");   
    }

    $f3->set('DB', $db); 
    $f3->set('DEBUG', 3);    
    $f3->set('CACHE',TRUE);   
    $f3->set('UI',__DIR__.'/app/views/'); 
    /*
    $f3->set('ONERROR',function($f3, $smtp){
        $template = \Template::instance();
        echo $template->render('404.html');   
    });
    */
    $f3->run();
?>