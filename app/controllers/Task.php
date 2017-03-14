 <?php
use TiBeN\CrontabManager\CrontabRepository;
use TiBeN\CrontabManager\CrontabAdapter;
use TiBeN\CrontabManager\CrontabJob;
class TaskController extends AdminController {
    function index() {

	    $crontabRepository = new CrontabRepository(new CrontabAdapter());     

	    /*$crontabJob = new CrontabJob();
	    $crontabJob->minutes = '*';
	    $crontabJob->hours = '*';
	    $crontabJob->dayOfMonth = '*';
	    $crontabJob->months = '*';
	    $crontabJob->dayOfWeek = '*';
	    $crontabJob->taskCommandLine = 'wget -O - http://capito.dr.cash/p/yws >/dev/null 2>&1';
	    $crontabJob->comments = 'Logging disk usage';
		$crontabRepository->addJob($crontabJob);
		$crontabRepository->persist();*/

		$results = $crontabRepository->findJobByRegex('/Yandex/');
		$crontabJob = $results[0];
		print_r($crontabJob);
		$crontabRepository->removeJob($crontabJob);
		$crontabRepository->persist();
			//$crontabJob = $this->CrontabJob::createFromCrontabLine('* * * * * wget -O - http://capito.dr.cash/p/yws >/dev/null 2>&1');

			/*$crontabRepository->addJo($crontabJob);
			$crontabRepository->persist();*/
    }
}
