 <?php
class AdminController extends Controller {
    function beforeroute() {
        $u_id = $this->f3->get('COOKIE.u_id');
        $u_hash = $this->f3->get('COOKIE.u_hash');
        if (isset($u_id) AND isset($u_hash)) { 
            $auth = $this->db->exec("SELECT * FROM  `tbl_users` WHERE  `user_id` =".$u_id." AND  `user_hash` LIKE  '".$u_hash."' LIMIT 1", NULL, 60);
            if(!$auth) {                
                $this->auth();
                //$this->pushJSON(false, "signin invalid");
                $template = \Template::instance();       
                echo $template->render('login.html');
                die();
            }
        } else {
            //$this->pushJSON(false, "cookies invalid");
            $template = \Template::instance();       
            echo $template->render('login.html');
            die();
        }
    }
    function index() {
        $this->f3->reroute('/admin/stat');
    }
    function emulate() {
        $this->auth('1', '2');
    }

}
?>