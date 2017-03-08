 <?php
class SiteController extends Controller {
    function index() {
        $template = \Template::instance();       
        echo $template->render('index.html'); 
    }
}
?>