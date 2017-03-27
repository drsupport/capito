 <?php
class LogController extends AdminController {
    function index() {
    	//$logs = $this->db->exec("SELECT tbl_words.name AS response, tbl_logs.datetime, tbl_logs.status FROM tbl_logs LEFT JOIN tbl_words ON tbl_words.id = tbl_logs.word WHERE tbl_words.name IS NOT NULL ORDER BY tbl_logs.datetime DESC;");
    	$logs = $this->db->exec("SELECT tbl_words.name AS response, tbl_logs.datetime, tbl_logs.status, analytic.datetime_out AS datetime_update FROM tbl_logs LEFT JOIN tbl_words ON tbl_words.id = tbl_logs.word LEFT JOIN( SELECT tbl_analytic.word, tbl_analytic.datetime_out FROM tbl_analytic ORDER BY tbl_analytic.datetime_out DESC) AS analytic ON analytic.word = tbl_words.id WHERE tbl_words.name IS NOT NULL GROUP BY tbl_words.id ORDER BY tbl_logs.datetime DESC;");
    	$balance = $this->db->exec("SELECT tbl_logs.response FROM tbl_logs ORDER BY tbl_logs.datetime DESC LIMIT 1;")[0];
    	$balance = json_decode($balance['response']);     	
        $template = \Template::instance();  
        $this->f3->set('logs', $logs);    
        $this->f3->set('balance', $balance->balance);    
        echo $template->render('log.html');
    }
}
?>

