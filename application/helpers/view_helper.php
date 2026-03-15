<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * View Helper
 * 
 * Custom helper functions for view templates
 */

if ( ! function_exists('btn_back'))
{
    /**
     * Create a back button
     * 
     * @param string $url The URL to navigate to
     * @param string $class Additional CSS classes
     * @return string HTML button element
     */
    function btn_back($url = 'javascript:history.back();', $class = '')
    {
        $ci =& get_instance();
        return '<a href="' . $url . '" class="btn btn-secondary ' . $class . '">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>';
    }
}

if ( ! function_exists('btn_submit'))
{
    /**
     * Create a submit button
     * 
     * @param string $text Button text
     * @param string $class Additional CSS classes
     * @return string HTML button element
     */
    function btn_submit($text = 'Simpan', $class = '')
    {
        return '<button type="submit" class="btn btn-primary ' . $class . '">
                    <i class="fas fa-save"></i> ' . $text . '
                </button>';
    }
}

if ( ! function_exists('btn_cancel'))
{
    /**
     * Create a cancel button
     * 
     * @param string $url The URL to navigate to
     * @param string $class Additional CSS classes
     * @return string HTML button element
     */
    function btn_cancel($url = 'javascript:history.back();', $class = '')
    {
        return '<a href="' . $url . '" class="btn btn-secondary ' . $class . '">
                    <i class="fas fa-times"></i> Batal
                </a>';
    }
}

if ( ! function_exists('btn_edit'))
{
    /**
     * Create an edit button
     * 
     * @param string $url The URL to navigate to
     * @param string $class Additional CSS classes
     * @return string HTML button element
     */
    function btn_edit($url, $class = '')
    {
        return '<a href="' . $url . '" class="btn btn-warning ' . $class . '">
                    <i class="fas fa-edit"></i> Edit
                </a>';
    }
}

if ( ! function_exists('btn_delete'))
{
    /**
     * Create a delete button
     * 
     * @param string $url The URL to navigate to
     * @param string $class Additional CSS classes
     * @return string HTML button element
     */
    function btn_delete($url, $class = '')
    {
        return '<a href="' . $url . '" class="btn btn-danger ' . $class . '" onclick="return confirm(\'Apakah Anda yakin ingin menghapus data ini?\')">
                    <i class="fas fa-trash"></i> Hapus
                </a>';
    }
}

if ( ! function_exists('btn_view'))
{
    /**
     * Create a view/detail button
     * 
     * @param string $url The URL to navigate to
     * @param string $class Additional CSS classes
     * @return string HTML button element
     */
    function btn_view($url, $class = '')
    {
        return '<a href="' . $url . '" class="btn btn-info ' . $class . '">
                    <i class="fas fa-eye"></i> Lihat
                </a>';
    }
}

if ( ! function_exists('btn_add'))
{
    /**
     * Create an add/new button
     * 
     * @param string $url The URL to navigate to
     * @param string $text Button text
     * @param string $class Additional CSS classes
     * @return string HTML button element
     */
    function btn_add($url, $text = 'Tambah', $class = '')
    {
        return '<a href="' . $url . '" class="btn btn-success ' . $class . '">
                    <i class="fas fa-plus"></i> ' . $text . '
                </a>';
    }
}

if ( ! function_exists('safe_redirect'))
{
    /**
     * Secure redirect function with flexible parameter handling
     * 
     * @param string $controller Controller name
     * @param string $method Method name (optional)
     * @param string $param Parameter/segment (optional)
     * @return void
     */
    function safe_redirect($controller, $method = null, $param = null)
    {
        $ci =& get_instance();
        
        // Build the redirect URL
        $url_segments = array($controller);
        
        if ($method) {
            $url_segments[] = $method;
        }
        
        if ($param) {
            $url_segments[] = $param;
        }
        
        $url = implode('/', $url_segments);
        
        // Use CodeIgniter's built-in redirect function
        redirect($url);
    }
}

if ( ! function_exists('ensure_upload_directories'))
{
    /**
     * Ensure all required upload directories exist with proper permissions
     * 
     * @return bool True if all directories are ready, false otherwise
     */
    function ensure_upload_directories()
    {
        $ci =& get_instance();
        $upload_path = FCPATH . 'uploads/';
        
        // Required upload directories
        $directories = array(
            'profil',
            'pengumuman', 
            'pengajuan',
            'idcard',
            'import',
            'laporan',
            'surat_permohonan',
            'berkas_pendukung',
            'dokumen',
            'lampiran'
        );
        
        $success = true;
        
        foreach ($directories as $dir) {
            $full_path = $upload_path . $dir;
            
            // Create directory if it doesn't exist
            if (!is_dir($full_path)) {
                if (!mkdir($full_path, 0777, true)) {
                    log_message('error', 'Failed to create upload directory: ' . $full_path);
                    $success = false;
                    continue;
                }
            }
            
            // Set permissions (chmod equivalent)
            if (!is_writable($full_path)) {
                if (!chmod($full_path, 0777)) {
                    log_message('error', 'Failed to set permissions for: ' . $full_path);
                    $success = false;
                }
            }
            
            // Create index.html for security
            $index_file = $full_path . '/index.html';
            if (!file_exists($index_file)) {
                $content = "<!DOCTYPE html>\n<html>\n<head>\n<title>403 Forbidden</title>\n</head>\n<body>\n<h1>Directory access is forbidden.</h1>\n</body>\n</html>";
                if (file_put_contents($index_file, $content) === false) {
                    log_message('error', 'Failed to create index.html in: ' . $full_path);
                    $success = false;
                }
            }
        }
        
        return $success;
    }
}

if ( ! function_exists('get_upload_path'))
{
    /**
     * Get the full path for an upload directory
     * 
     * @param string $directory The subdirectory name
     * @return string Full path to the upload directory
     */
    function get_upload_path($directory = '')
    {
        $base_path = FCPATH . 'uploads/';
        
        if (!empty($directory)) {
            $full_path = $base_path . trim($directory, '/') . '/';
        } else {
            $full_path = $base_path;
        }
        
        // Ensure directory exists
        if (!is_dir($full_path)) {
            mkdir($full_path, 0777, true);
        }
        
        return $full_path;
    }
}

if ( ! function_exists('is_upload_path_writable'))
{
    /**
     * Check if an upload path is writable
     * 
     * @param string $directory The subdirectory name
     * @return bool True if writable, false otherwise
     */
    function is_upload_path_writable($directory = '')
    {
        $path = get_upload_path($directory);
        return is_writable($path);
    }
}

?>