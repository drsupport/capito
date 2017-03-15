 <?php
class LogController extends AdminController {
    function index() {
    	$logs = $this->db->exec("SELECT * FROM  tbl_logs ORDER BY tbl_logs.datetime DESC;");
        $template = \Template::instance();  
        $this->f3->set('logs', $logs);    
        echo $template->render('log.html');
    }
}
?>
