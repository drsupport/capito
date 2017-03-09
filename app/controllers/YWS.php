 <?php
class YWSController extends Controller {
	function parse_wordstat($keyword) {  
		$url = 'https://wordstat.yandex.ru/#!/?words='.urlencode($keyword);
		$data = $this->get($url);
		return $data; 
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
    	//print_r($this->parse_wordstat('пиджеум')); 

    	$time = date('Y-m-d H:i:s', mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"))); 		
		$this->db->exec("INSERT INTO `tbl_logs`( `id` , `datetime` , `status`) VALUES ( NULL , '".$time."', '1' );");
		$log_id = $this->db->lastInsertId();

		echo shell_exec('./vendor/ariya/phantomjs/bin/phantomjs yws.js пиджеум '.$log_id.' 2>&1');


		//print_r($response);

    	/*
    	$words = $this->db->exec("SELECT * FROM  `tbl_words`;");
    	if(!$words) $this->pushJSON(false, "words empty");

 		foreach ($words as $key=>$word) {              
 			print_r($word);
        } 
    	die();

    	
		$push = $this->db->exec("INSERT INTO `tbl_stats`(`id` , `datetime` , `word` , `device` , `geo` , `impressions`) VALUES ( NULL , '".$time."', '1', NULL , '', '1000' );");
		*/
    }
}
?>
