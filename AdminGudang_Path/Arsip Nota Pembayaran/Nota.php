<?php
class Nota
{
    private $koneksi;

    public function __construct($conn)
    {
        $this->koneksi = $conn;
    }

    public function getNotaDisetujui($sort)
    {
        $sort = ($sort === 'ASC') ? 'ASC' : 'DESC';

        $query = mysqli_query($this->koneksi, "
            SELECT * FROM nota
            WHERE status_laporan = 'disetujui'
            AND (status_arsip IS NULL OR status_arsip != 'sudah')
            ORDER BY tanggal_nota $sort
        ");

        return $query;
    }

    public function getFotoPath($foto)
    {
        if (empty($foto)) {
            return null;
        }

        $namaFile   = basename(trim($foto));
        $serverPath = "C:/xampp/htdocs/RBPL/AdminGudang_Path/Input Nota Barang Masuk/uploads/nota/" . $namaFile;
        $urlPath    = "../Input Nota Barang Masuk/uploads/nota/" . $namaFile;

        if (!file_exists($serverPath)) {
            return null;
        }

        return $urlPath;
    }

    public function getFotoBase64($foto)
    {
        if (empty($foto)) {
            return null;
        }

        $serverPath = __DIR__ . "/../../Input Nota Barang Masuk/uploads/nota/" . trim($foto);

        if (!file_exists($serverPath)) {
            return null;
        }

        $mime = mime_content_type($serverPath);
        $data = base64_encode(file_get_contents($serverPath));

        return "data:{$mime};base64,{$data}";
    }

    public function getFotoById($id_nota)
    {
        $id_nota = intval($id_nota);

        $result = mysqli_query($this->koneksi, "
            SELECT foto_nota FROM nota WHERE id_nota = $id_nota LIMIT 1
        ");

        if (!$result || mysqli_num_rows($result) === 0) {
            return null;
        }

        $row = mysqli_fetch_assoc($result);
        return $this->getFotoPath($row['foto_nota']);
    }
    public function arsipkanNota($id_nota)
    {
        $id_nota = intval($id_nota);

        $result = mysqli_query($this->koneksi, "
        SELECT * FROM nota WHERE id_nota = $id_nota LIMIT 1
    ");

        if (!$result || mysqli_num_rows($result) === 0) {
            return false;
        }

        $data = mysqli_fetch_assoc($result);

        $namaFile = basename(trim($data['foto_nota']));

        $oldPath = "C:/xampp/htdocs/RBPL/AdminGudang_Path/Input Nota Barang Masuk/uploads/nota/" . $namaFile;
        $newPath = "C:/xampp/htdocs/RBPL/AdminGudang_Path/Arsip Nota Pembayaran/uploads/nota/" . $namaFile;

        if (!empty($namaFile) && file_exists($oldPath)) {
            rename($oldPath, $newPath);
        }

        $insert = mysqli_query($this->koneksi, "
        INSERT INTO arsip_nota (id_nota, nomor_nota, tanggal_nota, supplier, foto_nota)
        VALUES (
            '{$data['id_nota']}',
            '{$data['nomor_nota']}',
            '{$data['tanggal_nota']}',
            '{$data['supplier']}',
            '{$namaFile}'
        )
    ");

        if (!$insert) {
            return false;
        }

        $update = mysqli_query($this->koneksi, "
        UPDATE nota 
        SET status_arsip = 'sudah'
        WHERE id_nota = $id_nota
    ");

        return $update;
    }
}
