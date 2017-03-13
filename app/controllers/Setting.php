 <?php
class SettingController extends AdminController {
    function index() {
        $template = \Template::instance();  
        $setting = $this->db->exec("SELECT * FROM  `tbl_settings`")[0];
    	if(!empty($setting['value1'])) { $this->f3->set('login', $setting['value1']);  }
    	if(!empty($setting['value2'])) { $this->f3->set('password', $setting['value2']); }
        echo $template->render('setting.html');
    }
    function update() {
		$this->db->exec("UPDATE  `tbl_settings` SET  `value1` =  '".$_POST['login']."', `value2` =  '".$_POST['password']."' WHERE  `tbl_settings`.`key` ='yws';");
    }
}
?>