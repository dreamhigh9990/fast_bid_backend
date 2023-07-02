<?php
/*$main_categories = $this->categories_model->get_categories();
$new_array = array();
foreach ($main_categories as $key => $category) {
	if ($category['parent'] == 0)
		$new_array = add_child($new_array, $main_categories, $category);
}
$data['menu_categories'] = $new_array;

function add_child($array, $main_categories, $category) {
	$cur_array = $category;
	$cur_array['child'] = array();
	foreach ($main_categories as $key => $sub_category) {
        if ($sub_category['parent'] == $category['categories_id']) {
            $cur_array['child'] = add_child($cur_array['child'], $main_categories, $sub_category);
        }
    }
	array_push($array, $cur_array);
	return $array;
}*/

?>
<?php $this->load->view('includes/header'); ?>

<?php $this->load->view($main_content); ?>

<?php $this->load->view('includes/footer'); ?>

