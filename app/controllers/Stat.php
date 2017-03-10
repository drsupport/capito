 <?php
class StatController extends AdminController {
    function index() {
    	$stats = $this->db->exec("SELECT tbl_products.name AS product , tbl_words.name AS word, tbl_stats.datetime, tbl_stats.impressions FROM tbl_stats LEFT JOIN tbl_products_words ON tbl_products_words.word = tbl_stats.word LEFT JOIN tbl_words ON tbl_words.id = tbl_products_words.word LEFT JOIN tbl_products ON tbl_products.id = tbl_products_words.product");
   	    $template = \Template::instance();  
        $this->f3->set('stats', $stats);    
        echo $template->render('stat.html');
    }
}
?>
