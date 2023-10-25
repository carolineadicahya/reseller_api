<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    // GET = SELECT
    // tabel barang
    $app->get('/barang', function(Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query("SELECT * FROM barang"); //query digunakan agar langsung execute
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($result));

        return $response->withHeader("Content-Type", "application/json");
    });

    // tabel supplier
    $app->get('/supplier', function(Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query("SELECT * FROM supplier"); //query digunakan agar langsung execute
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($result));

        return $response->withHeader("Content-Type", "application/json");
    });

    // tabel reseller
    $app->get('/reseller', function(Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query("SELECT * FROM reseller"); //query digunakan agar langsung execute
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($result));

        return $response->withHeader("Content-Type", "application/json");
    });

    // tabel memesan
    $app->get('/memesan', function(Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query("SELECT * FROM memesan"); //query digunakan agar langsung execute
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($result));

        return $response->withHeader("Content-Type", "application/json");
    });

    // tabel detail_transaksi
    $app->get('/detail_pesanan', function(Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query("SELECT * FROM detail_pesanan"); //query digunakan agar langsung execute
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($result));

        return $response->withHeader("Content-Type", "application/json");
    });

    // GET by Id = SELECT by Id
    // tabel barang
    $app->get('/barang/{id_barang}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['id_barang'];
    
        // Menyiapkan SQL untuk memanggil procedure GetBarang
        $sql = "CALL GetBarang(:id_barang)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id_barang', $id, PDO::PARAM_STR);
    
        // Jalankan query
        if ($stmt->execute()) {
            // Mengambil hasil dari procedure
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                $response->getBody()->write(json_encode($result[0]));
            } else {
                // Jika data tidak ditemukan, kirim respons dengan status 404
                $response->getBody()->write(json_encode(['error' => 'Data barang tidak ditemukan']));
                $response = $response->withStatus(404);
            }
        } else {
            // Tangani kesalahan eksekusi query
            $errorInfo = $stmt->errorInfo();
            $response->getBody()->write(json_encode(['error' => $errorInfo]));
            $response = $response->withStatus(500); // Atur status kode 500 untuk kesalahan server
        }
        return $response->withHeader("Content-Type", "application/json");
    });

    // tabel supplier
    $app->get('/supplier/{id_supplier}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['id_supplier'];
    
        // Menyiapkan SQL untuk memanggil procedure GetBarang
        $sql = "CALL GetSupplier(:id_supplier)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id_supplier', $id, PDO::PARAM_STR);
    
        // Jalankan query
        if ($stmt->execute()) {
            // Mengambil hasil dari procedure
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                $response->getBody()->write(json_encode($result[0]));
            } else {
                // Jika data tidak ditemukan, kirim respons dengan status 404
                $response->getBody()->write(json_encode(['error' => 'Data supplier tidak ditemukan']));
                $response = $response->withStatus(404);
            }
        } else {
            // Tangani kesalahan eksekusi query
            $errorInfo = $stmt->errorInfo();
            $response->getBody()->write(json_encode(['error' => $errorInfo]));
            $response = $response->withStatus(500); // Atur status kode 500 untuk kesalahan server
        }
        return $response->withHeader("Content-Type", "application/json");
    });

    // tabel reseller
    $app->get('/reseller/{id_reseller}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['id_reseller'];
    
        // Menyiapkan SQL untuk memanggil procedure GetBarang
        $sql = "CALL GetReseller(:id_reseller)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id_reseller', $id, PDO::PARAM_STR);
    
        // Jalankan query
        if ($stmt->execute()) {
            // Mengambil hasil dari procedure
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                $response->getBody()->write(json_encode($result[0]));
            } else {
                // Jika data tidak ditemukan, kirim respons dengan status 404
                $response->getBody()->write(json_encode(['error' => 'Data supplier tidak ditemukan']));
                $response = $response->withStatus(404);
            }
        } else {
            // Tangani kesalahan eksekusi query
            $errorInfo = $stmt->errorInfo();
            $response->getBody()->write(json_encode(['error' => $errorInfo]));
            $response = $response->withStatus(500); // Atur status kode 500 untuk kesalahan server
        }
        return $response->withHeader("Content-Type", "application/json");
    });
    
    // tabel memesan
    $app->get('/memesan/{id_memesan}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['id_memesan'];
    
        // Menyiapkan SQL untuk memanggil procedure GetBarang
        $sql = "CALL GetPemesanan(:id_memesan)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id_memesan', $id, PDO::PARAM_STR);
    
        // Jalankan query
        if ($stmt->execute()) {
            // Mengambil hasil dari procedure
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                $response->getBody()->write(json_encode($result[0]));
            } else {
                // Jika data tidak ditemukan, kirim respons dengan status 404
                $response->getBody()->write(json_encode(['error' => 'Data supplier tidak ditemukan']));
                $response = $response->withStatus(404);
            }
        } else {
            // Tangani kesalahan eksekusi query
            $errorInfo = $stmt->errorInfo();
            $response->getBody()->write(json_encode(['error' => $errorInfo]));
            $response = $response->withStatus(500); // Atur status kode 500 untuk kesalahan server
        }
        return $response->withHeader("Content-Type", "application/json");
    });

    // tabel detail_transaksi
    $app->get('/detail_pesanan/{id_detail}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['id_detail'];
    
        // Menyiapkan SQL untuk memanggil procedure GetDetailPesanan
        $sql = "CALL GetDetailPesanan(:id_detail)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id_detail', $id, PDO::PARAM_STR);
    
        // Jalankan query
        if ($stmt->execute()) {
            // Mengambil hasil dari procedure
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                $response->getBody()->write(json_encode($result[0]));
            } else {
                // Jika data tidak ditemukan, kirim respons dengan status 404
                $response->getBody()->write(json_encode(['error' => 'Data pabrik tidak ditemukan']));
                $response = $response->withStatus(404);
            }
        } else {
            // Tangani kesalahan eksekusi query
            $errorInfo = $stmt->errorInfo();
            $response->getBody()->write(json_encode(['error' => $errorInfo]));
            $response = $response->withStatus(500); // Atur status kode 500 untuk kesalahan server
        }
        return $response->withHeader("Content-Type", "application/json");
    });

    // POST = INSERT
    // tabel barang
    $app->post('/barang', function(Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();
    
        $idBarang = $parsedBody['barang_id'];
        $Nama = $parsedBody['barang_nama'];
        $Brand = $parsedBody['barang_brand'];
        $Model = $parsedBody['barang_model'];
        $Jumlah = $parsedBody['barang_jumlah'];
    
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL AddBarang(?, ?, ?, ?, ?)');
            $query->bindParam(1, $idBarang, PDO::PARAM_STR);
            $query->bindParam(2, $Nama, PDO::PARAM_STR);
            $query->bindParam(3, $Brand, PDO::PARAM_STR);
            $query->bindParam(4, $Model, PDO::PARAM_STR);
            $query->bindParam(5, $Jumlah, PDO::PARAM_INT);
    
            $query->execute();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Detail barang disimpan dengan id ' . $idBarang
                    ]
            ));
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal menyimpan detail pesanan: ' . $e->getMessage()
                ]
            ));
        }
        return $response->withHeader("Content-Type", "application/json");
    });

    // tabel supplier
    // tabel reseller
    // tabel memesan
    // tabel detail_transaksi
    $app->post('/detail_pesanan', function(Request $request, Response $response) {
        $parsedBody = $request->getParsedBody;
    
        $idDetail = $parsedBody['id_detail'];
        $idBarang = $parsedBody['id_barang'];
        $idPesanan = $parsedBody['id_pemesanan'];
        $jumlah = $parsedBody['jumlah'];
        $harga = $parsedBody['harga'];
    
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL AddDetailPesanan(?, ?, ?, ?, ?)');
            $query->bindParam(1, $idDetail, PDO::PARAM_STR);
            $query->bindParam(2, $idBarang, PDO::PARAM_STR);
            $query->bindParam(3, $idPesanan, PDO::PARAM_STR);
            $query->bindParam(4, $jumlah, PDO::PARAM_INT);
            $query->bindParam(5, $harga, PDO::PARAM_INT);
    
            $query->execute();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Detail pesanan disimpan dengan id ' . $idDetail
                ]
            ));
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal menyimpan detail pesanan: ' . $e->getMessage()
                ]
            ));
        }
        return $response->withHeader("Content-Type", "application/json");
    });


    // PUT = UPDATE
    // tabel barang
    $app->put('/barang/{barang_id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['barang_id']; // Menggunakan 'id_barang' sesuai dengan yang Anda definisikan dalam rute
        $parsedBody = $request->getParsedBody();
        
        $newNama = isset($parsedBody['new_nama']) ? $parsedBody['new_nama'] : null;
        $newBrand = isset($parsedBody['new_brand']) ? $parsedBody['new_brand'] : null;
        $newModel = isset($parsedBody['new_model']) ? $parsedBody['new_model'] : null;
        $newJumlah = isset($parsedBody['new_jumlah']) ? $parsedBody['new_jumlah'] : null;
    
        if ($newNama === null && $newBrand === null && $newModel === null && $newJumlah === null) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Tidak ada data yang diperbarui.'
                ]
            ));
            return $response->withStatus(400); // Atur status kode ke 400 Bad Request atau sesuai kebutuhan
        }
        try {
            $query = $db->prepare('CALL UpdateBarang(?, ?, ?, ?, ?)');
            $query->bindParam(1, $id, PDO::PARAM_STR);
            $query->bindParam(2, $newNama, PDO::PARAM_STR);
            $query->bindParam(3, $newBrand, PDO::PARAM_STR);
            $query->bindParam(4, $newModel, PDO::PARAM_STR); // Ganti PDO::PARAM_INT menjadi PDO::PARAM_STR karena 'new_model' adalah VARCHAR
            $query->bindParam(5, $newJumlah, PDO::PARAM_INT);
        
            $query->execute();
        
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Detail barang dengan id ' . $id . ' telah diperbarui'
                ]
            ));
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal memperbarui barang: ' . $e->getMessage()
                ]
            ));
        }
        return $response->withHeader("Content-Type", "application/json");
    });

    // tabel supplier
    // tabel reseller
    // tabel memesan
    // tabel detail_transaksi
    $app->put('/detail_pesanan/{id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['id'];
        $parsedBody = $request->getParsedBody();
    
        $newIdBarang = $parsedBody['new_id_barang'];
        $newIdPemesanan = $parsedBody['new_id_pemesanan'];
        $newJumlah = $parsedBody['new_jumlah'];
        $newHarga = $parsedBody['new_harga'];
    
        try {
            $query = $db->prepare('CALL UpdateDetailPesanan(?, ?, ?, ?, ?)');
            $query->bindParam(1, $id, PDO::PARAM_STR);
            $query->bindParam(2, $newIdBarang, PDO::PARAM_STR);
            $query->bindParam(3, $newIdPemesanan, PDO::PARAM_STR);
            $query->bindParam(4, $newJumlah, PDO::PARAM_INT);
            $query->bindParam(5, $newHarga, PDO::PARAM_INT);
    
            $query->execute();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Detail pesanan dengan id ' . $id . ' telah diperbarui'
                ]
            ));
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal memperbarui detail pesanan: ' . $e->getMessage()
                ]
            ));
        }
        return $response->withHeader("Content-Type", "application/json");
    });


    // DELETE = DELETE
    // tabel barang
    $app->delete('/barang/{id_barang}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['id_barang'];
    
        try {
            $query = $db->prepare('CALL DeleteBarang(?)');
            $query->bindParam(1, $id, PDO::PARAM_STR);
    
            $query->execute();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Detail pesanan dengan id ' . $id . ' telah dihapus'
                ]
            ));
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal menghapus barang: ' . $e->getMessage()
                ]
            ));
        }
        return $response->withHeader("Content-Type", "application/json");
    });

    // tabel supplier
    // tabel reseller
    // tabel memesan
    // tabel detail_transaksi
    $app->delete('/detail_pesanan/{id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['id'];
    
        try {
            $query = $db->prepare('CALL DeleteDetailPesanan(?)');
            $query->bindParam(1, $id, PDO::PARAM_STR);
    
            $query->execute();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Detail pesanan dengan id ' . $id . ' telah dihapus'
                ]
            ));
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal menghapus detail pesanan: ' . $e->getMessage()
                ]
            ));
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });

};
