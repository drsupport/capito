 <?php
class YWSController extends Controller {
	function parse_wordstat($keyword) {  
		$url = 'https://wordstat.yandex.ru/#!/?words='.urlencode($keyword);
		$data = $this->get($url);
		return $data; 
	} 
	function isJson($string) {
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}
	function get($url) {
		$ch = curl_init ();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt ($ch, CURLOPT_HEADER, 1);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9) Gecko/2008052906 Firefox/3.0');
		curl_setopt ($ch, CURLOPT_REFERER, "http://wordstat.yandex.ru/");
		$data = curl_exec ($ch);
		$http_code = curl_getinfo ($ch, CURLINFO_HTTP_CODE);
		curl_close ($ch);
		return $data;
	}
    function index() {
    	/*require_once(__DIR__.'../../../vendor/ivansky/php-yandex-wordstat/YADWord.php');
    	require_once(__DIR__.'../../../vendor/ivansky/php-yandex-wordstat/YADWordstat.php');*/

    	/*$this->db->exec("TRUNCATE TABLE  `tbl_logs`");
    	$this->db->exec("TRUNCATE TABLE  `tbl_stats`");*/    	
 		
 		//$this->f3->get('PARAMS.id');

    	$word = $this->db->exec("SELECT tbl_words.name, tbl_words.id, stat.datetime FROM tbl_words LEFT JOIN( SELECT tbl_stats.datetime, tbl_stats.word FROM tbl_stats WHERE tbl_stats.datetime = CURDATE() GROUP BY tbl_stats.word) AS stat ON stat.word = tbl_words.id ORDER BY stat.datetime ASC LIMIT 1")[0];
    	if(!$word) $this->pushJSON(false, "words empty");

    	$time = date('Y-m-d H:i:s', mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"))); 		
		$this->db->exec("INSERT INTO `tbl_logs`( `id` , `datetime` , `status`) VALUES ( NULL , '".$time."', '0' );");
		$log = $this->db->lastInsertId();	

		//account
        $setting = $this->db->exec("SELECT * FROM  `tbl_settings`")[0];

		$query = './vendor/ariya/phantomjs/bin/phantomjs yws.js '.$word['name'].' '.$log.' '.$setting['value1'].' '.$setting['value2'].' 2>&1';


        $this->db->exec("UPDATE  `capito`.`tbl_logs` SET  `query` =  '".$query."' WHERE  `tbl_logs`.`id` =".$log.";"); 
 		//foreach ($words as $key=>$word) {   	} 
 		putenv('LANG=en_US.UTF-8'); 
        $response = shell_exec($query); 

        if(empty($response) OR !$this->isJson($response)) $response = '';        
        $this->db->exec("UPDATE  `tbl_logs` SET  `status` =  '".strval(empty($response) ? '-1' : '1')."' WHERE  `tbl_logs`.`id` =".$log.";");   

        $response = json_decode($response);
        $this->db->exec("INSERT IGNORE INTO  `tbl_stats` (`id`, `datetime`, `word`, `device`, `geo`, `impressions`) VALUES (NULL, '".date("Y-m-d 00:00", strtotime($response->datetime))."', '".$word['id']."', NULL, NULL, '".$response->impressions."');");

		$this->get('http://capito.dr.cash/p/stat');
        

		//print_r($response);

    	/*

    	die();

    	
		$push = $this->db->exec("INSERT INTO `tbl_stats`(`id` , `datetime` , `word` , `device` , `geo` , `impressions`) VALUES ( NULL , '".$time."', '1', NULL , '', '1000' );");
		*/
    }
}
?>
