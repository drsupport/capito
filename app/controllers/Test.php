 <?php
use \Colors\RandomColor;
class TestController extends AdminController {
    function index() {
		echo RandomColor::one(); 
    }
}
