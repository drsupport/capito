 <?php
class OfferController extends AdminController {
    function index() {
    	$stats = $this->db->exec("SELECT tbl_products.name AS product, COUNT(*) AS words FROM tbl_products_words LEFT JOIN tbl_words ON tbl_words.id = tbl_products_words.word LEFT JOIN tbl_products ON tbl_products.id = tbl_products_words.product GROUP BY tbl_products_words.product");   	    
   	    $template = \Template::instance();  
        $this->f3->set('offers', $stats);    
        echo $template->render('offer.html');
    }
    function createView() {
   	    $template = \Template::instance();    
        echo $template->render('offer/create.html');
    }
    function create() {   
		$this->init_sqlij();
    	if(empty($_POST['words'])) if(!$product) $this->pushJSON(false, 'words undefined', '', 'words');    	
    	if(empty($_POST['name'])) if(!$product) $this->pushJSON(false, 'name undefined', '', 'name'); 
    	$product = $_POST['name'];
 		$product = $this->db->exec("INSERT IGNORE INTO `tbl_products`(`id` , `name`) VALUES (NULL, '".$product."');");
 		if(!$product) $this->pushJSON(false, 'product exists', '', 'name');
 		$product = $this->db->lastInsertId();

    	$words = explode(",", $_POST['words']);
    	$words = array_filter($words, function($element) { return !empty($element); });
		foreach ($words as $key=>$word) {  
			$word = mb_strtolower(trim($word));		
			$word = $this->db->exec("INSERT IGNORE INTO `tbl_words`(`id` , `name`) VALUES (NULL , '".$word."') ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id);");
			if ($word) $i++;
 			$word = $this->db->lastInsertId();
 			$this->db->exec("INSERT IGNORE INTO `tbl_products_words`( `id` , `product` , `word`) VALUES ( NULL , '".$product."', '".$word."' );"); 	 					
		}	

		$msg = 'Result product id: '.$product.' is new '. 'and of '.strval(count($words)).' words, '.strval($i).' word is new added, '.strval(count($words)-$i).' is updated'; 
    	$this->pushJSON(true, $msg);

    }
}
?>
