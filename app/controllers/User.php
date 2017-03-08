 <?php
class UserController extends Controller {
	function index() {
	}    
	function signup() {
	} 
	function signin() {
		if(empty($_POST['login'])) $this->pushJSON(false, "login required");
		if (!$this->is_email($_POST['login'])) $this->pushJSON(false, "login invalid");
		$auth = $this->db->exec("SELECT * FROM `tbl_users` WHERE `user_login` LIKE '".$_POST['login']."'")[0];
		if(!$auth) $this->pushJSON(false, "login invalid");
		if(empty($_POST['password'])) $this->pushJSON(false, "password required");
		if($auth['user_password'] !== md5(md5($_POST['password']))) $this->pushJSON(false, "password invalid");
		$hash = md5($this->generateCode(10));
    	$this->db->exec("UPDATE  `insidersdigest`.`tbl_users` SET  `user_hash` =  '".$hash."' WHERE  `tbl_users`.`user_id` =".$auth['user_id'].";");
    	$this->auth($auth['user_id'], $hash);
    	$this->pushJSON(true);
	} 
	function logout() {
		$this->auth();
		$this->f3->reroute('/admin');
	}      
	function login() {	
		// beforeroute AdminController
	} 
}
?>