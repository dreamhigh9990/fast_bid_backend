<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class test extends REST_Controller
{
	function build_ted_db_get()
	{
		$this->load->model("chapters_model");
		$this->load->model("sentences_model");

		$enFile = fopen("F:/Work/Together/DB/ted en-zh/ted ces en-zh.txt/TED2013.en-zh.en", "r") or die("Unable to open file!");
		$zhFile = fopen("F:/Work/Together/DB/ted en-zh/ted ces en-zh.txt/TED2013.en-zh.zh", "r") or die("Unable to open file!");

		$limit = 2000000;
		$idx = 0;
		$prevEnId = "";
		$lineIdx = 0;
		$books_id = 8;
		while(!feof($enFile)) {
			$enSt = fgets($enFile);
			$zhSt = fgets($zhFile);

			if (strpos($enSt, "http://") > 0) {
				$data_for_chapters = array(
					'books_id' => $books_id,
					'name' => $enSt,
					);

				$chapters_id = $this->chapters_model->store_chapters($data_for_chapters);

				$idx ++;
				continue;
			}

			if ($prevEnId != $enId) {
				echo "- " . $enId . "-" . $zhId . "<br>";
				echo "- " . $lineIdx . "<br>";

				$data_for_chapters = array(
					'books_id' => $books_id,
					'name' => $enId,
					);

				$chapters_id = $this->chapters_model->store_chapters($data_for_chapters);

				$idx ++;
			}

			$data_for_sentences = array(
				'chapters_id' => $chapters_id,
				'date' => date('Y-m-d H:i:s'),
				'editor_id' => 1,
			);
			$data_for_sentences['s_language'] = 'en';
			$data_for_sentences['d_language'] = 'zh-CN';
			$data_for_sentences['s_en'] = $enSt;
			$data_for_sentences['d_zh-CN'] = $zhSt;
			$last_inserted_id = $this->sentences_model->store_sentences($data_for_sentences);
			if ($last_inserted_id < 0) 
			{
				echo "failed to insert sentence.".$s."<br>";
			}

			$lineIdx ++;

			if ($idx >= $limit)
				break;

			$prevEnId = $enId;
			// echo "- " . $enSt . "<br>" . "- " . $zhSt . "<br><br>";
		}

		echo "<br>end";

		fclose($enFile);
		fclose($zhFile);
		fclose($idFile);
	}

	function build_opensubtitle_db_get()
	{
		$this->load->model("chapters_model");
		$this->load->model("sentences_model");

		$enFile = fopen("F:/Work/Together/DB/ost en-zh/moses/en-zh.txt/OpenSubtitles2016.en-zh.en", "r") or die("Unable to open file!");
		$zhFile = fopen("F:/Work/Together/DB/ost en-zh/moses/en-zh.txt/OpenSubtitles2016.en-zh.zh", "r") or die("Unable to open file!");
		$idFile = fopen("F:/Work/Together/DB/ost en-zh/moses/en-zh.txt/OpenSubtitles2016.en-zh.ids", "r") or die("Unable to open file!");
		$sqlFile = fopen("F:/Work/Together/DB/ost.sql", "wb");


		$limit = 2000000;
		$idx = 0;
		$prevEnId = "";
		$lineIdx = 0;
		$books_id = 7;
		while(!feof($enFile)) {
			$enSt = fgets($enFile);
			$zhSt = fgets($zhFile);
			$idInfo = fgets($idFile);
			if ($enSt == "")
				break;
			$enIdEndPos = strpos($idInfo, ".xml.gz");
			$enIdStartPos = strrpos(substr($idInfo, 0, $enIdEndPos), "/") + 1;
			$enId = substr($idInfo, $enIdStartPos, ($enIdEndPos - $enIdStartPos));

			$zhIdEndPos = strpos($idInfo, ".xml.gz", $enIdEndPos + strlen(".xml.gz"));
			$zhIdStartPos = strrpos(substr($idInfo, 0, $zhIdEndPos), "/") + 1;
			$zhId = substr($idInfo, $zhIdStartPos, ($zhIdEndPos - $zhIdStartPos));

			if ($prevEnId != $enId) {
				echo "- " . $enId . "-" . $zhId . "<br>";
				echo "- " . $lineIdx . "<br>";

				$data_for_chapters = array(
					'books_id' => $books_id,
					'name' => $enId,
					);

				$chapters_id = $this->chapters_model->store_chapters($data_for_chapters);
				// $c_query = sprintf("insert into `chapters`(`name`,`books_id`) values('%s', %s);\r\n", $enId, $books_id);
				// fputs($sqlFile, $c_query);
				$idx ++;
			}

			// $data_for_sentences = array(
			// 	'chapters_id' => $chapters_id,
			// 	'date' => date('Y-m-d H:i:s'),
			// 	'editor_id' => 1,
			// );
			// $data_for_sentences['s_language'] = 'en';
			// $data_for_sentences['d_language'] = 'zh-CN';
			// $data_for_sentences['s_en'] = $enSt;
			// $data_for_sentences['d_zh-CN'] = $zhSt;
			// $last_inserted_id = $this->sentences_model->store_sentences($data_for_sentences);
			// if ($last_inserted_id < 0) 
			// {
			// 	echo "failed to insert sentence.".$s."<br>";
			// }

			if ($idx >= $limit)
				break;

			$s_query = sprintf("insert into `sentences`(`chapters_id`,`editor_id`,`s_en`,`s_language`,`d_zh-CN`,`d_language`,`date`) values(%s, %s, '%s', '%s', '%s', '%s', '%s');\r\n", $chapters_id, '1', addslashes($enSt), 'en', addslashes($zhSt), 'zh-CN', date('Y-m-d H:i:s'));
			fputs($sqlFile, $s_query);

			$lineIdx ++;


			$prevEnId = $enId;
			// echo "- " . $enSt . "<br>" . "- " . $zhSt . "<br><br>";
		}

		echo "<br>end";

		fclose($enFile);
		fclose($zhFile);
		fclose($idFile);
	}

	function get_read_chapter_db_get()
	{
		$this->load->model("chapters_model");
		$this->load->model("sentences_model");
		$myfile = fopen("F:/Work/Together/DB/120 daily.txt", "r") or die("Unable to open file!");
		$en = 1;
		$ens = "";
		$chapters_id = 164;
		while(!feof($myfile)) {
			$s = fgets($myfile);
			$s = trim($s);
			$data_for_sentences = array(
				'chapters_id' => $chapters_id,
				'date' => date('Y-m-d H:i:s'),
				'editor_id' => 1,
			);
			$data_for_sentences['s_language'] = 'en';
			$data_for_sentences['d_language'] = 'zh-CN';
			if ($en == 1) {
				$ens = $s;
				// echo "------".$s."<br>";
			} else {
				$data_for_sentences['s_en'] = $ens;
				$data_for_sentences['d_zh-CN'] = $s;
				$last_inserted_id = $this->sentences_model->store_sentences($data_for_sentences);
				if ($last_inserted_id < 0) 
				{
					echo "failed to insert sentence.".$s."<br>";
				}
				// echo "-".$s."<br>";
			}
			$en = 1-$en;
		}
		fclose($myfile);
	}
	function get_read_fa_db_get()
	{
		$this->load->model("chapters_model");
		$this->load->model("sentences_model");
		$myfile = fopen("C:/xampp/htdocs/fa.txt", "r") or die("Unable to open file!");
		$en = 0;
		$ens = "";
		$chapters_id = 0;
		while(!feof($myfile)) {
			$s = fgets($myfile);
			$s = trim($s);
			if (preg_match("/\p{Han}+/u", $s)) 
			{
				if (preg_match("/^第/", $s) && strpos($s, '课') > 0 && strpos($s, '的课') == false)
				{
					echo "---------------".$s."--------------<br>";
					$data_for_chapters = array(
					'name' => $s,
					'books_id' => 3,
					);
					$chapters_id = $this->chapters_model->store_chapters($data_for_chapters);
					if($chapters_id < 0)
					{
						echo "failed to insert chapter.".$s."<br>";
					}
					$en = 1;
					continue;
				}
			}
			$data_for_sentences = array(
				'chapters_id' => $chapters_id,
				'date' => date('Y-m-d H:i:s'),
				'editor_id' => 1,
			);
			$data_for_sentences['s_language'] = 'en';
			$data_for_sentences['d_language'] = 'zh-CN';
			if ($en != 0) {
				$ens = $s;
				// echo "------".$s."<br>";
			} else {
				$data_for_sentences['s_en'] = $ens;
				$data_for_sentences['d_zh-CN'] = $s;
				$last_inserted_id = $this->sentences_model->store_sentences($data_for_sentences);
				if ($last_inserted_id < 0) 
				{
					echo "failed to insert sentence.".$s."<br>";
				}
				// echo "-".$s."<br>";
			}
			$en = 1-$en;
		}
		fclose($myfile);
	}
	function get_local_test_sentences_post()
	{
		if(!$this->post('users_id') or !$this->post('from_date') or !$this->post('to_date'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
        $users_id 	= $this->post('users_id');
        $from_date	= $this->post('from_date');
		$to_date	= $this->post('to_date');
		$sentences_count = $this->post("sentences_count");
		if (!$sentences_count)
			$sentences_count = 50;

		$this->load->model("test_model");
		$this->load->model("users_model");
		$user = $this->users_model->get_users_by_id($users_id);
        if (!$user)
        	$this->response(array("status"=>401, "message"=>"Invalid users id"), 200);

		$sentences = $this->test_model->get_local_test_sentences($users_id, $user['source_language'], $user['target_language'], $from_date, $to_date, $sentences_count);
		$sentences = array_merge($sentences['sentences'], $sentences['discussion'], $sentences['videos']);

		$this->response(array("status"=>200, "tested_sentences"=>$sentences), 200);
	}

	function get_local_test_score_history_post()
	{
		if(!$this->post('users_id') or !$this->post('from_date') or !$this->post('to_date'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
        $users_id 	= $this->post('users_id');
        $from_date	= $this->post('from_date');
		$to_date	= $this->post('to_date');

		$this->load->model("test_model");
		$history = $this->test_model->get_local_test_score_history($users_id, $from_date, $to_date);
		$this->response(array("status"=>200, "history"=>$history), 200);
	}

	function set_local_test_score_post()
	{
		if(!$this->post('users_id') or !$this->post('tested_date') or !$this->post('tested_sentences_count') or !$this->post('tested_score') or !$this->post('from_date') or !$this->post('to_date'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
        $users_id 	= $this->post('users_id');
        $tested_date	= $this->post('tested_date');
		$tested_sentences_count	= $this->post('tested_sentences_count');
		$tested_score	= $this->post('tested_score');
        $from_date	= $this->post('from_date');
		$to_date	= $this->post('to_date');

		$this->load->model("test_model");
		$result = $this->test_model->set_local_test_score($users_id, $tested_date, $tested_sentences_count, $tested_score, $from_date, $to_date);
		if (!$result)
			$this->response(array("status"=>401, "message"=>"Errors occured while saving on DB"), 200);

		$this->response(array("status"=>200, "message"=>"Successfully set local test score"), 200);

	}

	function get_global_test_sentences_post()
	{
		if(!$this->post('users_id'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
        $users_id 	= $this->post('users_id');
		$sentences_count = $this->post("sentences_count");
		if (!$sentences_count)
			$sentences_count = 50;

		$this->load->model("test_model");
		$this->load->model("users_model");
		$user = $this->users_model->get_users_by_id($users_id);
        if (!$user)
        	$this->response(array("status"=>401, "message"=>"Invalid users id"), 200);

		$sentences = $this->test_model->get_global_test_sentences($users_id, $user['source_language'], $user['target_language'], $sentences_count);

		$this->response(array("status"=>200, "tested_sentences"=>$sentences), 200);
	}

	function get_global_test_score_history_post()
	{
		if(!$this->post('users_id') or !$this->post('from_date') or !$this->post('to_date'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
        $users_id 	= $this->post('users_id');
        $from_date	= $this->post('from_date');
		$to_date	= $this->post('to_date');

		$this->load->model("test_model");
		$history = $this->test_model->get_global_test_score_history($users_id, $from_date, $to_date);
		$this->response(array("status"=>200, "history"=>$history), 200);
	}

	function set_global_test_score_post()
	{
		if(!$this->post('users_id') or !$this->post('tested_date') or !$this->post('tested_score'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
        $users_id 	= $this->post('users_id');
        $tested_date	= $this->post('tested_date');
		$tested_score	= $this->post('tested_score');

		$this->load->model("test_model");
		$result = $this->test_model->set_global_test_score($users_id, $tested_date, $tested_score);
		if (!$result)
			$this->response(array("status"=>401, "message"=>"Errors occured while saving on DB"), 200);

		$this->response(array("status"=>200, "message"=>"Successfully set global test score"), 200);

	}

	function get_test_score_history_post()
	{
		if(!$this->post('users_id') or !$this->post('from_date') or !$this->post('to_date'))
        {
            $this->response(array("status"=>400, "message"=>"Fields can not be blank"), 200);
        }
        $users_id 	= $this->post('users_id');
        $from_date	= $this->post('from_date');
		$to_date	= $this->post('to_date');

		$this->load->model("test_model");
		$history = $this->test_model->get_test_score_history($users_id, $from_date, $to_date);
		$this->response(array("status"=>200, "history"=>$history), 200);
	}
}