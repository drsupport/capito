 <?php
class LogController extends AdminController {
    function index() {
    	//$logs = $this->db->exec("SELECT tbl_words.name AS response, tbl_logs.datetime, tbl_logs.status FROM tbl_logs LEFT JOIN tbl_words ON tbl_words.id = tbl_logs.word WHERE tbl_words.name IS NOT NULL ORDER BY tbl_logs.datetime DESC;");
    	$logs = $this->db->exec("SELECT tbl_words.name AS response, tbl_logs.datetime, tbl_logs.status, analytic.datetime_out AS datetime_update FROM tbl_logs LEFT JOIN tbl_words ON tbl_words.id = tbl_logs.word LEFT JOIN( SELECT tbl_analytic.word, tbl_analytic.datetime_out FROM tbl_analytic ORDER BY tbl_analytic.datetime_out DESC) AS analytic ON analytic.word = tbl_words.id WHERE tbl_words.name IS NOT NULL AND tbl_logs.datetime >= CURDATE() GROUP BY tbl_words.id ORDER BY tbl_logs.datetime DESC;");
    	$balance = $this->db->exec("SELECT tbl_logs.response FROM tbl_logs WHERE tbl_logs.response LIKE '%success%' ORDER BY tbl_logs.datetime DESC LIMIT 1;")[0];
    	$word = $this->db->exec("SELECT tbl_words.id, tbl_words.name, logs.word FROM tbl_words LEFT JOIN( SELECT tbl_logs.word FROM tbl_logs WHERE tbl_logs.datetime >=CURDATE() AND tbl_logs.status = 1 GROUP BY tbl_logs.word, DAY(tbl_logs.datetime)) AS logs ON logs.word = tbl_words.id WHERE logs.word IS NULL LIMIT 1")[0];

    	$balance = json_decode($balance['response']);     	
        $template = \Template::instance(); 
        if($word) { 
        	$this->f3->set('sync', true); 
        } else {
        	$this->f3->set('sync', false);	
        } 
        $this->f3->set('logs', $logs);    
        $this->f3->set('balance', $balance->balance);    
        echo $template->render('log.html');
    }
}
?>



