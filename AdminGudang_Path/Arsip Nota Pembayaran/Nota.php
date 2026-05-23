<?php

class Nota
{
    private mysqli $koneksi;

    public function __construct(mysqli $conn)
    {
        $this->koneksi = $conn;
    }


    public function getArsipNota(string $sort): mysqli_result|false
    {
        $sort = ($sort === 'ASC') ? 'ASC' : 'DESC';

        return mysqli_query($this->koneksi, "
            SELECT * FROM arsip_nota
            ORDER BY tanggal_arsip $sort
        ");
    }

    public function getNotaDisetujui(string $sort): mysqli_result|false
    {
        $sort = ($sort === 'ASC') ? 'ASC' : 'DESC';

        return mysqli_query($this->koneksi, "
            SELECT * FROM nota
            WHERE status_laporan = 'disetujui'
            AND (status_arsip IS NULL OR status_arsip != 'sudah')
            ORDER BY tanggal_nota $sort
        ");
    }

    public function getFotoPath(?string $foto): ?string
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

    public function getFotoPathArsip(?string $foto): ?string
    {
        if (empty($foto)) {
            return null;
        }

        $namaFile   = basename(trim($foto));
        $serverPath = "C:/xampp/htdocs/RBPL/AdminGudang_Path/Arsip Nota Pembayaran/uploads/nota/" . $namaFile;
        $urlPath    = "../Arsip Nota Pembayaran/uploads/nota/" . $namaFile;

        if (!file_exists($serverPath)) {
            return null;
        }

        return $urlPath;
    }

    public function getFotoBase64(?string $foto): ?string
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

    public function getFotoById(int $id_nota): ?string
    {
        $stmt = mysqli_prepare($this->koneksi, "
            SELECT foto_nota FROM nota WHERE id_nota = ? LIMIT 1
        ");

        mysqli_stmt_bind_param($stmt, "i", $id_nota);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (!$result || mysqli_num_rows($result) === 0) {
            return null;
        }

        $row = mysqli_fetch_assoc($result);
        return $this->getFotoPath($row['foto_nota']);
    }

    public function arsipkanNota(int $id_nota): bool
    {
        $stmt = mysqli_prepare($this->koneksi, "
            SELECT * FROM nota WHERE id_nota = ? LIMIT 1
        ");

        mysqli_stmt_bind_param($stmt, "i", $id_nota);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (!$result || mysqli_num_rows($result) === 0) {
            return false;
        }

        $data = mysqli_fetch_assoc($result);
        $namaFile = basename(trim($data['foto_nota'] ?? ''));

        $oldPath = "C:/xampp/htdocs/RBPL/AdminGudang_Path/Input Nota Barang Masuk/uploads/nota/" . $namaFile;
        $newPath = "C:/xampp/htdocs/RBPL/AdminGudang_Path/Arsip Nota Pembayaran/uploads/nota/" . $namaFile;

        if (!empty($namaFile) && file_exists($oldPath)) {
            copy($oldPath, $newPath);
        }

        $stmtInsert = mysqli_prepare($this->koneksi, "
            INSERT INTO arsip_nota (id_nota, nomor_nota, tanggal_nota, supplier, foto_nota)
            VALUES (?, ?, ?, ?, ?)
        ");

        mysqli_stmt_bind_param(
            $stmtInsert,
            "issss",
            $data['id_nota'],
            $data['nomor_nota'],
            $data['tanggal_nota'],
            $data['supplier'],
            $namaFile
        );

        if (!mysqli_stmt_execute($stmtInsert)) {
            return false;
        }

        $stmtUpdate = mysqli_prepare($this->koneksi, "
            UPDATE nota SET status_arsip = 'sudah' WHERE id_nota = ?
        ");

        mysqli_stmt_bind_param($stmtUpdate, "i", $id_nota);

        return mysqli_stmt_execute($stmtUpdate);
    }
}
