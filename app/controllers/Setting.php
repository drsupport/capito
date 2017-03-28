 <?php
class SettingController extends AdminController {
    function index() {
        $template = \Template::instance();  
        $settings = $this->db->exec("SELECT * FROM  `tbl_settings`");
    
        foreach($settings as $key => $setting){  
          $settings[$setting['key']] = $setting; 
          unset($settings[$key]);
        }
        
    	if(!empty($settings['yandex']['value1'])) { $this->f3->set('login', $settings['yandex']['value1']);  }
    	if(!empty($settings['yandex']['value2'])) { $this->f3->set('password', $settings['yandex']['value2']); }
        if(!empty($settings['anticaptcha']['value1'])) { $this->f3->set('token', $settings['anticaptcha']['value1']); }
        echo $template->render('setting.html');
    }
    function update() {
        $this->init_sqlij();
		$this->db->exec("UPDATE  `tbl_settings` SET  `value1` =  '".$_POST['login']."', `value2` =  '".$_POST['password']."' WHERE  `tbl_settings`.`key` = 'yandex';");
        $this->db->exec("UPDATE  `tbl_settings` SET  `value1` =  '".$_POST['token']."' WHERE  `tbl_settings`.`key` = 'anticaptcha';");
        
    }
}
?>