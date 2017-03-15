 <?php
class CronController extends Controller {
    function index() {
    	//$stats = $this->db->exec("SELECT result.product_id AS product, result.alldata, Count(*) AS words, Sum(result.impressions) AS impressions, Max(result.datetime) AS `datetime` FROM(SELECT tbl_products.id AS product_id, tbl_products.name AS product, tbl_products_words.word, stat.impressions, stat.datetime, stat.word AS alldata FROM tbl_products_words LEFT JOIN tbl_products ON tbl_products.id = tbl_products_words.product LEFT JOIN (SELECT tbl_stats.impressions, tbl_stats.datetime, tbl_stats.word FROM tbl_stats WHERE tbl_stats.datetime < DATE_SUB(CURDATE(), INTERVAL 0 DAY) ORDER BY tbl_stats.datetime DESC) AS stat ON stat.word = tbl_products_words.word WHERE stat.datetime IS NOT NULL GROUP BY tbl_products_words.word) AS result GROUP BY result.product_id");
    	//$stats = $this->db->exec("SELECT result.product_id AS product, result.alldata, Count(*) AS words, Sum(result.impressions) AS impressions, Max(result.datetime) AS `datetime` FROM(SELECT tbl_products.id AS product_id, tbl_products.name AS product, tbl_products_words.word, stat.impressions, stat.datetime, stat.word AS alldata FROM tbl_products_words LEFT JOIN tbl_products ON tbl_products.id = tbl_products_words.product LEFT JOIN (SELECT tbl_stats.impressions, tbl_stats.datetime, tbl_stats.word FROM tbl_stats WHERE tbl_stats.datetime = CURDATE() ORDER BY tbl_stats.datetime DESC) AS stat ON stat.word = tbl_products_words.word WHERE stat.datetime IS NOT NULL GROUP BY tbl_products_words.word) AS result GROUP BY result.product_id");

 		//$stats = $this->db->exec("SELECT COUNT(*) AS allwords, result_in.product, result_in.alldata, result_in.result_words AS words, result_in.impressions, result_in.`datetime` FROM tbl_products_words LEFT JOIN( SELECT result.product_id AS product, result.alldata, Count(*) AS result_words, Sum(result.impressions) AS impressions, Max(result.datetime) AS `datetime` FROM(SELECT tbl_products.id AS product_id, tbl_products.name AS product, tbl_products_words.word, stat.impressions, stat.datetime, stat.word AS alldata FROM tbl_products_words LEFT JOIN tbl_products ON tbl_products.id = tbl_products_words.product LEFT JOIN (SELECT tbl_stats.impressions, tbl_stats.datetime, tbl_stats.word FROM tbl_stats WHERE tbl_stats.datetime < DATE_SUB(CURDATE(), INTERVAL 0 DAY) ORDER BY tbl_stats.datetime DESC) AS stat ON stat.word = tbl_products_words.word WHERE stat.datetime IS NOT NULL GROUP BY tbl_products_words.word) AS result GROUP BY result.product_id) AS result_in ON result_in.product = tbl_products_words.product WHERE result_in.product IS NOT NULL GROUP BY tbl_products_words.product");
    	$stats = $this->db->exec("SELECT COUNT(*) AS allwords, result_in.product, result_in.alldata, result_in.result_words AS words, result_in.`datetime`, IF(COUNT(*) = result_in.result_words, result_in.impressions, 0) AS impressions FROM tbl_products_words LEFT JOIN( SELECT result.product_id AS product, result.alldata, Count(*) AS result_words, Sum(result.impressions) AS impressions, Max(result.datetime) AS `datetime` FROM( SELECT tbl_products.id AS product_id, tbl_products.name AS product, tbl_products_words.word, stat.impressions, stat.datetime, stat.word AS alldata FROM tbl_products_words LEFT JOIN tbl_products ON tbl_products.id = tbl_products_words.product LEFT JOIN( SELECT tbl_stats.impressions, tbl_stats.datetime, tbl_stats.word FROM tbl_stats WHERE tbl_stats.datetime = CURDATE() ORDER BY tbl_stats.datetime DESC) AS stat ON stat.word = tbl_products_words.word WHERE stat.datetime IS NOT NULL GROUP BY tbl_products_words.word ) AS result GROUP BY result.product_id ) AS result_in ON result_in.product = tbl_products_words.product WHERE result_in.product IS NOT NULL GROUP BY tbl_products_words.product");
    	foreach ($stats as $key=>$stat) {   
    		$result = $this->db->exec("INSERT IGNORE INTO `tbl_stats_products`( `id` , `datetime` , `word` , `impressions` , `product`, `words`) VALUES ( NULL , '".$stat['datetime']."', '".$stat['words']."', '".$stat['impressions']."', '".$stat['product']."', '".$stat['allwords']."' ) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id);");
    		if(!$result) { $i++; 
    			$update = $this->db->exec("UPDATE  `capito`.`tbl_stats_products` SET  `word` =  '".$stat['words']."', `impressions` =  '".$stat['impressions']."', `words` =  '".$stat['allwords']."' WHERE  `tbl_stats_products`.`id` =".$this->db->lastInsertId().";");
    			//if($update) {}
    		}
    	}   
        $dynamics = $this->db->exec(" SELECT tbl_stats_products.id, tbl_products.id AS product_id, tbl_products.name AS product, tbl_stats_products.impressions AS today, last.impressions AS yesterday, CASE WHEN tbl_stats_products.impressions = last.impressions THEN 0 WHEN tbl_stats_products.impressions > last.impressions THEN 1 WHEN tbl_stats_products.impressions < last.impressions THEN -1 END AS dynamic, Date_format(tbl_stats_products.datetime, '%d.%m.%Y') AS `datetime` FROM tbl_stats_products LEFT JOIN tbl_products ON tbl_products.id = tbl_stats_products.product LEFT JOIN( SELECT tbl_products.id AS product, tbl_stats_products.impressions, DATE_FORMAT( tbl_stats_products.datetime, '%d.%m.%Y') AS `datetime` FROM tbl_stats_products LEFT JOIN tbl_products ON tbl_products.id = tbl_stats_products.product GROUP BY tbl_products.id ORDER BY tbl_stats_products.datetime DESC ) AS last ON last.product = tbl_stats_products.product WHERE tbl_stats_products.datetime = CURDATE() AND tbl_stats_products.impressions <> 0 ORDER BY tbl_stats_products.datetime DESC");
        if($dynamics) {
            foreach ($dynamics as $key=>$dynamic) {  
                $this->db->exec("UPDATE  `tbl_stats_products` SET  `dynamic` =  '".$dynamic['dynamic']."' WHERE  `tbl_stats_products`.`id` =".$dynamic['id'].";");
            }  
        }         
    	if(!count($stats)) $this->pushJSON(false, 'No new data to aggregate.'); 	
    	$msg = strval($i>0 ? 'Result new aggregated: '.strval(count($stats)-$i).' and updated '.strval($i) : 'Result new aggregated: '.strval(count($stats)-$i)); 
    	$this->pushJSON(true, $msg);
    		
    }
}


?>




