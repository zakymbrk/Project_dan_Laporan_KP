<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Biodata Siswa - <?php echo isset($biodata->siswa_nama) ? $biodata->siswa_nama : $biodata->nama_lengkap ?></title>
    <script>
        window.onload = function() {
            // Auto-print on page load
            window.print();
        };
    </script>
    <style>
        @media print {
            @page {
                size: A4;
                margin: 15mm;
            }
            body {
                margin: 0;
                padding: 0;
                font-family: Arial, sans-serif;
                font-size: 12pt;
                line-height: 1.4;
            }
            .print-container {
                width: 100%;
                max-width: 210mm;
                margin: 0 auto;
            }
        }
        
        /* Screen display styles */
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            line-height: 1.4;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        
        .print-container {
            background: white;
            padding: 25mm;
            margin: 0 auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 210mm;
            min-height: 297mm;
        }
        
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        
        .header h1 {
            margin: 0 0 10px 0;
            font-size: 18pt;
            color: #333;
        }
        
        .header h2 {
            margin: 0;
            font-size: 14pt;
            color: #666;
            font-weight: normal;
        }
        
        .biodata-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .biodata-table tr {
            border-bottom: 1px solid #ddd;
        }
        
        .biodata-table td {
            padding: 8px 12px;
        }
        
        .biodata-table td:first-child {
            width: 40%;
            font-weight: bold;
            background-color: #f8f9fa;
        }
        
        .biodata-table td:last-child {
            width: 60%;
        }
        
        .section-title {
            font-size: 14pt;
            font-weight: bold;
            margin: 20px 0 15px 0;
            color: #333;
            border-bottom: 1px solid #333;
            padding-bottom: 5px;
        }
        
        .no-print {
            text-align: center;
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
        }
        
        .no-print button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 4px;
            margin: 0 5px;
        }
        
        .no-print button:hover {
            background-color: #0056b3;
        }
        
        .timestamp {
            font-size: 10pt;
            color: #666;
            text-align: right;
            margin-top: 30px;
            font-style: italic;
        }
        
        /* Print-specific styles */
        @media print {
            .no-print {
                display: none;
            }
            
            .print-container {
                box-shadow: none;
                padding: 15mm;
            }
            
            body {
                background-color: white;
            }
        }
    </style>
</head>
<body>
    <div class="print-container">
        <div class="header">
            <h1>INFORMASI SISWA</h1>
            <h2>Program Praktik Kerja Lapangan (PKL)</h2>
        </div>
        
        <div class="section-title">Data Pribadi</div>
        <table class="biodata-table">
            <tr>
                <td>Nama Siswa</td>
                <td><?php echo isset($biodata->siswa_nama) && $biodata->siswa_nama ? $biodata->siswa_nama : (isset($biodata->nama_lengkap) ? $biodata->nama_lengkap : '-') ?></td>
            </tr>
            <tr>
                <td>NIS / Nomor Induk Siswa</td>
                <td><?php echo isset($biodata->siswa_nis) && $biodata->siswa_nis ? $biodata->siswa_nis : '-' ?></td>
            </tr>
            <tr>
                <td>NISN / Nomor Induk Siswa Nasional</td>
                <td><?php echo isset($biodata->siswa_nisn) && $biodata->siswa_nisn ? $biodata->siswa_nisn : '-' ?></td>
            </tr>
            <tr>
                <td>Asal Sekolah</td>
                <td><?php echo isset($biodata->siswa_asal_sekolah) && $biodata->siswa_asal_sekolah ? $biodata->siswa_asal_sekolah : '-' ?></td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td><?php echo isset($biodata->siswa_kelas) && $biodata->siswa_kelas ? $biodata->siswa_kelas : '-' ?></td>
            </tr>
            <tr>
                <td>Jurusan</td>
                <td><?php echo isset($biodata->siswa_jurusan) && $biodata->siswa_jurusan ? $biodata->siswa_jurusan : '-' ?></td>
            </tr>
            <tr>
                <td>Telepon</td>
                <td><?php echo isset($biodata->siswa_telepon) && $biodata->siswa_telepon ? $biodata->siswa_telepon : (isset($biodata->telepon) ? $biodata->telepon : '-') ?></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><?php echo isset($biodata->user_email) && $biodata->user_email ? $biodata->user_email : (isset($biodata->email) && $biodata->email ? $biodata->email : '-') ?></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td><?php echo isset($biodata->alamat) && $biodata->alamat ? $biodata->alamat : (isset($biodata->siswa_alamat) ? $biodata->siswa_alamat : '-') ?></td>
            </tr>
            <tr>
                <td>Tempat Lahir</td>
                <td><?php echo isset($biodata->tempat_lahir) && $biodata->tempat_lahir ? $biodata->tempat_lahir : (isset($biodata->siswa_tempat_lahir) ? $biodata->siswa_tempat_lahir : '-') ?></td>
            </tr>
            <tr>
                <td>Tanggal Lahir</td>
                <td><?php 
                    $tanggal_lahir = isset($biodata->tanggal_lahir) && $biodata->tanggal_lahir ? $biodata->tanggal_lahir : (isset($biodata->siswa_tanggal_lahir) ? $biodata->siswa_tanggal_lahir : '');
                    echo $tanggal_lahir ? date('d F Y', strtotime($tanggal_lahir)) : '-';
                ?></td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>
                    <?php 
                    $jk = isset($biodata->jenis_kelamin) ? $biodata->jenis_kelamin : (isset($biodata->siswa_jk) ? $biodata->siswa_jk : '');
                    if($jk == 'L' || $jk == 'Laki-laki') {
                        echo 'Laki-laki';
                    } elseif($jk == 'P' || $jk == 'Perempuan') {
                        echo 'Perempuan';
                    } else {
                        echo $jk ? $jk : '-';
                    }
                    ?>
                </td>
            </tr>
            <?php if(isset($biodata->siswa_agama) && !empty($biodata->siswa_agama)): ?>
            <tr>
                <td>Agama</td>
                <td><?php echo $biodata->siswa_agama ?></td>
            </tr>
            <?php endif; ?>
        </table>
        
        <?php if(isset($biodata->dudi_nama) && $biodata->dudi_nama): ?>
        <div class="section-title">Data Perusahaan (DUDI)</div>
        <table class="biodata-table">
            <tr>
                <td>Perusahaan (DUDI)</td>
                <td><?php echo $biodata->dudi_nama ?></td>
            </tr>
            <tr>
                <td>Status Mitra Perusahaan</td>
                <td>
                    <?php if(isset($biodata->dudi_is_mitra) && $biodata->dudi_is_mitra == 1): ?>
                        <span style="color: #28a745; font-weight: bold;">Mitra</span>
                    <?php else: ?>
                        <span style="color: #ffc107; font-weight: bold;">Non-Mitra</span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>Status Kerjasama</td>
                <td>
                    <?php 
                    $status_text = '';
                    $status_color = '';
                    if(isset($biodata->status_kerjasama)) {
                        switch($biodata->status_kerjasama) {
                            case 'mitra':
                                $status_text = 'Mitra';
                                $status_color = '#28a745';
                                break;
                            case 'non_mitra':
                                $status_text = 'Non-Mitra';
                                $status_color = '#ffc107';
                                break;
                            case 'pengajuan':
                                $status_text = 'Pengajuan';
                                $status_color = '#17a2b8';
                                break;
                            default:
                                $status_text = ucfirst(str_replace('_', ' ', $biodata->status_kerjasama));
                                $status_color = '#6c757d';
                        }
                    } else {
                        $status_text = '-';
                        $status_color = '#6c757d';
                    }
                    ?>
                    <span style="color: <?php echo $status_color; ?>; font-weight: bold;"><?php echo $status_text; ?></span>
                </td>
            </tr>
            <tr>
                <td>Sumber Data Perusahaan</td>
                <td>
                    <span style="color: <?php echo (isset($biodata->sumber_data) && $biodata->sumber_data == 'siswa') ? '#007bff' : '#17a2b8'; ?>; font-weight: bold;">
                        <?php echo isset($biodata->sumber_data) ? ucfirst($biodata->sumber_data) : '-' ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td>Alamat Perusahaan</td>
                <td><?php echo isset($biodata->dudi_alamat) ? $biodata->dudi_alamat : '-' ?></td>
            </tr>
            <tr>
                <td>Telepon Perusahaan</td>
                <td><?php echo isset($biodata->dudi_telepon) ? $biodata->dudi_telepon : '-' ?></td>
            </tr>
            <tr>
                <td>Email Perusahaan</td>
                <td><?php echo isset($biodata->dudi_email) ? $biodata->dudi_email : '-' ?></td>
            </tr>
        </table>
        <?php endif; ?>
        
        <?php if(isset($biodata->pembimbing_nama) && $biodata->pembimbing_nama): ?>
        <div class="section-title">Data Pembimbing Lapangan</div>
        <table class="biodata-table">
            <tr>
                <td>Nama Pembimbing</td>
                <td><?php echo $biodata->pembimbing_nama ?></td>
            </tr>
            <tr>
                <td>Email Pembimbing</td>
                <td><?php echo isset($biodata->pembimbing_email) && $biodata->pembimbing_email ? $biodata->pembimbing_email : '-' ?></td>
            </tr>
            <tr>
                <td>Telepon Pembimbing</td>
                <td><?php echo isset($biodata->pembimbing_telepon) && $biodata->pembimbing_telepon ? $biodata->pembimbing_telepon : '-' ?></td>
            </tr>
        </table>
        <?php endif; ?>
        
        <div class="section-title">Status PKL</div>
        <table class="biodata-table">
            <tr>
                <td>Status Pengajuan PKL</td>
                <td>
                    <?php 
                    $status_pengajuan = isset($biodata->status_pengajuan) ? $biodata->status_pengajuan : 'draft';
                    $status_text = '';
                    $status_color = '';
                    
                    switch($status_pengajuan) {
                        case 'disetujui':
                            $status_text = 'Disetujui';
                            $status_color = '#28a745';
                            break;
                        case 'ditolak':
                            $status_text = 'Ditolak';
                            $status_color = '#dc3545';
                            break;
                        case 'menunggu':
                            $status_text = 'Menunggu';
                            $status_color = '#ffc107';
                            break;
                        case 'draft':
                            $status_text = 'Draft';
                            $status_color = '#6c757d';
                            break;
                        default:
                            $status_text = ucfirst($status_pengajuan);
                            $status_color = '#6c757d';
                    }
                    ?>
                    <span style="color: <?php echo $status_color; ?>; font-weight: bold;"><?php echo $status_text; ?></span>
                </td>
            </tr>
            <?php if(isset($biodata->periode) && !empty($biodata->periode)): ?>
            <tr>
                <td>Periode PKL</td>
                <td><?php echo $biodata->periode ?></td>
            </tr>
            <?php endif; ?>
            <?php if((isset($biodata->tanggal_mulai) && !empty($biodata->tanggal_mulai)) || (isset($biodata->tanggal_selesai) && !empty($biodata->tanggal_selesai))): ?>
            <tr>
                <td>Tanggal Pelaksanaan</td>
                <td>
                    <?php 
                    $mulai = isset($biodata->tanggal_mulai) ? date('d F Y', strtotime($biodata->tanggal_mulai)) : '-';
                    $selesai = isset($biodata->tanggal_selesai) ? date('d F Y', strtotime($biodata->tanggal_selesai)) : '-';
                    echo $mulai . ' s/d ' . $selesai;
                    ?>
                </td>
            </tr>
            <?php endif; ?>
        </table>
        
        <div class="timestamp">
            Dicetak pada: <?php echo date('d F Y H:i:s') ?><br>
            Terakhir diubah: <?php echo isset($biodata->updated_at) && $biodata->updated_at ? date('d F Y H:i:s', strtotime($biodata->updated_at)) : '-' ?>
        </div>
        
        <div class="no-print">
            <button onclick="window.print()">🖨️ Cetak Halaman</button>
            <button onclick="window.close()">❌ Tutup</button>
        </div>
    </div>
</body>
</html>