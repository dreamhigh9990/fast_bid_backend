<?php
class Floorplans_model extends CI_Model {

    private $table = 'tb_edge_floorplan';
    public function __construct()
    {
        $this->load->database();
    }

    /**
     * Get the floorplan list
     * function get_floorplans
     * 
     */
    public function get_floorplans($id)
    {
		$this->db->select('*');
		$this->db->from($this->table);
		$query = $this->db->get();
		return $query->result_array(); 
    }

    /**
     * Delete floorplans by id
     * functioin delete_floorplan
     * 
     * @param int $id - floor plan id
     * 
     * @return boolean
     */
    function delete_floorplan($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->table);
    }

    /**
     * Store the floorplan to DB
     * @param data: (fp_name, fp_address, fp_map_lat, fp_map_long, fp_image_url, fp_zoom, fp_customer, fp_doctor, fp_nurse)
     */
    function store_floorplan($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * Update the floorplan in DB
     * @param id: id of floorplan table
     * @param data: (fp_name, fp_address, fp_map_lat, fp_map_long, fp_image_url, fp_zoom, fp_customer, fp_doctor, fp_nurse)
     * 
     * @return [error, message] of adding of record
     */
    function update_floorplan($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update($this->table, $data);
        $report = array();
        $report['error'] = $this->db->_error_number();
        $report['message'] = $this->db->_error_message();
        if ($report !== 0) {
            return true;
        } else {
            return false;
        }
    }
    
}