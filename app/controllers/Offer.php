 <?php
class OfferController extends AdminController {
    function index() {
    	$stats = $this->db->exec("SELECT tbl_products.id, tbl_products.name AS product, COUNT(*) AS words, tbl_segments.name AS segment FROM tbl_products_words LEFT JOIN tbl_words ON tbl_words.id = tbl_products_words.word LEFT JOIN tbl_products ON tbl_products.id = tbl_products_words.product LEFT JOIN tbl_segments ON tbl_segments.id = tbl_products.segment GROUP BY tbl_products_words.product");   	    
   	    $template = \Template::instance();  
        $this->f3->set('offers', $stats);    
        echo $template->render('offer.html');
    }
    function createView() {
        $segments = $this->db->exec("SELECT * FROM  `tbl_segments`");
        $companies = $this->db->exec("SELECT * FROM  `tbl_companies`");
   	    $template = \Template::instance();    
        $this->f3->set('segments', $segments);
        $this->f3->set('companies', $companies);
        echo $template->render('offer/create.html');
    }
    function create() {  
    	$this->init_sqlij();
        $_POST['id'] = preg_replace("/[^0-9]/", '', $_POST['id']);
        $_POST['owner'] = preg_replace("/[^0-9]/", '', $_POST['owner']);
        $_POST['reseller'] = preg_replace("/[^0-9]/", '', $_POST['reseller']);
    	if(empty($_POST['words'])) if(!$product) $this->pushJSON(false, 'words undefined', '', 'words');    	
    	if(empty($_POST['name'])) if(!$product) $this->pushJSON(false, 'name undefined', '', 'name'); 
        if(empty($_POST['marker'])) if(!$product) $this->pushJSON(false, 'marker undefined', '', 'marker');
    	$product = $_POST['name'];

    	//edit
 		if(!empty($_POST['id'])) { 
 			$this->db->exec("UPDATE  `tbl_products` SET  `name` =  '".$product."', `segment` =  '".$_POST['segment']."', `color` =  '".$_POST['marker']."' WHERE  `tbl_products`.`id` =".$_POST['id'].";");
			$product = $this->db->exec("SELECT * FROM  `tbl_products` WHERE  `id` =".$_POST['id'])[0]['name'];
			if(empty($product)) $this->pushJSON(false, 'product_id undefined', '', 'id'); 
			$this->db->exec("DELETE FROM tbl_products_words WHERE tbl_products_words.product = ". $_POST['id']);
            if(empty($_POST['owner'])) $this->db->exec("DELETE FROM tbl_products_companies WHERE `owner` = 1 AND `product` = ". $_POST['id']);    
            if(empty($_POST['reseller'])) $this->db->exec("DELETE FROM tbl_products_companies WHERE `owner` = 0 AND `product` = ". $_POST['id']); 
		}

 		$product = $this->db->exec("INSERT IGNORE INTO `tbl_products`(`id` , `name`, `segment`, `color`) VALUES (NULL, '".$product."', '".$_POST['segment']."', '".$_POST['marker']."') ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id);");
 		//if(!$product) $this->pushJSON(false, 'product exists', '', 'name');
 		$product = $this->db->lastInsertId();

        if(!empty($_POST['owner'])) $this->db->exec("INSERT IGNORE INTO `tbl_products_companies` (`id`, `product`, `company`, `owner`) VALUES (NULL, '".$product."', '".$_POST['owner']."', '1');");
        if(!empty($_POST['reseller'])) $this->db->exec("INSERT IGNORE INTO `tbl_products_companies` (`id`, `product`, `company`, `owner`) VALUES (NULL, '".$product."', '".$_POST['reseller']."', '0');");

    	$words = explode(",", $_POST['words']);
    	$words = array_filter($words, function($element) { return !empty($element); });
		foreach ($words as $key=>$word) {  
			$word = mb_strtolower(trim($word));		
			$word = $this->db->exec("INSERT IGNORE INTO `tbl_words`(`id` , `name`) VALUES (NULL , '".$word."') ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id);");
			if ($word) $i++;
 			$word = $this->db->lastInsertId();
 			$this->db->exec("INSERT IGNORE INTO `tbl_products_words`( `id` , `product` , `word`) VALUES ( NULL , '".$product."', '".$word."' );"); 	 					
		}
    	//edit
 		if(!empty($_POST['id'])) { 
			$this->db->exec("DELETE FROM tbl_words WHERE id IN( SELECT * FROM ( SELECT tbl_words.id FROM tbl_words LEFT JOIN tbl_products_words ON tbl_products_words.word = tbl_words.id WHERE tbl_products_words.product IS NULL GROUP BY tbl_words.id) AS p )");	
		}
		$msg = 'Result product id: '.$product.' is new '. 'and of '.strval(count($words)).' words, '.strval($i).' word is new added, '.strval(count($words)-$i).' is updated'; 
    	$this->pushJSON(true, $msg);

    }
    function editView() {  
    	$_POST['id'] = $this->f3->get('PARAMS.id');
    	$_POST['id'] = preg_replace("/[^0-9]/", '', $_POST['id']);
    	$this->init_sqlij();    	
    	if(empty($_POST['id'])) $this->pushJSON(false, 'id undefined'); 
    	$product = $_POST['id'];
 		$words = $this->db->exec("SELECT tbl_products_words.id, tbl_products.id AS product_id, tbl_products.name AS product, tbl_words.name AS word, tbl_products.color, tbl_products.segment FROM tbl_products_words left join tbl_words ON tbl_words.id = tbl_products_words.word left join tbl_products ON tbl_products.id = tbl_products_words.product WHERE tbl_products_words.product = ".$product);
    	if(!$words) $this->pushJSON(true, 'words empty');	
        $companies = $this->db->exec("SELECT * FROM  `tbl_companies`");
        $segments = $this->db->exec("SELECT * FROM  `tbl_segments`");

        $owner = $this->db->exec("SELECT * FROM  `tbl_products_companies` WHERE  `product` = ".$product." AND  `owner` = 1")[0];
        $reseller = $this->db->exec("SELECT * FROM  `tbl_products_companies` WHERE  `product` = ".$product." AND  `owner` = 0")[0];

        $seg = $words[0]['segment'];
    	$id = $words[0]['product_id'];
    	$product = $words[0]['product'];
        $color = $words[0]['color'];
    	$words = array_column($words, 'word');
		$words = implode($words, ',');	
    	$template = \Template::instance(); 
  
        if($reseller['company']) $this->f3->set('reseller', $reseller['company']);
        if($owner['company']) $this->f3->set('owner', $owner['company']);
        if($seg) $this->f3->set('seg', $seg);

        $this->f3->set('companies', $companies);
        $this->f3->set('segments', $segments);  
        $this->f3->set('color', $color); 
    	$this->f3->set('name', $product); 
    	$this->f3->set('words', $words); 
    	$this->f3->set('id', $id); 
        echo $template->render('offer/edit.html');    	

    }
}
?>
