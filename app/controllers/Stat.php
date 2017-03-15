 <?php
class StatController extends AdminController {
    function index() {
    	$stats = $this->db->exec("SELECT tbl_products.id AS product_id, tbl_products.name AS product, tbl_stats_products.words AS allwords, tbl_stats_products.word AS words, tbl_stats_products.impressions, tbl_stats_products.dynamic, Date_format(tbl_stats_products.datetime, '%d.%m.%Y') AS `datetime` FROM tbl_stats_products LEFT JOIN tbl_products ON tbl_products.id = tbl_stats_products.product ORDER BY tbl_stats_products.datetime DESC");   	    
   	    $template = \Template::instance();  
        $this->f3->set('stats', $stats);    
        echo $template->render('stat.html');

        
    }
}

