<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Biodata Pembimbing - <?php echo isset($pembimbing->pembimbing_nama) ? $pembimbing->pembimbing_nama : 'Pembimbing' ?></title>
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
            <h1>DETAIL INFORMASI PEMBIMBING</h1>
            <h2>Program Praktik Kerja Lapangan (PKL)</h2>
        </div>
        
        <div class="section-title">Data Pribadi</div>
        <table class="biodata-table">
            <tr>
                <td>Nama Pembimbing</td>
                <td><?php echo isset($pembimbing->pembimbing_nama) ? $pembimbing->pembimbing_nama : '-' ?></td>
            </tr>
            <tr>
                <td>NIP</td>
                <td><?php echo isset($pembimbing->pembimbing_nip) ? $pembimbing->pembimbing_nip : '-' ?></td>
            </tr>
            <tr>
                <td>Tempat Tugas</td>
                <td><?php echo isset($pembimbing->tempat_tugas) ? $pembimbing->tempat_tugas : '-' ?></td>
            </tr>
            <tr>
                <td>Tempat Lahir</td>
                <td><?php echo isset($pembimbing->tempat_lahir) ? $pembimbing->tempat_lahir : '-' ?></td>
            </tr>
            <tr>
                <td>Tanggal Lahir</td>
                <td><?php 
                    $tanggal_lahir = isset($pembimbing->tanggal_lahir) ? $pembimbing->tanggal_lahir : '';
                    echo $tanggal_lahir ? date('d F Y', strtotime($tanggal_lahir)) : '-';
                ?></td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>
                    <?php 
                    $jk = isset($pembimbing->jenis_kelamin) ? $pembimbing->jenis_kelamin : '';
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
            <tr>
                <td>Alamat</td>
                <td><?php echo isset($pembimbing->pembimbing_alamat) ? $pembimbing->pembimbing_alamat : (isset($pembimbing->alamat) ? $pembimbing->alamat : '-') ?></td>
            </tr>
        </table>
        
        <div class="section-title">Data Profesional</div>
        <table class="biodata-table">
            <tr>
                <td>Pendidikan Terakhir</td>
                <td><?php echo isset($pembimbing->pendidikan_terakhir) ? $pembimbing->pendidikan_terakhir : '-' ?></td>
            </tr>
            <tr>
                <td>Jurusan Keahlian</td>
                <td><?php echo isset($pembimbing->jurusan_keahlian) ? $pembimbing->jurusan_keahlian : '-' ?></td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td><?php echo isset($pembimbing->jabatan) ? $pembimbing->jabatan : '-' ?></td>
            </tr>
            <tr>
                <td>Tahun Masuk</td>
                <td><?php echo isset($pembimbing->tahun_masuk) ? $pembimbing->tahun_masuk : '-' ?></td>
            </tr>
            <tr>
                <td>Status Kepegawaian</td>
                <td>
                    <?php 
                    if(isset($pembimbing->status_kepegawaian) && !empty($pembimbing->status_kepegawaian)) {
                        $status_labels = [
                            'PNS' => 'Pegawai Negeri Sipil',
                            'CPNS' => 'Calon Pegawai Negeri Sipil',
                            'Honorer' => 'Honorer',
                            'Kontrak' => 'Kontrak'
                        ];
                        echo isset($status_labels[$pembimbing->status_kepegawaian]) ? $status_labels[$pembimbing->status_kepegawaian] : $pembimbing->status_kepegawaian;
                    } else {
                        echo '-';
                    }
                    ?>
                </td>
            </tr>
        </table>
        
        <div class="section-title">Kontak</div>
        <table class="biodata-table">
            <tr>
                <td>Email</td>
                <td><?php echo isset($pembimbing->pembimbing_email) ? $pembimbing->pembimbing_email : '-' ?></td>
            </tr>
            <tr>
                <td>Telepon</td>
                <td><?php echo isset($pembimbing->pembimbing_telepon) ? $pembimbing->pembimbing_telepon : '-' ?></td>
            </tr>
        </table>
        
        <div class="section-title">Informasi Bimbingan</div>
        <table class="biodata-table">
            <tr>
                <td>Jumlah Siswa Bimbingan</td>
                <td><?php echo isset($jumlah_siswa) ? $jumlah_siswa . ' Siswa' : '0 Siswa' ?></td>
            </tr>
        </table>
        
        <div class="timestamp">
            Dicetak pada: <?php echo date('d F Y H:i:s') ?><br>
            <?php if(isset($pembimbing->updated_at)): ?>
            Terakhir diubah: <?php echo date('d F Y H:i:s', strtotime($pembimbing->updated_at)) ?>
            <?php endif; ?>
        </div>
        
        <div class="no-print">
            <button onclick="window.print()">🖨️ Cetak Halaman</button>
            <button onclick="window.close()">❌ Tutup</button>
        </div>
    </div>
</body>
</html>