 <?php
class CompanyController extends AdminController {
    function index() {
    	$companies = $this->db->exec("SELECT tbl_companies.id, tbl_companies.name, GROUP_CONCAT(tbl_products.name SEPARATOR ', ') AS impressions FROM tbl_companies LEFT JOIN tbl_products_companies ON tbl_products_companies.company = tbl_companies.id LEFT JOIN tbl_products ON tbl_products.id = tbl_products_companies.product GROUP BY tbl_companies.id");   	    
   	    $template = \Template::instance();  
        $this->f3->set('companies', $companies);    
        echo $template->render('company.html');
    }
    function createView() {
   	    $template = \Template::instance();    
        echo $template->render('company/create.html');
    }
    function create() {  
    	$this->init_sqlij();
    	if(empty($_POST['name'])) if(!$product) $this->pushJSON(false, 'name undefined', '', 'name'); 
        if(empty($_POST['marker'])) if(!$product) $this->pushJSON(false, 'marker undefined', '', 'marker');
        $company = $_POST['name'];

    	//edit
 		if(!empty($_POST['id'])) { 
            $this->db->exec("UPDATE  `tbl_companies` SET  `name` =  '".$company."', `color` =  '".$_POST['marker']."' WHERE  `tbl_companies`.`id` =".$_POST['id'].";");
			$company = $this->db->exec("SELECT * FROM  `tbl_companies` WHERE  `id` =".$_POST['id'])[0]['name'];
			if(empty($company)) $this->pushJSON(false, 'company_id undefined', '', 'id'); 
			$this->db->exec("DELETE FROM tbl_products_companies WHERE tbl_products_companies.company = ". $_POST['id']);
		}

 		$company = $this->db->exec("INSERT IGNORE INTO `tbl_companies`(`id` , `name`, `segment`, `color`) VALUES (NULL, '".$company."', NULL, '".$_POST['marker']."') ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id);");
 		//if(!$product) $this->pushJSON(false, 'product exists', '', 'name');
 		$company = $this->db->lastInsertId();

        /*
    	$words = explode(",", $_POST['words']);
    	$words = array_filter($words, function($element) { return !empty($element); });
		foreach ($words as $key=>$word) {  
			$word = mb_strtolower(trim($word));		
			$word = $this->db->exec("INSERT IGNORE INTO `tbl_words`(`id` , `name`) VALUES (NULL , '".$word."') ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id);");
			if ($word) $i++;
 			$word = $this->db->lastInsertId();
 			$this->db->exec("INSERT IGNORE INTO `tbl_products_words`( `id` , `product` , `word`) VALUES ( NULL , '".$product."', '".$word."' );"); 	 					
		}*/

    	//edit
        /*
 		if(!empty($_POST['id'])) { 
			$this->db->exec("DELETE FROM tbl_words WHERE id IN( SELECT * FROM ( SELECT tbl_words.id FROM tbl_words LEFT JOIN tbl_products_words ON tbl_products_words.word = tbl_words.id WHERE tbl_products_words.product IS NULL GROUP BY tbl_words.id) AS p )");	
		}
        */
        /*
		$msg = 'Result product id: '.$company.' is new '. 'and of '.strval(count($words)).' words, '.strval($i).' word is new added, '.strval(count($words)-$i).' is updated'; 
    	$this->pushJSON(true, $msg);*/
        $this->pushJSON(true, 'company create success');

    }
    function editView() {  
    	$_POST['id'] = $this->f3->get('PARAMS.id');
    	$_POST['id'] = preg_replace("/[^0-9]/", '', $_POST['id']);
    	$this->init_sqlij();    	
    	if(empty($_POST['id'])) $this->pushJSON(false, 'id undefined'); 
        $company = $this->db->exec("SELECT * FROM  `tbl_companies` WHERE  `id` = ".$_POST['id'])[0];
 		/*$words = $this->db->exec("SELECT tbl_products_words.id, tbl_products.id AS product_id, tbl_products.name AS product, tbl_words.name AS word, tbl_products.color, tbl_products.segment FROM tbl_products_words left join tbl_words ON tbl_words.id = tbl_products_words.word left join tbl_products ON tbl_products.id = tbl_products_words.product WHERE tbl_products_words.product = ".$product);
    	if(!$words) $this->pushJSON(true, 'words empty');	
        $segments = $this->db->exec("SELECT * FROM  `tbl_segments`");
        $seg = $words[0]['segment'];
    	$id = $words[0]['product_id'];*/
    	//$company = $words[0]['company'];
        //$color = $words[0]['color'];
    	//$words = array_column($words, 'word');
		//$words = implode($words, ',');	
    	$template = \Template::instance();  
        //if($seg) $this->f3->set('seg', $seg);
        //$this->f3->set('segments', $segments);  
        $this->f3->set('color', $company['color']); 
    	$this->f3->set('name', $company['name']); 
    	//$this->f3->set('words', $words); 
    	$this->f3->set('id', $company['id']); 
        echo $template->render('company/edit.html');    	

    }
}
?>
