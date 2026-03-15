<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Holiday extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Cek login
        if(!$this->session->userdata('userdata')){
            redirect('auth/');
        }
        
        // Hanya admin (Hubin) yang bisa mengakses
        $userdata = $this->session->userdata('userdata');
        if($userdata['level'] != 1){
            redirect('auth/');
        }
        
        $this->load->model('M_user');
    }
    
    // Menampilkan daftar hari libur
    public function index()
    {
        $data['title'] = 'Kelola Hari Libur';
        $data['content'] = 'holiday/index';
        
        // Ambil data hari libur
        $this->db->select('tb_holiday.*, tb_dudi.dudi_nama');
        $this->db->from('tb_holiday');
        $this->db->join('tb_dudi', 'tb_holiday.dudi_id = tb_dudi.dudi_id', 'left');
        $this->db->order_by('tb_holiday.holiday_date', 'ASC');
        $data['holidays'] = $this->db->get()->result();
        
        // Ambil data DUDI untuk dropdown
        $data['dudi_list'] = $this->db->get('tb_dudi')->result();
        
        $this->load->view('template', $data);
    }
    
    // Menampilkan form tambah hari libur
    public function create()
    {
        $data['title'] = 'Tambah Hari Libur';
        $data['content'] = 'holiday/create';
        $data['dudi_list'] = $this->db->get('tb_dudi')->result();
        
        $this->load->view('template', $data);
    }
    
    // Menyimpan hari libur baru
    public function store()
    {
        $this->form_validation->set_rules('holiday_date', 'Tanggal Libur', 'required');
        $this->form_validation->set_rules('holiday_name', 'Nama Hari Libur', 'required');
        $this->form_validation->set_rules('holiday_type', 'Tipe Hari Libur', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'holiday_date' => $this->input->post('holiday_date'),
                'holiday_name' => $this->input->post('holiday_name'),
                'holiday_type' => $this->input->post('holiday_type'),
                'dudi_id' => $this->input->post('dudi_id') ?: NULL
            );
            
            $this->db->insert('tb_holiday', $data);
            
            $this->session->set_flashdata('message', 'Hari libur berhasil ditambahkan');
            redirect('holiday');
        }
    }
    
    // Menampilkan form edit hari libur
    public function edit($id)
    {
        $data['title'] = 'Edit Hari Libur';
        $data['content'] = 'holiday/edit';
        
        $this->db->where('holiday_id', $id);
        $data['holiday'] = $this->db->get('tb_holiday')->row();
        
        if (!$data['holiday']) {
            $this->session->set_flashdata('error_message', 'Hari libur tidak ditemukan');
            redirect('holiday');
        }
        
        $data['dudi_list'] = $this->db->get('tb_dudi')->result();
        
        $this->load->view('template', $data);
    }
    
    // Memperbarui hari libur
    public function update($id)
    {
        $this->form_validation->set_rules('holiday_date', 'Tanggal Libur', 'required');
        $this->form_validation->set_rules('holiday_name', 'Nama Hari Libur', 'required');
        $this->form_validation->set_rules('holiday_type', 'Tipe Hari Libur', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->edit($id);
        } else {
            $data = array(
                'holiday_date' => $this->input->post('holiday_date'),
                'holiday_name' => $this->input->post('holiday_name'),
                'holiday_type' => $this->input->post('holiday_type'),
                'dudi_id' => $this->input->post('dudi_id') ?: NULL
            );
            
            $this->db->where('holiday_id', $id);
            $this->db->update('tb_holiday', $data);
            
            $this->session->set_flashdata('message', 'Hari libur berhasil diperbarui');
            redirect('holiday');
        }
    }
    
    // Menghapus hari libur
    public function delete($id)
    {
        $this->db->where('holiday_id', $id);
        $this->db->delete('tb_holiday');
        
        $this->session->set_flashdata('message', 'Hari libur berhasil dihapus');
        redirect('holiday');
    }
    
    // Menampilkan kalender hari libur untuk semua pengguna
    public function calendar()
    {
        $data['title'] = 'Kalender Hari Libur';
        $data['content'] = 'holiday/calendar';
        
        // Ambil data hari libur nasional
        $this->db->where('holiday_type', 'nasional');
        $this->db->or_where('dudi_id IS NULL', null, false);
        $this->db->order_by('holiday_date', 'ASC');
        $data['national_holidays'] = $this->db->get('tb_holiday')->result();
        
        // Ambil data hari libur perusahaan
        $this->db->select('tb_holiday.*, tb_dudi.dudi_nama');
        $this->db->from('tb_holiday');
        $this->db->join('tb_dudi', 'tb_holiday.dudi_id = tb_dudi.dudi_id', 'left');
        $this->db->where('tb_holiday.holiday_type', 'perusahaan');
        $this->db->order_by('tb_holiday.holiday_date', 'ASC');
        $data['company_holidays'] = $this->db->get()->result();
        
        $this->load->view('template', $data);
    }
    
    // API untuk mendapatkan hari libur dalam format JSON
    public function api_get_holidays()
    {
        $year = $this->input->get('year') ?: date('Y');
        
        // Ambil semua hari libur untuk tahun tertentu
        $this->db->like('holiday_date', "$year-", 'after');
        $this->db->order_by('holiday_date', 'ASC');
        $holidays = $this->db->get('tb_holiday')->result();
        
        $holiday_data = array();
        foreach ($holidays as $holiday) {
            $holiday_data[$holiday->holiday_date] = array(
                'name' => $holiday->holiday_name,
                'type' => $holiday->holiday_type,
                'dudi' => $holiday->dudi_id
            );
        }
        
        header('Content-Type: application/json');
        echo json_encode($holiday_data);
    }
}