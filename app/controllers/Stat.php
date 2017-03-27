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
        //tbl_analytic.datetime_in, tbl_analytic.datetime_out,   
    	$stats = $this->db->exec("SELECT tbl_analytic.datetime_in, tbl_analytic.datetime_out, tbl_products_words.product AS product_id, tbl_products.name AS product, COUNT(*) AS words, SUM(tbl_analytic.impressions) AS impressions FROM tbl_analytic LEFT JOIN tbl_products_words ON tbl_products_words.word = tbl_analytic.word LEFT JOIN tbl_products ON tbl_products.id = tbl_products_words.product WHERE tbl_products_words.product > 0 ".$filters." GROUP BY tbl_products_words.product ORDER BY tbl_analytic.datetime_in DESC");   
 		$dates = $this->db->exec("SELECT tbl_analytic.datetime_in, tbl_analytic.datetime_out FROM tbl_analytic GROUP BY tbl_analytic.datetime_in, tbl_analytic.datetime_out ORDER BY tbl_analytic.datetime_out ASC");
 		$offers = $this->db->exec("SELECT tbl_products_words.product AS id, tbl_products.name FROM tbl_analytic LEFT JOIN tbl_products_words ON tbl_products_words.word = tbl_analytic.word LEFT JOIN tbl_products ON tbl_products.id = tbl_products_words.product WHERE tbl_products_words.product > 0 ".$filters." GROUP BY tbl_products_words.product");

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
        $this->f3->set('offers', $offers);  
        $this->f3->set('stats', $stats);   
        $this->f3->set('labels', json_encode($labels));   
        $this->f3->set('datasets', json_encode($datasets));  
        $this->f3->set('colors', $colors);  
        echo $template->render('stat.html');

        
    }
}



