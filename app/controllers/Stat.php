 <?php
 use \Colors\RandomColor;
class StatController extends AdminController {
    function index() {

    	$labels = [];
    	$datasets = [];
    	$datacolor = json_decode('["#FA2A00","#ffb400","#1ABC9C","#22A7F0","#FF0099","#6600CC","#FF9900","#996600","#006633","#CC6600","#666666","#33FF00", "#800080", "#40E0D0", "#DEB887"]');
    	$colors = [];
        $filters = '';

        if(!empty($_POST['offers'])) {
            $filters = "
                AND tbl_products_words.product IN(".implode(",", $_POST['offers']).")
            ";
        }       

        if(!empty($_GET['week'])) {
            $nav = $this->db->exec("SELECT tbl_analytic.datetime_out FROM tbl_analytic WHERE tbl_analytic.datetime_out <= '".$_GET['week']."' GROUP BY tbl_analytic.datetime_out ORDER BY tbl_analytic.datetime_out DESC LIMIT 0,3");
            $back = $this->db->exec("SELECT tbl_analytic.datetime_out FROM tbl_analytic WHERE tbl_analytic.datetime_out > '".$_GET['week']."' GROUP BY tbl_analytic.datetime_out ORDER BY tbl_analytic.datetime_out DESC LIMIT 0,1")[0]['datetime_out'];
        } else {
            $nav = $this->db->exec("SELECT SQL_CALC_FOUND_ROWS tbl_analytic.datetime_out FROM tbl_analytic GROUP BY tbl_analytic.datetime_out ORDER BY tbl_analytic.datetime_out DESC LIMIT 0,3");

        }

        $curent = $nav[0]['datetime_out'];
        $next = $nav[1]['datetime_out'];
        $pages = $this->db->exec("SELECT SQL_CALC_FOUND_ROWS tbl_analytic.datetime_out FROM tbl_analytic GROUP BY tbl_analytic.datetime_out ORDER BY tbl_analytic.datetime_out DESC");
        $pages = $this->db->exec("SELECT FOUND_ROWS();")[0]['FOUND_ROWS()'];

        /*echo 'back: '.$back.' curent: '.$curent. ' next: '.$next.' total: '.$pages;
        die();*/

        //$last_date = $this->db->exec("SELECT tbl_analytic.datetime_out FROM tbl_analytic ORDER BY tbl_analytic.datetime_out DESC LIMIT 1")[0]['datetime_out'];
    	$stats = $this->db->exec("SELECT tbl_analytic.datetime_in, tbl_analytic.datetime_out, tbl_products_words.product AS product_id, tbl_products.name AS product, tbl_products.color, COUNT(*) AS words, SUM(tbl_analytic.impressions) AS impressions FROM tbl_analytic LEFT JOIN tbl_products_words ON tbl_products_words.word = tbl_analytic.word LEFT JOIN tbl_products ON tbl_products.id = tbl_products_words.product WHERE tbl_analytic.datetime_out = '".$curent."' AND tbl_products_words.product > 0  GROUP BY tbl_analytic.datetime_in, tbl_analytic.datetime_out, tbl_products_words.product ORDER BY SUM(tbl_analytic.impressions) DESC");   

 		$dates = $this->db->exec("SELECT tbl_analytic.datetime_in, tbl_analytic.datetime_out FROM tbl_analytic GROUP BY tbl_analytic.datetime_in, tbl_analytic.datetime_out ORDER BY tbl_analytic.datetime_out ASC");
 		$offers = $this->db->exec("SELECT tbl_products_words.product AS id, tbl_products.name FROM tbl_analytic LEFT JOIN tbl_products_words ON tbl_products_words.word = tbl_analytic.word LEFT JOIN tbl_products ON tbl_products.id = tbl_products_words.product WHERE tbl_products_words.product > 0 ".$filters." GROUP BY tbl_products_words.product");
        $segments = $this->db->exec("SELECT * FROM  `tbl_segments`");
        $companies = $this->db->exec("SELECT * FROM  `tbl_companies`");

 		foreach($dates as $key => $date){  		
 			array_push($labels, date("d.m", strtotime(trim($date['datetime_in']))).'-'.date("d.m", strtotime(trim($date['datetime_out']))).'('.date("Y", strtotime(trim($date['datetime_out']))).')'); 			
 		}
 		foreach($offers as $key => $offer){  
 			$dataitem = [];
 			$data = $this->db->exec("SELECT stats.product, stats.product_id, GROUP_CONCAT(stats.impressions SEPARATOR ', ') AS impressions, COUNT(*) AS impressions_count, stats.color FROM( SELECT tbl_analytic.datetime_in, tbl_analytic.datetime_out, tbl_products_words.product AS product_id, tbl_products.name AS product, COUNT(*) AS words, SUM(tbl_analytic.impressions) AS impressions, tbl_products.color FROM tbl_analytic LEFT JOIN tbl_products_words ON tbl_products_words.word = tbl_analytic.word LEFT JOIN tbl_products ON tbl_products.id = tbl_products_words.product GROUP BY tbl_analytic.datetime_in, tbl_analytic.datetime_out, tbl_products_words.product ORDER BY tbl_analytic.datetime_out ASC) AS stats WHERE stats.product_id = ".$offer['id']." GROUP BY stats.product_id")[0];
 			/*$color = RandomColor::one(array(
			   'luminosity' => 'bright',
			   'format' => 'rgbCss'
			));*/
            /*
			$color = $datacolor[$key]; 
			$colors[$data['product_id']] = $color;
            */

 			$dataitem['label'] = $data['product'];
 			$dataitem['color'] = $data['color'];
 			$dataitem['fillColor'] = "rgba(34, 167, 240, 0)";
 			$dataitem['strokeColor'] = $data['color'];
 			$dataitem['pointColor'] = $data['color'];
 			$dataitem['pointStrokeColor'] = "#fff";
 			$dataitem['pointHighlightFill'] = "#fff";
 			$dataitem['pointHighlightStroke'] = $data['color'];
 			$dataitem['data'] = [];
 			foreach(explode(",", $data['impressions']) as $key => $d){ 
				array_push($dataitem['data'], intval(trim($d)));
 			}
 			array_push($datasets, $dataitem);
 		}
   	    $template = \Template::instance(); 

        $offers = $this->db->exec("SELECT tbl_products_words.product AS id, tbl_products.name FROM tbl_analytic LEFT JOIN tbl_products_words ON tbl_products_words.word = tbl_analytic.word LEFT JOIN tbl_products ON tbl_products.id = tbl_products_words.product WHERE tbl_products_words.product > 0 GROUP BY tbl_products_words.product");

        if($_POST['offers']) $this->f3->set('off', implode(",", $_POST['offers']));
        if($_POST['segments']) $this->f3->set('sgg', implode(",", $_POST['segments']));

        $this->f3->set('back', $back); 
        $this->f3->set('next', $next);
        $this->f3->set('curent', $curent);
    
        $this->f3->set('weeks', $pages); 
        $this->f3->set('offers', $offers);  
        $this->f3->set('segments', $segments);  
        $this->f3->set('companies', $companies); 
        $this->f3->set('stats', $stats);   
        $this->f3->set('labels', json_encode($labels));   
        $this->f3->set('datasets', json_encode($datasets));  
        $this->f3->set('colors', $colors);  
        echo $template->render('stat.html');

        
    }
}



