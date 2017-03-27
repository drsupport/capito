 <?php
class LogController extends AdminController {
    function index() {
    	$logs = $this->db->exec("SELECT tbl_words.name AS response, tbl_logs.datetime, tbl_logs.status FROM tbl_logs LEFT JOIN tbl_words ON tbl_words.id = tbl_logs.word WHERE tbl_words.name IS NOT NULL ORDER BY tbl_logs.datetime DESC;");
        $template = \Template::instance();  
        $this->f3->set('logs', $logs);    
        echo $template->render('log.html');
    }
}
?>
