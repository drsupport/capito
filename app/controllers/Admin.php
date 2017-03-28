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

        $nav = [];
        $path = explode('/', 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
        $nav['stat']['href'] = '/admin/stat';
        $nav['stat']['class'] = '';
        $nav['stat']['icon'] = 'fa-bar-chart';
        $nav['stat']['title'] = 'Статистика';
        $nav['offer']['href'] = '/admin/offer';
        $nav['offer']['class'] = '';
        $nav['offer']['icon'] = 'fa-briefcase';
        $nav['offer']['title'] = 'Офферы';

        $nav['company']['href'] = '/admin/company';
        $nav['company']['class'] = '';
        $nav['company']['icon'] = 'fa-users';
        $nav['company']['title'] = 'Партнерки';

        $nav['segment']['href'] = '/admin/segment';
        $nav['segment']['class'] = '';
        $nav['segment']['icon'] = 'fa-tags';
        $nav['segment']['title'] = 'Сегменты';

        

        $nav['log']['href'] = '/admin/log';
        $nav['log']['class'] = '';
        $nav['log']['icon'] = 'fa-history';
        $nav['log']['title'] = 'Парсеры';
        $nav['setting']['href'] = '/admin/setting';
        $nav['setting']['class'] = '';
        $nav['setting']['icon'] = 'fa-cog';
        $nav['setting']['title'] = 'Настройки';
        $nav['tasks']['href'] = '#';
        $nav['tasks']['class'] = 'class=disabled';
        $nav['tasks']['icon'] = 'fa-tasks';
        $nav['tasks']['title'] = 'Планировщик';
        $nav[$path[4]]['class'] = 'class=active';
        $this->f3->set('nav_list', $nav);  
    }
    function index() {        
        $this->f3->reroute('/admin/stat');
    }


}
?>