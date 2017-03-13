 <?php
class OfferController extends AdminController {
    function index() {
    	$stats = $this->db->exec("SELECT tbl_products.name AS product, COUNT(*) AS words FROM tbl_products_words LEFT JOIN tbl_words ON tbl_words.id = tbl_products_words.word LEFT JOIN tbl_products ON tbl_products.id = tbl_products_words.product GROUP BY tbl_products_words.product");
   	    
   	    $template = \Template::instance();  
        $this->f3->set('offers', $stats);    
        echo $template->render('offer.html');
    }
}



?>
