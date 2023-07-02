<?php
class Assets_model extends CI_Model {

    private $table = 'tb_edge_asset';
    private $fp_table ='tb_edge_floorplan';
    public function __construct()
    {
        $this->load->database();
    }

    /**
     * Get the asset list
     * function get_assets
     * 
     */

     /*
     select fp.id, fp.fp_name, count(*) as asset_cnt, sum(asset.asset_status=1) as asset_on
from tb_edge_asset as asset
join tb_edge_floorplan as fp on fp.id = asset.asset_fp_id
group by fp.id
*/
    public function get_assets($id)
    {
        $ret = array();
		$this->db->select('asset.*, fp.fp_name, fp.fp_image_url');
		$this->db->from($this->table.' asset');
        $this->db->join($this->fp_table.' fp', 'asset.asset_fp_id = fp.id');
		$query = $this->db->get();
        $ret['assets'] = $query->result_array(); 

        $this->db->select('fp.id, fp.fp_name, count(*) as asset_cnt, sum(asset.asset_status=1) as asset_on ');
		$this->db->from($this->table.' asset');
        $this->db->join($this->fp_table.' fp', 'asset.asset_fp_id = fp.id');
        $this->db->group_by('fp.id');
		$query = $this->db->get();
        $ret['floorplans'] = $query->result_array(); 

		return $ret;
    }

    /**
     * Delete assets by id
     * functioin delete_asset
     * 
     * @param int $id - floor plan id
     * 
     * @return boolean
     */
    function delete_asset($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->table);
    }

    /**
     * Store the asset to DB
     * @param data: (asset_name, ....)
     */
    function store_asset($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * Update the asset in DB
     * @param id: id of asset table
     * @param data: (asset_name, ...)
     * 
     * @return [error, message] of adding of record
     */
    function update_asset($id, $data)
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