 <?php
class StatController extends AdminController {
    function index() {
        $template = \Template::instance();   
        $logs = $this->db->exec("SELECT * FROM  `tbl_logs`;");    
        echo $template->render('stat.html');
    }
}
?>
