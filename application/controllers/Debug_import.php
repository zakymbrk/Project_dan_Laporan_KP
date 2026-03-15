<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Debug_import extends CI_Controller {

    public function index()
    {
        $this->load->model('M_user');
        $this->load->model('M_siswa');
        
        // Check counts
        $user_count = $this->db->where('level', 2)->count_all_results('tb_user');
        $siswa_count = $this->db->count_all('tb_siswa');
        
        $recent_users = $this->db->where('level', 2)->order_by('created_at', 'DESC')->limit(10)->get('tb_user')->result();
        
        echo "<h2>Debug Import Data</h2>";
        echo "<p><strong>Total Students (level 2):</strong> " . $user_count . "</p>";
        echo "<p><strong>Total Siswa Records:</strong> " . $siswa_count . "</p>";
        echo "<h3>Recent Student Records:</h3><ul>";
        
        foreach($recent_users as $user) {
            $siswa = $this->db->where('user_id', $user->id)->get('tb_siswa')->row();
            echo "<li>";
            echo "<strong>Username:</strong> " . $user->username . " | ";
            echo "<strong>Name:</strong> " . $user->nama_lengkap . " | ";
            echo "<strong>Created:</strong> " . $user->created_at . " | ";
            echo "<strong>Status:</strong> " . ($siswa ? $siswa->status_pengajuan : 'No Siswa Record');
            echo "</li>";
        }
        echo "</ul>";
        
        echo "<h3>Import Troubleshooting Guide:</h3>";
        echo "<ol>";
        echo "<li>Check if you received an error message after import</li>";
        echo "<li>Verify that your CSV/XLSX file has the correct 18 columns</li>";
        echo "<li>Ensure required fields (Username*, Name*, Password*, Class*) are filled</li>";
        echo "<li>Confirm that usernames and NIS/NISN values are unique</li>";
        echo "<li>Check that dates are in YYYY-MM-DD format</li>";
        echo "<li>Make sure passwords are at least 6 characters</li>";
        echo "</ol>";
        
        echo "<p><a href='".base_url('hubin/view/daftar-siswa')."'>Back to Student List</a></p>";
    }
}