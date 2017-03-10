 <?php
class StatController extends AdminController {
    function index() {
    	$stats = $this->db->exec("SELECT tbl_products.name AS product, COUNT(*) AS words, SUM(stat.impressions) AS impressions, stat.datetime FROM tbl_products_words LEFT JOIN tbl_products ON tbl_products.id = tbl_products_words.product LEFT JOIN( SELECT tbl_stats.impressions, tbl_stats.datetime, tbl_stats.word FROM tbl_stats ORDER BY tbl_stats.datetime DESC LIMIT 1) AS stat ON stat.word = tbl_products_words.word GROUP BY tbl_products.id");
   	    
   	    $template = \Template::instance();  
        $this->f3->set('stats', $stats);    
        echo $template->render('stat.html');
    }
}
?>
