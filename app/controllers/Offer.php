 <?php
class OfferController extends AdminController {
    function index() {
    	$stats = $this->db->exec("SELECT result.product AS product, Count(*) AS words, Sum(result.impressions) AS impressions, Max(Date_format(result.datetime, '%d.%m.%Y')) AS `datetime`, result.allwords FROM(SELECT tbl_products.id AS product_id, tbl_products.name AS product, tbl_products_words.word, stat.impressions, stat.datetime, stat.word AS allwords FROM tbl_products_words LEFT JOIN tbl_products ON tbl_products.id = tbl_products_words.product LEFT JOIN(SELECT tbl_stats.impressions, tbl_stats.datetime, tbl_stats.word FROM tbl_stats ORDER BY tbl_stats.datetime DESC) AS stat ON stat.word = tbl_products_words.word WHERE stat.datetime IS NOT NULL GROUP BY tbl_products_words.word) AS result GROUP BY result.product_id ");
   	    
   	    $template = \Template::instance();  
        $this->f3->set('offers', $stats);    
        echo $template->render('offer.html');
    }
}



?>
