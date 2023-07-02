<?php defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class job extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("jobs_model");
    }

    function update_job_list_get()
    {
        $skills = [3,6,7,9,10,13,44,51,58,59,69,77,90,92,95,101,106,113,116,237,247,287,292,305,320,323,334,335,343,434,454,500,502,564,598,607,613,669,690,704,741,759,772,901,954,962,979,989,992,1002,1031,1037,1042,1067,1084,1088,1092,1093,1094,1126,1226,1240,1254,1314,1315,13];
        $limit = 20;
        $skills_string = "";
        foreach ($skills as $skill) {
            $skills_string .= "&jobs%5B%5D=" . $skill;
        }
        $projects_url = "https://www.freelancer.com/api/projects/0.1/projects/active?limit={$limit}&full_description=true&job_details=true&local_details=true&location_details=true&upgrade_details=true&user_country_details=true&user_details=true&user_employer_reputation=true&user_status=true{$skills_string}&sort_field=submitdate&webapp=1&compact=true&new_errors=true&new_pools=true";

        $projects_result = file_get_contents($projects_url);
        $projects_result = json_decode($projects_result);
        if ($projects_result->status == 'success') {
            $projects = $projects_result->result->projects;
            $users = $projects_result->result->users;
            foreach ($projects as $project) {
                $skills = "";
                foreach ($project->jobs as $skill) {
                    $skills .= $skill->name . ",";
                }
                $skills = trim($skills, ',');
                $user = $users->{$project->owner_id};

                if (count($this->jobs_model->get_jobs_by_id($project->id)) == 0) {
                    $this->jobs_model->store_job(
                        array('jobs_id' => $project->id,
                            'title' => $project->title,
                            'description' => $project->description,
                            'country' => $user->location->country->name,
                            'payment' => $user->status->payment_verified,
                            'reviews' => isset($user->employer_reputation->entire_history->reviews) ? $user->employer_reputation->entire_history->reviews : 0,
                            'score' => $user->employer_reputation->earnings_score,
                            'since' => date('Y-m-d', $user->registration_date),
                            'min_budget' => $project->budget->minimum,
                            'max_budget' => isset($project->budget->maximum) ? $project->budget->maximum : null,
                            'skills' => $skills,
                            'url' => $project->seo_url,
                            'currency' => $project->currency->code,
                            'type' => $project->type,
                            'created_at' => date('Y-m-d H:i:s')
                        )
                    );
                }
            }
        }
        $this->response(array("status" => 'success'), 200);
    }

    function get_jobs_count_get()
    {
        $count = $this->jobs_model->count_jobs();
        $this->response(array("count" => $count, "status" => 200), 200);
    }

    function get_jobs_detail_get()
    {
        if (!$this->get('jobs_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $jobs_id = $this->get('jobs_id');

        $this->load->model("jobs_model");
        if ($jobs_id > 0) {
            $jobs_detail = $this->jobs_model->get_jobs_by_id($jobs_id);
            $jobs_detail = $jobs_detail[0];
            $jobs_detail['rx_chart_files'] = $this->medications_model->get_rx_chart_files($jobs_id);

            $this->response(array("status" => 200, "jobs_detail" => $jobs_detail), 200);
        } else {
            $this->response(array("status" => 400, "message" => "Invalid jobs ID"), 200);
        }
    }

    function get_jobs_list_get()
    {
        $page = $this->get('_page');
        $page = ($page) ? $page - 1 : 0;
        $limit = $this->get('_limit');
        $limit = ($limit) ? $limit : 10;
        $jobs_id_like = $this->get('jobs_id_like');
        $jobs_title_like = $this->get('title_like');
        $jobs_country_like = $this->get('country_like');
        $payment_like = $this->get('payment_like');
        $currency_like = $this->get('currency_like');
        $min_budget_like = $this->get('min_budget_like');
        $max_budget_like = $this->get('max_budget_like');
        $reviews_like = $this->get('reviews_like');
        $score_like = $this->get('score_like');
        $type_like = $this->get('type_like');
        $created_at_like = $this->get('created_at_like');
        $is_favorite = $this->get('is_favorite');
        $sort = $this->get('_sort');
        $order = $this->get('_order');

        $this->load->model("jobs_model");
        header('Access-Control-Expose-Headers: X-Total-Count, Link');
        header('x-total-count: ' . $this->jobs_model->count_jobs($jobs_id_like, $jobs_title_like, $jobs_country_like, $payment_like, $currency_like, $min_budget_like, $max_budget_like, $reviews_like, $score_like, $type_like, $created_at_like, $is_favorite));
        header('link: _');
        $users = $this->jobs_model->get_jobs($jobs_id_like, $jobs_title_like, $jobs_country_like, $payment_like, $currency_like, $min_budget_like, $max_budget_like, $reviews_like, $score_like, $type_like, $created_at_like, $is_favorite, $limit, $page * $limit, $sort, $order);
        $this->response($users, 200);
    }

    function delete_job_post()
    {
        if (!$this->post('jobs_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $jobs_id = $this->post('jobs_id');

        $this->load->model("jobs_model");
        if ($jobs_id > 0) {
            $this->jobs_model->delete_job($jobs_id);
            $this->response(array("status" => 200, "message" => "Successfully deleted the job"), 200);
        } else {
            $this->response(array("status" => 400, "message" => "Invalid jobs ID"), 200);
        }
    }

    function add_new_job_post()
    {
        if (!$this->post('jobs_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        if (count($this->jobs_model->get_jobs_by_id($this->post('jobs_id'))) == 0) {
            $this->load->model("jobs_model");
            $result = $this->jobs_model->store_job(
                array(
                    'jobs_id' => $this->post('jobs_id'),
                    'title' => $this->post('title'),
                    'description' => $this->post('description'),
                    'country' => $this->post('country'),
                    'payment' => ($this->post('payment') == null || $this->post('payment') == "false") ? 0 : 1,
                    'reviews' => $this->post('reviews'),
                    'score' => $this->post('score'),
                    'since' => $this->post('since'),
                    'min_budget' => (!$this->post('min_budget')) ? 0 : $this->post('min_budget'),
                    'max_budget' => (!$this->post('max_budget')) ? 0 : $this->post('max_budget'),
                    'skills' => $this->post('skills'),
                    'url' => $this->post('url'),
                    'currency' => $this->post('currency'),
                    'type' => $this->post('type'),
                    'created_at' => date('Y-m-d H:i:s')
                )
            );

            if (!$result)
                $this->response(array("status" => 401, "message" => "Errors occurred while saving on DB"), 200);
        } else {
            $this->response(array("status" => 200, "message" => "Already existing job"), 200);
        }

        $this->response(array("status" => 200, "message" => "Successfully added new job"), 200);
    }

    function update_job_post()
    {
        if (!$this->post('jobs_id')) {
            $this->response(array("status" => 400, "message" => "Fields can not be blank"), 200);
        }

        $jobs_id = $this->post('jobs_id');

        $data_for_jobs = [];

        if ($this->post('title'))
            $data_for_jobs['title'] = $this->post('title');


        $this->load->model("jobs_model");
        if ($this->jobs_model->update_job($jobs_id, $data_for_jobs) == TRUE) {
            $this->response(array("status" => 200, "message" => "Successfully updated"), 200);
        } else {
            $this->response(array("status" => 401, "message" => "Failed to update the job"), 200);
        }
    }
}
