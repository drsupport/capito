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
    	$word = $this->db->exec("SELECT tbl_words.id, tbl_words.name, logs.word FROM tbl_words LEFT JOIN( SELECT tbl_logs.word FROM tbl_logs WHERE tbl_logs.datetime >=CURDATE() AND tbl_logs.status = 1 GROUP BY DAY(tbl_logs.datetime)) AS logs ON logs.word = tbl_words.id WHERE logs.word IS NULL LIMIT 1")[0];
    	if(!$word) $this->pushJSON(false, "words empty");
    	$time = date('Y-m-d H:i:s', mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"))); 		
		$this->db->exec("INSERT INTO `tbl_logs`( `id` , `datetime` , `status`, `query`, `response`, `word`) VALUES ( NULL , '".$time."', '0', '', '".$word['name']."', '".$word['id']."');");
		$log = $this->db->lastInsertId();	
        $setting = $this->db->exec("SELECT * FROM  `tbl_settings`")[0];               
		$query = './vendor/drsupport/parser.yws/vendor/ariya/phantomjs/bin/phantomjs --web-security=no ./vendor/drsupport/parser.yws/yws.js 003fa7c1cd658bca6016eae7c179f012 ivanov.vladimir.v sp@rt@nec "'.$word['name'].'" "" "weekly" "" 2>&1';
		$this->db->exec("UPDATE  `tbl_logs` SET  `query` =  '".$query."' WHERE  `tbl_logs`.`id` =".$log.";"); 
		putenv('LANG=en_US.UTF-8'); 
        $response = shell_exec($query);
        $this->db->exec("UPDATE  `tbl_logs` SET  `response` =  '".$response."' WHERE  `tbl_logs`.`id` =".$log.";"); 
        if(empty($response) OR !$this->isJson($response)) unset($response); 
        $this->db->exec("UPDATE  `tbl_logs` SET  `status` =  '".strval(isset($response) ? '1' : '-1')."' WHERE  `tbl_logs`.`id` =".$log.";");
 		if(!isset($response)) $this->pushJSON(false, "json invalid");
        $response = json_decode($response);      
		foreach($response->response->history as $key => $history){ 
			$datetimes = explode("-", $history->period);
			$datetimes = array_filter($datetimes, function($el){ return !empty($el);});
			$datetimes = array_map('trim', $datetimes);
			if (count($datetimes)!=2) continue;
			$datetimes[0] = preg_replace('/[^\d.]/','', $datetimes[0]);
			$datetimes[1] = preg_replace('/[^\d.]/','', $datetimes[1]);
			$this->db->exec("INSERT INTO `tbl_analytic` (`id`, `datetime`, `datetime_in`, `datetime_out`, `word`, `db`, `period`, `regions`, `impressions`) VALUES (NULL, '".$time."', '".date("Y-m-d", strtotime(trim($datetimes[0])))."', '".date("Y-m-d", strtotime(trim($datetimes[1])))."', '".$word['id']."', NULL, 'weekly', NULL, '".$history->value."') ON DUPLICATE KEY UPDATE `impressions` = '".$history->value."';");
		}    
		//$this->get('http://capito.dr.cash/p/stat');
		$this->pushJSON(true, $word['name']);     


    }
}
?>
