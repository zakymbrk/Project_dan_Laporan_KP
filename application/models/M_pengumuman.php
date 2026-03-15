<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_pengumuman extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    private $table = 'tb_pengumuman';

    function get_all_pengumuman()
    {
        $this->db->select('tb_pengumuman.*, tb_user.nama_lengkap as creator_name, tb_user.username as creator_username');
        $this->db->join('tb_user', 'tb_user.id = tb_pengumuman.created_by', 'left');
        $this->db->order_by('tb_pengumuman.created_at', 'DESC');
        return $this->db->get($this->table);
    }

    function get_pengumuman($pengumuman_id)
    {
        $this->db->select('tb_pengumuman.*, tb_user.nama_lengkap as creator_name, tb_user.username as creator_username, tb_group.group_name as creator_level');
        $this->db->join('tb_user', 'tb_user.id = tb_pengumuman.created_by', 'left');
        $this->db->join('tb_group', 'tb_group.group_id = tb_user.level', 'left');
        $this->db->where('tb_pengumuman.pengumuman_id', $pengumuman_id);
        $query = $this->db->get($this->table);
        return $query->row();
    }

    function get_pengumuman_by_creator($user_id)
    {
        $this->db->select('tb_pengumuman.*, tb_user.nama_lengkap as creator_name');
        $this->db->join('tb_user', 'tb_user.id = tb_pengumuman.created_by', 'left');
        $this->db->where('tb_pengumuman.created_by', $user_id);
        $this->db->order_by('tb_pengumuman.created_at', 'DESC');
        return $this->db->get($this->table)->result();
    }

    function get_recent_pengumuman($limit = 5)
    {
        $this->db->select('tb_pengumuman.*, tb_user.nama_lengkap as creator_name');
        $this->db->join('tb_user', 'tb_user.id = tb_pengumuman.created_by', 'left');
        $this->db->order_by('tb_pengumuman.created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get($this->table)->result();
    }

    function get_pengumuman_count()
    {
        return $this->db->count_all_results($this->table);
    }

    function get_pengumuman_count_by_creator($user_id)
    {
        $this->db->where('created_by', $user_id);
        return $this->db->count_all_results($this->table);
    }

    function tambah_pengumuman($data)
    {
        // Ensure created_by is set
        if(!isset($data['created_by'])) {
            $userdata = $this->session->userdata('userdata');
            if(isset($userdata['id'])) {
                $data['created_by'] = $userdata['id'];
            }
        }
        
        // Set default values
        if(!isset($data['is_active'])) {
            $data['is_active'] = 1;
        }
        
        return $this->db->insert($this->table, $data);
    }

    function update_pengumuman($data, $pengumuman_id)
    {
        $this->db->where('pengumuman_id', $pengumuman_id);
        return $this->db->update($this->table, $data);
    }

    function delete_pengumuman($pengumuman_id)
    {
        $this->db->where('pengumuman_id', $pengumuman_id);
        return $this->db->delete($this->table);
    }

    function activate_pengumuman($pengumuman_id)
    {
        $this->db->where('pengumuman_id', $pengumuman_id);
        return $this->db->update($this->table, array('is_active' => 1));
    }

    function deactivate_pengumuman($pengumuman_id)
    {
        $this->db->where('pengumuman_id', $pengumuman_id);
        return $this->db->update($this->table, array('is_active' => 0));
    }

    function get_active_pengumuman()
    {
        $this->db->select('tb_pengumuman.*, tb_user.nama_lengkap as creator_name');
        $this->db->join('tb_user', 'tb_user.id = tb_pengumuman.created_by', 'left');
        $this->db->where('tb_pengumuman.is_active', 1);
        $this->db->order_by('tb_pengumuman.created_at', 'DESC');
        return $this->db->get($this->table)->result();
    }
}