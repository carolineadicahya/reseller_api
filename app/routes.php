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
    $app->get('/barang/{barang_id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['barang_id'];
    
        // Menyiapkan SQL untuk memanggil procedure GetBarang
        $sql = "CALL GetBarang(:barang_id)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':barang_id', $id, PDO::PARAM_STR);
    
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
    $app->get('/supplier/{supplier_id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['supplier_id'];
    
        // Menyiapkan SQL untuk memanggil procedure GetBarang
        $sql = "CALL GetSupplier(:supplier_id)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':supplier_id', $id, PDO::PARAM_STR);
    
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
    $app->get('/reseller/{reseller_id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['reseller_id'];
    
        // Menyiapkan SQL untuk memanggil procedure GetBarang
        $sql = "CALL GetReseller(:reseller_id)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':reseller_id', $id, PDO::PARAM_STR);
    
        // Jalankan query
        if ($stmt->execute()) {
            // Mengambil hasil dari procedure
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                $response->getBody()->write(json_encode($result[0]));
            } else {
                // Jika data tidak ditemukan, kirim respons dengan status 404
                $response->getBody()->write(json_encode(['error' => 'Data reseller tidak ditemukan']));
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
    $app->get('/memesan/{pemesanan_id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['pemesanan_id'];
    
        // Menyiapkan SQL untuk memanggil procedure GetBarang
        $sql = "CALL GetPemesanan(:pemesanan_id)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':pemesanan_id', $id, PDO::PARAM_STR);
    
        // Jalankan query
        if ($stmt->execute()) {
            // Mengambil hasil dari procedure
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                $response->getBody()->write(json_encode($result[0]));
            } else {
                // Jika data tidak ditemukan, kirim respons dengan status 404
                $response->getBody()->write(json_encode(['error' => 'Data pesanan tidak ditemukan']));
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
    $app->get('/detail_pesanan/{detail_id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['detail_id'];
    
        // Menyiapkan SQL untuk memanggil procedure GetDetailPesanan
        $sql = "CALL GetDetailPesanan(:detail_id)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':detail_id', $id, PDO::PARAM_STR);
    
        // Jalankan query
        if ($stmt->execute()) {
            // Mengambil hasil dari procedure
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                $response->getBody()->write(json_encode($result[0]));
            } else {
                // Jika data tidak ditemukan, kirim respons dengan status 404
                $response->getBody()->write(json_encode(['error' => 'Data detail pesanan tidak ditemukan']));
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
        $NamaBarang = $parsedBody['barang_nama'];
        $BrandBarang = $parsedBody['barang_brand'];
        $ModelBarang = $parsedBody['barang_model'];
        $JumlahBarang = $parsedBody['barang_jumlah'];
    
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL AddBarang(?, ?, ?, ?, ?)');
            $query->bindParam(1, $idBarang, PDO::PARAM_STR);
            $query->bindParam(2, $NamaBarang, PDO::PARAM_STR);
            $query->bindParam(3, $BrandBarang, PDO::PARAM_STR);
            $query->bindParam(4, $ModelBarang, PDO::PARAM_STR);
            $query->bindParam(5, $JumlahBarang, PDO::PARAM_INT);
    
            $query->execute();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Detail barang disimpan dengan id ' . $idBarang
                    ]
            ));
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal menyimpan barang: ' . $e->getMessage()
                ]
            ));
        }
        return $response->withHeader("Content-Type", "application/json");
    });

    // tabel supplier
    $app->post('/supplier', function(Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();
    
        $idSupplier = $parsedBody['supplier_id'];
        $NamaSupplier = $parsedBody['supplier_name'];
        $KontakSupplier = $parsedBody['supplier_contact'];
        $AlamatSupplier = $parsedBody['supplier_address'];
    
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL AddSupplier(?, ?, ?, ?)');
            $query->bindParam(1, $idSupplier, PDO::PARAM_STR);
            $query->bindParam(2, $NamaSupplier, PDO::PARAM_STR);
            $query->bindParam(3, $KontakSupplier, PDO::PARAM_STR);
            $query->bindParam(4, $AlamatSupplier, PDO::PARAM_STR);
    
            $query->execute();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Detail Supplier disimpan dengan id ' . $idSupplier
                    ]
            ));
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal menyimpan supplier: ' . $e->getMessage()
                ]
            ));
        }
        return $response->withHeader("Content-Type", "application/json");
    });

    // tabel reseller
    $app->post('/reseller', function(Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();
    
        $idReseller = $parsedBody['reseller_id'];
        $NamaReseller = $parsedBody['reseller_name'];
        $KontakReseller = $parsedBody['reseller_contact'];
        $AlamatReseller = $parsedBody['reseller_address'];
    
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL AddReseller(?, ?, ?, ?)');
            $query->bindParam(1, $idReseller, PDO::PARAM_STR);
            $query->bindParam(2, $NamaReseller, PDO::PARAM_STR);
            $query->bindParam(3, $KontakReseller, PDO::PARAM_STR);
            $query->bindParam(4, $AlamatReseller, PDO::PARAM_STR);
    
            $query->execute();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Detail Reseller disimpan dengan id ' . $idReseller
                    ]
            ));
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal menyimpan reseller: ' . $e->getMessage()
                ]
            ));
        }
        return $response->withHeader("Content-Type", "application/json");
    });

    // tabel memesan
    $app->post('/memesan', function(Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();
    
        $idMemesan = $parsedBody['pemesanan_id'];
        $idSupplier = $parsedBody['supplier_id'];
        $idReseller = $parsedBody['reseller_id'];
        $Tanggal = $parsedBody['pemesanan_tanggal'];
    
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL AddPemesanan(?, ?, ?, ?)');
            $query->bindParam(1, $idMemesan, PDO::PARAM_STR);
            $query->bindParam(2, $idSupplier, PDO::PARAM_STR);
            $query->bindParam(3, $idReseller, PDO::PARAM_STR);
            $query->bindParam(4, $Tanggal, PDO::PARAM_STR);
    
            $query->execute();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Detail Memesan disimpan dengan id ' . $idMemesan
                    ]
            ));
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal menyimpan memesan: ' . $e->getMessage()
                ]
            ));
        }
        return $response->withHeader("Content-Type", "application/json");
    });

    // tabel detail_transaksi
    $app->post('/detail_pesanan', function(Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();
    
        $idDetail = $parsedBody['detail_id'];
        $idBarang = $parsedBody['barang_id'];
        $idPesanan = $parsedBody['pesanan_id'];
        $jumlahDetail = $parsedBody['jumlah_barang'];
        $hargaDetail = $parsedBody['harga_barang'];
    
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL AddDetailPesanan(?, ?, ?, ?, ?)');
            $query->bindParam(1, $idDetail, PDO::PARAM_STR);
            $query->bindParam(2, $idBarang, PDO::PARAM_STR);
            $query->bindParam(3, $idPesanan, PDO::PARAM_STR);
            $query->bindParam(4, $jumlahDetail, PDO::PARAM_INT);
            $query->bindParam(5, $hargaDetail, PDO::PARAM_INT);
    
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
    $app->put('/supplier/{supplier_id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['supplier_id']; // Menggunakan 'id_barang' sesuai dengan yang Anda definisikan dalam rute
        $parsedBody = $request->getParsedBody();
        
        $newName = isset($parsedBody['new_name']) ? $parsedBody['new_name'] : null;
        $newContact = isset($parsedBody['new_contact']) ? $parsedBody['new_contact'] : null;
        $newAddress = isset($parsedBody['new_address']) ? $parsedBody['new_address'] : null;
    
        if ($newName === null && $newContact === null && $newAddress === null) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Tidak ada data yang diperbarui.'
                ]
            ));
            return $response->withStatus(400); // Atur status kode ke 400 Bad Request atau sesuai kebutuhan
        }
        try {
            $query = $db->prepare('CALL UpdateSupplier(?, ?, ?, ?)');
            $query->bindParam(1, $id, PDO::PARAM_STR);
            $query->bindParam(2, $newName, PDO::PARAM_STR);
            $query->bindParam(3, $newContact, PDO::PARAM_STR);
            $query->bindParam(4, $newAddress, PDO::PARAM_STR);
        
            $query->execute();
        
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Detail supplier dengan id ' . $id . ' telah diperbarui'
                ]
            ));
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal memperbarui supplier: ' . $e->getMessage()
                ]
            ));
        }
        return $response->withHeader("Content-Type", "application/json");
    });
    
    // tabel reseller
    $app->put('/reseller/{reseller_id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['reseller_id']; // Menggunakan 'id_barang' sesuai dengan yang Anda definisikan dalam rute
        $parsedBody = $request->getParsedBody();
        
        $newName = isset($parsedBody['new_name']) ? $parsedBody['new_name'] : null;
        $newContact = isset($parsedBody['new_contact']) ? $parsedBody['new_contact'] : null;
        $newAddress = isset($parsedBody['new_address']) ? $parsedBody['new_address'] : null;
    
        if ($newName === null && $newContact === null && $newAddress === null) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Tidak ada data yang diperbarui.'
                ]
            ));
            return $response->withStatus(400); // Atur status kode ke 400 Bad Request atau sesuai kebutuhan
        }
        try {
            $query = $db->prepare('CALL UpdateReseller(?, ?, ?, ?)');
            $query->bindParam(1, $id, PDO::PARAM_STR);
            $query->bindParam(2, $newName, PDO::PARAM_STR);
            $query->bindParam(3, $newContact, PDO::PARAM_STR);
            $query->bindParam(4, $newAddress, PDO::PARAM_STR);
        
            $query->execute();
        
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Detail reseller dengan id ' . $id . ' telah diperbarui'
                ]
            ));
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal memperbarui reseller: ' . $e->getMessage()
                ]
            ));
        }
        return $response->withHeader("Content-Type", "application/json");
    });

    // tabel memesan
    $app->put('/memesan/{pemesanan_id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['pemesanan_id']; // Menggunakan 'id_barang' sesuai dengan yang Anda definisikan dalam rute
        $parsedBody = $request->getParsedBody();
        
        $newSupplier = isset($parsedBody['new_supplier_id']) ? $parsedBody['new_supplier_id'] : null;
        $newReseller = isset($parsedBody['new_reseller_id']) ? $parsedBody['new_reseller_id'] : null;
        $newDate = isset($parsedBody['new_pemesanan_tanggal']) ? $parsedBody['new_pemesanan_tanggal'] : null;
    
        if ($newSupplier === null && $newReseller === null && $newDate === null) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Tidak ada data yang diperbarui.'
                ]
            ));
            return $response->withStatus(400); // Atur status kode ke 400 Bad Request atau sesuai kebutuhan
        }
        try {
            $query = $db->prepare('CALL UpdatePemesanan(?, ?, ?, ?)');
            $query->bindParam(1, $id, PDO::PARAM_STR);
            $query->bindParam(2, $newSupplier, PDO::PARAM_STR);
            $query->bindParam(3, $newReseller, PDO::PARAM_STR);
            $query->bindParam(4, $newDate, PDO::PARAM_STR);
        
            $query->execute();
        
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Pesanan dengan id ' . $id . ' telah diperbarui'
                ]
            ));
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal memperbarui pesanan: ' . $e->getMessage()
                ]
            ));
        }
        return $response->withHeader("Content-Type", "application/json");
    });

    // tabel detail_transaksi
    $app->put('/detail_pesanan/{detail_id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['detail_id'];
        $parsedBody = $request->getParsedBody();

        $newIdBarang = isset($parsedBody['new_barang_id']) ? $parsedBody['new_barang_id'] : null;
        $newIdPemesanan = isset($parsedBody['new_pesanan_id']) ? $parsedBody['new_pesanan_id'] : null;
        $newJumlah = isset($parsedBody['new_jumlah_barang']) ? $parsedBody['new_jumlah_barang'] : null;
        $newHarga = isset($parsedBody['new_harga_barang']) ? $parsedBody['new_harga_barang'] : null;
    
        if ($newIdBarang === null && $newIdPemesanan === null && $newJumlah === null && $newHarga === null) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Tidak ada data yang diperbarui.'
                ]
            ));
            return $response->withStatus(400); // Atur status kode ke 400 Bad Request atau sesuai kebutuhan
        }
    
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
    $app->delete('/barang/{barang_id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['barang_id'];
    
        try {
            $query = $db->prepare('CALL DeleteBarang(?)');
            $query->bindParam(1, $id, PDO::PARAM_STR);
    
            $query->execute();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Detail barang dengan id ' . $id . ' telah dihapus'
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
    $app->delete('/supplier/{supplier_id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['supplier_id'];
    
        try {
            $query = $db->prepare('CALL DeleteSupplier(?)');
            $query->bindParam(1, $id, PDO::PARAM_STR);
    
            $query->execute();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Detail supplier dengan id ' . $id . ' telah dihapus'
                ]
            ));
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal menghapus supplier: ' . $e->getMessage()
                ]
            ));
        }
        return $response->withHeader("Content-Type", "application/json");
    });

    // tabel reseller
    $app->delete('/reseller/{reseller_id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['reseller_id'];
    
        try {
            $query = $db->prepare('CALL DeleteReseller(?)');
            $query->bindParam(1, $id, PDO::PARAM_STR);
    
            $query->execute();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Detail reseller dengan id ' . $id . ' telah dihapus'
                ]
            ));
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal menghapus reseller: ' . $e->getMessage()
                ]
            ));
        }
        return $response->withHeader("Content-Type", "application/json");
    });

    // tabel memesan
    $app->delete('/memesan/{pemesanan_id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['pemesanan_id'];
    
        try {
            $query = $db->prepare('CALL DeletePemesanan(?)');
            $query->bindParam(1, $id, PDO::PARAM_STR);
    
            $query->execute();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Pesanan dengan id ' . $id . ' telah dihapus'
                ]
            ));
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal menghapus pesanan: ' . $e->getMessage()
                ]
            ));
        }
        return $response->withHeader("Content-Type", "application/json");
    });
    
    // tabel detail_transaksi
    $app->delete('/detail_pesanan/{detail_id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['detail_id'];
    
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
