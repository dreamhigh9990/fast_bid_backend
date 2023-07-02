<?php
class Sensors_model extends CI_Model {

    private $table = 'tb_edge_sensor';
    private $fp_table ='tb_edge_floorplan';
    public function __construct()
    {
        $this->load->database();
    }

    /**
     * Get the sensor list
     * function get_sensors
     * 
     */
    public function get_sensors($id)
    {
		$this->db->select('*');
		$this->db->from($this->table);
		$query = $this->db->get();
		return $query->result_array(); 
    }

    /**
     * Delete sensors by id
     * functioin delete_sensor
     * 
     * @param int $id - floor plan id
     * 
     * @return boolean
     */
    function delete_sensor($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->table);
    }

    /**
     * Store the sensor to DB
     * @param data: (sensor_name, ....)
     */
    function store_sensor($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * Update the sensor in DB
     * @param id: id of sensor table
     * @param data: (sensor_name, ...)
     * 
     * @return [error, message] of adding of record
     */
    function update_sensor($id, $data)
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