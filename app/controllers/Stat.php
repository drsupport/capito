 <?php
class StatController extends AdminController {
    function index() {
    	$stats = $this->db->exec("SELECT tbl_products.id AS product_id, tbl_products.name AS product, tbl_stats_products.words AS allwords, tbl_stats_products.word AS words, tbl_stats_products.impressions, stat.max_impressions, stat.min_impressions, CASE WHEN tbl_stats_products.impressions = stat.max_impressions THEN 0 WHEN tbl_stats_products.impressions > stat.max_impressions THEN 1 WHEN tbl_stats_products.impressions < stat.max_impressions THEN -1 END AS dynamic, Date_format(tbl_stats_products.datetime, '%d.%m.%Y') AS `datetime` FROM tbl_stats_products LEFT JOIN tbl_products ON tbl_products.id = tbl_stats_products.product LEFT JOIN( SELECT tbl_products.id AS product, MAX(tbl_stats_products.impressions) AS max_impressions, MIN(tbl_stats_products.impressions) AS min_impressions, Date_format(tbl_stats_products.datetime, '%d.%m.%Y') AS `datetime` FROM tbl_stats_products LEFT JOIN tbl_products ON tbl_products.id = tbl_stats_products.product WHERE tbl_stats_products.datetime < DATE_SUB(CURDATE(), INTERVAL 0 DAY) OR tbl_stats_products.datetime = CURDATE() GROUP BY tbl_products.id) AS stat ON stat.product = tbl_stats_products.product ORDER BY tbl_stats_products.datetime DESC");   	    
   	    $template = \Template::instance();  
        $this->f3->set('stats', $stats);    
        echo $template->render('stat.html');
    }
}


