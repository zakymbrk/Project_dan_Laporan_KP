<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Qr_generator {
    
    private $CI;
    
    public function __construct() {
        $this->CI =& get_instance();
    }
    
    /**
     * Generate QR code using multiple fallback methods
     * 
     * @param string $data The data to encode in QR code
     * @param int $size Size of QR code (default: 200)
     * @param string $error_correction Error correction level (L, M, Q, H)
     * @param int $margin Margin around QR code
     * @return string QR code image URL or base64 encoded image
     */
    public function generate_qr_code($data, $size = 200, $error_correction = 'M', $margin = 4) {
        // Method 1: Try Google Charts API (fallback)
        $encoded_data = urlencode($data);
        $google_qr_url = "https://chart.googleapis.com/chart?cht=qr&chs={$size}x{$size}&chl={$encoded_data}&choe=UTF-8&chld={$error_correction}|{$margin}";
        
        // Method 2: Try QR Server API
        $qrserver_url = "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data={$encoded_data}&ecc={$error_correction}&margin={$margin}";
        
        // Method 3: Try GoQR API
        $goqr_url = "https://api.goqr.me/api/qr?data={$encoded_data}&size={$size}x{$size}&ecc={$error_correction}";
        
        // Try QR Server first (more reliable)
        $headers = @get_headers($qrserver_url);
        if($headers && strpos($headers[0], '200')) {
            return $qrserver_url;
        }
        
        // Try GoQR as second option
        $headers = @get_headers($goqr_url);
        if($headers && strpos($headers[0], '200')) {
            return $goqr_url;
        }
        
        // Fallback to Google Charts
        return $google_qr_url;
    }
    
    /**
     * Generate QR code for school verification
     * 
     * @param string $siswa_id Student ID for unique identification
     * @param string $siswa_nama Student name
     * @param string $kelas Student class
     * @return string QR code image URL
     */
    public function generate_school_qr($siswa_id = '', $siswa_nama = '', $kelas = '') {
        // School verification URL
        $school_url = 'https://smk.itikurih-hibarna.sch.id/?page_id=140';
        
        // Add student information to URL parameters for verification
        if($siswa_id || $siswa_nama || $kelas) {
            $params = array();
            if($siswa_id) $params['id'] = $siswa_id;
            if($siswa_nama) $params['nama'] = urlencode($siswa_nama);
            if($kelas) $params['kelas'] = urlencode($kelas);
            
            if(!empty($params)) {
                $query_string = http_build_query($params);
                $school_url .= '&' . $query_string;
            }
        }
        
        // Generate QR code with school URL
        return $this->generate_qr_code($school_url, 150, 'M', 2);
    }
    
    /**
     * Generate QR code for specific student verification
     * 
     * @param object $siswa Student data object
     * @return string QR code image URL
     */
    public function generate_student_qr($siswa) {
        if(!$siswa) return '';
        
        return $this->generate_school_qr(
            $siswa->siswa_id,
            $siswa->siswa_nama,
            $siswa->siswa_kelas
        );
    }
    
    /**
     * Generate simple QR code for school website only
     * 
     * @return string QR code image URL
     */
    public function generate_simple_school_qr() {
        $school_url = 'https://smk.itikurih-hibarna.sch.id/?page_id=140';
        return $this->generate_qr_code($school_url, 150, 'M', 2);
    }
    
    /**
     * Generate QR code as base64 encoded data URI (fallback method)
     * 
     * @param string $data The data to encode
     * @param int $size Size of QR code
     * @return string Base64 encoded image data URI
     */
    public function generate_qr_base64($data, $size = 150) {
        // Create a simple QR-like pattern using CSS
        // This is a fallback when external APIs fail
        $encoded_data = urlencode($data);
        
        // Generate SVG QR code using external service that returns SVG
        $svg_url = "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data={$encoded_data}&format=svg";
        
        // Try to get SVG content
        $svg_content = @file_get_contents($svg_url);
        if($svg_content) {
            // Convert SVG to base64
            $base64_svg = base64_encode($svg_content);
            return "data:image/svg+xml;base64,{$base64_svg}";
        }
        
        // If SVG fails, create a placeholder
        return $this->create_placeholder_qr($data, $size);
    }
    
    /**
     * Create a simple placeholder QR code pattern
     * 
     * @param string $data The data
     * @param int $size Size
     * @return string Base64 encoded placeholder
     */
    private function create_placeholder_qr($data, $size) {
        // Create a simple canvas with text
        $canvas = imagecreate($size, $size);
        $white = imagecolorallocate($canvas, 255, 255, 255);
        $black = imagecolorallocate($canvas, 0, 0, 0);
        
        // Fill background
        imagefilledrectangle($canvas, 0, 0, $size, $size, $white);
        
        // Add border
        imagerectangle($canvas, 0, 0, $size-1, $size-1, $black);
        imagerectangle($canvas, 1, 1, $size-2, $size-2, $black);
        
        // Add text
        $font_size = 2;
        $text = substr($data, 0, 20) . '...';
        $text_width = imagefontwidth($font_size) * strlen($text);
        $text_height = imagefontheight($font_size);
        $x = ($size - $text_width) / 2;
        $y = ($size - $text_height) / 2;
        
        imagestring($canvas, $font_size, $x, $y, $text, $black);
        
        // Convert to base64
        ob_start();
        imagepng($canvas);
        $image_data = ob_get_contents();
        ob_end_clean();
        
        imagedestroy($canvas);
        
        return 'data:image/png;base64,' . base64_encode($image_data);
    }
}