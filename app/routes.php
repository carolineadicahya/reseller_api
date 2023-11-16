<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    // PROCEDURE + TRANSACTION (CRUD)
    // GET = SELECT
    // tabel barang
    $app->get('/barang', function(Request $request, Response $response) {
        $db = $this->get(PDO::class);
    
        try {
            $stmt = $db->prepare("CALL GetAllBarang()");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            // Menutup statement setelah penggunaan
            $stmt->closeCursor();
    
            if (empty($result)) {
                $response->getBody()->write(json_encode(['error' => 'Data barang tidak ditemukan.']));
                return $response->withStatus(404); // Atur status kode ke 404 Not Found atau sesuai kebutuhan
            }
    
            $response->getBody()->write(json_encode($result));
            return $response->withHeader("Content-Type", "application/json");

        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Gagal mengambil data barang: ' . $e->getMessage()]));
            return $response->withStatus(500); // Atur status kode ke 500 Internal Server Error atau sesuai kebutuhan
        }
    });
      
    // tabel supplier
    $app->get('/supplier', function(Request $request, Response $response) {
        $db = $this->get(PDO::class);
        
        try {
            $stmt = $db->prepare("CALL GetAllSupplier()");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            // Menutup statement setelah penggunaan
            $stmt->closeCursor();
    
            if (empty($result)) {
                $response->getBody()->write(json_encode(['error' => 'Data supplier tidak ditemukan.']));
                return $response->withStatus(404)->withHeader("Content-Type", "application/json");
            }
    
            $response->getBody()->write(json_encode($result));
            return $response->withHeader("Content-Type", "application/json");
    
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Gagal mengambil data supplier: ' . $e->getMessage()]));
            return $response->withStatus(500)->withHeader("Content-Type", "application/json");
        }
    });
    
    // tabel reseller
    $app->get('/reseller', function(Request $request, Response $response) {
        $db = $this->get(PDO::class);
    
        try {
            $stmt = $db->prepare("CALL GetAllReseller()");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            // Menutup statement setelah penggunaan
            $stmt->closeCursor();
    
            if (empty($result)) {
                $response->getBody()->write(json_encode(['error' => 'Data reseller tidak ditemukan.']));
                return $response->withStatus(404)->withHeader("Content-Type", "application/json");
            }
    
            $response->getBody()->write(json_encode($result));
            return $response->withHeader("Content-Type", "application/json");
    
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Gagal mengambil data reseller: ' . $e->getMessage()]));
            return $response->withStatus(500)->withHeader("Content-Type", "application/json");
        }
    });

    // tabel memesan
    $app->get('/memesan', function(Request $request, Response $response) {
        $db = $this->get(PDO::class);
    
        try {
            $stmt = $db->prepare("CALL GetAllPemesanan()");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            // Menutup statement setelah penggunaan
            $stmt->closeCursor();
    
            if (empty($result)) {
                $response->getBody()->write(json_encode(['error' => 'Data pemesanan tidak ditemukan.']));
                return $response->withStatus(404)->withHeader("Content-Type", "application/json");
            }
    
            $response->getBody()->write(json_encode($result));
            return $response->withHeader("Content-Type", "application/json");
    
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Gagal mengambil data pemesanan: ' . $e->getMessage()]));
            return $response->withStatus(500)->withHeader("Content-Type", "application/json");
        }
    });    

    // tabel detail_transaksi
    $app->get('/detail_pesanan', function(Request $request, Response $response) {
        $db = $this->get(PDO::class);
    
        try {
            $stmt = $db->prepare("CALL GetAllDetailPesanan()");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            // Menutup statement setelah penggunaan
            $stmt->closeCursor();
    
            if (empty($result)) {
                $response->getBody()->write(json_encode(['error' => 'Data detail pesanan tidak ditemukan.']));
                return $response->withStatus(404)->withHeader("Content-Type", "application/json");
            }
    
            $response->getBody()->write(json_encode($result));
            return $response->withHeader("Content-Type", "application/json");
    
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Gagal mengambil data detail pesanan: ' . $e->getMessage()]));
            return $response->withStatus(500)->withHeader("Content-Type", "application/json");
        }
    });    


    // GET by Id = SELECT by Id
    // tabel barang
    $app->get('/barang/{barang_id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['barang_id'];
    
        try {
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
                $response->getBody()->write(json_encode(['error' => 'Gagal mengambil data barang: ' . $errorInfo[2]]));
                $response = $response->withStatus(500); // Atur status kode 500 untuk kesalahan server
            }
        } catch (PDOException $e) {
            // Tangani kesalahan PDO
            $response->getBody()->write(json_encode(['error' => 'Gagal mengambil data barang: ' . $e->getMessage()]));
            $response = $response->withStatus(500); // Atur status kode 500 untuk kesalahan server
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });    

    // tabel supplier
    $app->get('/supplier/{supplier_id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['supplier_id'];
    
        try {
            // Menyiapkan SQL untuk memanggil procedure GetSupplier
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
                $response->getBody()->write(json_encode(['error' => 'Gagal mengambil data supplier: ' . $errorInfo[2]]));
                $response = $response->withStatus(500); // Atur status kode 500 untuk kesalahan server
            }
        } catch (PDOException $e) {
            // Tangani kesalahan PDO
            $response->getBody()->write(json_encode(['error' => 'Gagal mengambil data supplier: ' . $e->getMessage()]));
            $response = $response->withStatus(500); // Atur status kode 500 untuk kesalahan server
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });

    // tabel reseller
    $app->get('/reseller/{reseller_id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['reseller_id'];
    
        try {
            // Menyiapkan SQL untuk memanggil procedure GetReseller
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
                $response->getBody()->write(json_encode(['error' => 'Gagal mengambil data reseller: ' . $errorInfo[2]]));
                $response = $response->withStatus(500); // Atur status kode 500 untuk kesalahan server
            }
        } catch (PDOException $e) {
            // Tangani kesalahan PDO
            $response->getBody()->write(json_encode(['error' => 'Gagal mengambil data reseller: ' . $e->getMessage()]));
            $response = $response->withStatus(500); // Atur status kode 500 untuk kesalahan server
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });    
    
    // tabel memesan
    $app->get('/memesan/{pemesanan_id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['pemesanan_id'];
    
        try {
            // Menyiapkan SQL untuk memanggil procedure GetPemesanan
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
                $response->getBody()->write(json_encode(['error' => 'Gagal mengambil data pesanan: ' . $errorInfo[2]]));
                $response = $response->withStatus(500); // Atur status kode 500 untuk kesalahan server
            }
        } catch (PDOException $e) {
            // Tangani kesalahan PDO
            $response->getBody()->write(json_encode(['error' => 'Gagal mengambil data pesanan: ' . $e->getMessage()]));
            $response = $response->withStatus(500); // Atur status kode 500 untuk kesalahan server
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });    

    // tabel detail_transaksi
    $app->get('/detail_pesanan/{detail_id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['detail_id'];
    
        try {
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
                $response->getBody()->write(json_encode(['error' => 'Gagal mengambil data detail pesanan: ' . $errorInfo[2]]));
                $response = $response->withStatus(500); // Atur status kode 500 untuk kesalahan server
            }
        } catch (PDOException $e) {
            // Tangani kesalahan PDO
            $response->getBody()->write(json_encode(['error' => 'Gagal mengambil data detail pesanan: ' . $e->getMessage()]));
            $response = $response->withStatus(500); // Atur status kode 500 untuk kesalahan server
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });


    // POST = INSERT
    // tabel barang
    $app->post('/barang', function(Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();

        // Memeriksa keberadaan dan kevalidan data
        if (
            empty($parsedBody['barang_id']) ||
            empty($parsedBody['barang_nama']) ||
            empty($parsedBody['barang_brand']) ||
            empty($parsedBody['barang_model']) ||
            !is_numeric($parsedBody['barang_jumlah'])
        ) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Semua data barang harus diisi dan jumlah harus berupa angka.'
                ]
            ));
            return $response->withStatus(400); // Atur status kode 400 Bad Request
        }
    
        $idBarang = $parsedBody['barang_id'];
        $NamaBarang = $parsedBody['barang_nama'];
        $BrandBarang = $parsedBody['barang_brand'];
        $ModelBarang = $parsedBody['barang_model'];
        $JumlahBarang = $parsedBody['barang_jumlah'];
    
        $db = $this->get(PDO::class);
    
        try {
            // Memeriksa keberadaan barang dengan ID yang sama
            $checkQuery = $db->prepare('CALL GetBarang(?)');
            $checkQuery->execute([$idBarang]);
            $existingBarang = $checkQuery->fetch(PDO::FETCH_ASSOC);
    
            if ($existingBarang) {
                $response->getBody()->write(json_encode(
                    [
                        'error' => 'Barang dengan ID ' . $idBarang . ' sudah ada.'
                    ]
                ));
                return $response->withStatus(409); // Atur status kode 409 Conflict
            }
    
            // Menyiapkan query untuk menambahkan barang
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
            $response = $response->withStatus(500); // Atur status kode 500 untuk kesalahan server
        }
        return $response->withHeader("Content-Type", "application/json");
    });
    
    // tabel supplier
    $app->post('/supplier', function(Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();
    
        // Validating the presence and validity of data
        if (
            empty($parsedBody['supplier_id']) ||
            empty($parsedBody['supplier_name']) ||
            empty($parsedBody['supplier_contact']) ||
            empty($parsedBody['supplier_address'])
        ) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Semua data supplier harus diisi.'
                ]
            ));
            return $response->withStatus(400); // Set status code 400 Bad Request
        }
    
        $idSupplier = $parsedBody['supplier_id'];
        $NamaSupplier = $parsedBody['supplier_name'];
        $KontakSupplier = $parsedBody['supplier_contact'];
        $AlamatSupplier = $parsedBody['supplier_address'];
    
        $db = $this->get(PDO::class);
    
        try {
            // Check if a supplier with the same ID already exists
            $checkQuery = $db->prepare('CALL GetSupplier(?)');
            $checkQuery->execute([$idSupplier]);
            $existingSupplier = $checkQuery->fetch(PDO::FETCH_ASSOC);
    
            if ($existingSupplier) {
                $response->getBody()->write(json_encode(
                    [
                        'error' => 'Supplier dengan ID ' . $idSupplier . ' sudah ada.'
                    ]
                ));
                return $response->withStatus(409); // Set status code 409 Conflict
            }
    
            // Prepare and execute a query to add a new supplier
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
            $response = $response->withStatus(500); // Set status code 500 for server error
        }
        return $response->withHeader("Content-Type", "application/json");
    });    

    // tabel reseller
    $app->post('/reseller', function(Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();
    
        // Validating the presence and validity of data
        if (
            empty($parsedBody['reseller_id']) ||
            empty($parsedBody['reseller_name']) ||
            empty($parsedBody['reseller_contact']) ||
            empty($parsedBody['reseller_address'])
        ) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Semua data reseller harus diisi.'
                ]
            ));
            return $response->withStatus(400); // Set status code 400 Bad Request
        }
    
        $idReseller = $parsedBody['reseller_id'];
        $NamaReseller = $parsedBody['reseller_name'];
        $KontakReseller = $parsedBody['reseller_contact'];
        $AlamatReseller = $parsedBody['reseller_address'];

        $db = $this->get(PDO::class);
        try {
            // Check if a reseller with the same ID already exists
            $checkQuery = $db->prepare('CALL GetReseller(?)');
            $checkQuery->execute([$idReseller]);
            $existingReseller = $checkQuery->fetch(PDO::FETCH_ASSOC);
    
            if ($existingReseller) {
                $response->getBody()->write(json_encode(
                    [
                        'error' => 'Reseller dengan ID ' . $idReseller . ' sudah ada.'
                    ]
                ));
                return $response->withStatus(409); // Set status code 409 Conflict
            }
    
            // Prepare and execute a query to add a new reseller
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
            $response = $response->withStatus(500); // Set status code 500 for server error
        }
        return $response->withHeader("Content-Type", "application/json");
    });    

    // tabel memesan
    $app->post('/memesan', function(Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();
    
        // Validating the presence and validity of data
        if (
            empty($parsedBody['pemesanan_id']) ||
            empty($parsedBody['supplier_id']) ||
            empty($parsedBody['reseller_id']) ||
            empty($parsedBody['pemesanan_tanggal'])
        ) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Semua data memesan harus diisi.'
                ]
            ));
            return $response->withStatus(400); // Set status code 400 Bad Request
        }
    
        $idMemesan = $parsedBody['pemesanan_id'];
        $idSupplier = $parsedBody['supplier_id'];
        $idReseller = $parsedBody['reseller_id'];
        $Tanggal = $parsedBody['pemesanan_tanggal'];
    
        $db = $this->get(PDO::class);
    
        try {
            // Check if a memesan with the same ID already exists
            $checkQuery = $db->prepare('CALL GetPemesanan(?)');
            $checkQuery->execute([$idMemesan]);
            $existingMemesan = $checkQuery->fetch(PDO::FETCH_ASSOC);
    
            if ($existingMemesan) {
                $response->getBody()->write(json_encode(
                    [
                        'error' => 'Memesan dengan ID ' . $idMemesan . ' sudah ada.'
                    ]
                ));
                return $response->withStatus(409); // Set status code 409 Conflict
            }
    
            // Prepare and execute a query to add a new memesan
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
            $response = $response->withStatus(500); // Set status code 500 for server error
        }
        return $response->withHeader("Content-Type", "application/json");
    });    

    // tabel detail_transaksi
    $app->post('/detail_pesanan', function(Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();
    
        // Validating the presence and validity of data
        if (
            empty($parsedBody['detail_id']) ||
            empty($parsedBody['barang_id']) ||
            empty($parsedBody['pesanan_id']) ||
            !is_numeric($parsedBody['jumlah_barang']) ||
            !is_numeric($parsedBody['harga_barang'])
        ) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Semua data detail pesanan harus diisi dan jumlah/harga harus berupa angka.'
                ]
            ));
            return $response->withStatus(400); // Set status code 400 Bad Request
        }
    
        $idDetail = $parsedBody['detail_id'];
        $idBarang = $parsedBody['barang_id'];
        $idPesanan = $parsedBody['pesanan_id'];
        $jumlahDetail = $parsedBody['jumlah_barang'];
        $hargaDetail = $parsedBody['harga_barang'];
    
        $db = $this->get(PDO::class);
    
        try {
            // Check if a detail_pesanan with the same ID already exists
            $checkQuery = $db->prepare('CALL GetDetailPesanan(?)');
            $checkQuery->execute([$idDetail]);
            $existingDetailPesanan = $checkQuery->fetch(PDO::FETCH_ASSOC);
    
            if ($existingDetailPesanan) {
                $response->getBody()->write(json_encode(
                    [
                        'error' => 'Detail pesanan dengan ID ' . $idDetail . ' sudah ada.'
                    ]
                ));
                return $response->withStatus(409); // Set status code 409 Conflict
            }
    
            // Prepare and execute a query to add a new detail pesanan
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
            $response = $response->withStatus(500); // Set status code 500 for server error
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
    
        // Memeriksa apakah barang dengan ID yang diminta untuk dihapus ada
        try {
            $checkQuery = $db->prepare('CALL Barang(?)');
            $checkQuery->execute([$id]);
            $existingBarang = $checkQuery->fetch(PDO::FETCH_ASSOC);
    
            if (!$existingBarang) {
                $response->getBody()->write(json_encode(
                    [
                        'error' => 'Barang dengan ID ' . $id . ' tidak ditemukan.'
                    ]
                ));
                return $response->withStatus(404); // Atur status kode 404 Not Found
            }
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal memeriksa keberadaan barang: ' . $e->getMessage()
                ]
            ));
            return $response->withStatus(500); // Atur status kode 500 untuk kesalahan server
        }
    
        // Menghapus data jika ada
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
            return $response->withStatus(500); // Atur status kode 500 untuk kesalahan server
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
    
            // Memeriksa apakah supplier dengan ID yang diminta untuk dihapus ada
            $checkQuery = $db->prepare('CALL GetSupplier(?)');
            $checkQuery->execute([$id]);
            $existingSupplier = $checkQuery->fetch(PDO::FETCH_ASSOC);
    
            if (!$existingSupplier) {
                $response->getBody()->write(json_encode(
                    [
                        'error' => 'Supplier dengan ID ' . $id . ' tidak ditemukan.'
                    ]
                ));
                return $response->withStatus(404); // Atur status kode 404 Not Found
            }
    
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
            return $response->withStatus(500); // Atur status kode 500 untuk kesalahan server
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });    

    // tabel reseller
    $app->delete('/reseller/{reseller_id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['reseller_id'];
    
        // Memeriksa apakah reseller dengan ID yang diminta untuk dihapus ada
        try {
            $checkQuery = $db->prepare('CALL GetReseller(?)');
            $checkQuery->execute([$id]);
            $existingReseller = $checkQuery->fetch(PDO::FETCH_ASSOC);
    
            if (!$existingReseller) {
                $response->getBody()->write(json_encode(
                    [
                        'error' => 'Reseller dengan ID ' . $id . ' tidak ditemukan.'
                    ]
                ));
                return $response->withStatus(404); // Atur status kode 404 Not Found
            }
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal memeriksa keberadaan reseller: ' . $e->getMessage()
                ]
            ));
            return $response->withStatus(500); // Atur status kode 500 untuk kesalahan server
        }
    
        // Menghapus data reseller jika ada
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
            return $response->withStatus(500); // Atur status kode 500 untuk kesalahan server
        }
    
        return $response->withHeader("Content-Type", "application/json");
    }); 

    // tabel memesan
    $app->delete('/memesan/{pemesanan_id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['pemesanan_id'];
    
        // Memeriksa apakah pesanan dengan ID yang diminta untuk dihapus ada
        try {
            $checkQuery = $db->prepare('CALL GetPemesanan(?)');
            $checkQuery->execute([$id]);
            $existingPemesanan = $checkQuery->fetch(PDO::FETCH_ASSOC);
    
            if (!$existingPemesanan) {
                $response->getBody()->write(json_encode(
                    [
                        'error' => 'Pesanan dengan ID ' . $id . ' tidak ditemukan.'
                    ]
                ));
                return $response->withStatus(404); // Atur status kode 404 Not Found
            }
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal memeriksa keberadaan pesanan: ' . $e->getMessage()
                ]
            ));
            return $response->withStatus(500); // Atur status kode 500 untuk kesalahan server
        }
    
        // Menghapus data pesanan jika ada
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
            return $response->withStatus(500); // Atur status kode 500 untuk kesalahan server
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });    
    
    // tabel detail_transaksi
    $app->delete('/detail_pesanan/{detail_id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['detail_id'];
    
        // Memeriksa apakah detail pesanan dengan ID yang diminta untuk dihapus ada
        try {
            $checkQuery = $db->prepare('CALL GetDetailPesanan(?)');
            $checkQuery->execute([$id]);
            $existingDetailPesanan = $checkQuery->fetch(PDO::FETCH_ASSOC);
    
            if (!$existingDetailPesanan) {
                $response->getBody()->write(json_encode(
                    [
                        'error' => 'Detail pesanan dengan ID ' . $id . ' tidak ditemukan.'
                    ]
                ));
                return $response->withStatus(404); // Atur status kode 404 Not Found
            }
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal memeriksa keberadaan detail pesanan: ' . $e->getMessage()
                ]
            ));
            return $response->withStatus(500); // Atur status kode 500 untuk kesalahan server
        }
    
        // Menghapus data detail pesanan jika ada
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
            return $response->withStatus(500); // Atur status kode 500 untuk kesalahan server
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });

    
    
    // FUNCTION 
    $app->get('/reseller_avg/{id_reseller}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
    
        try {
            // Ganti $id_reseller dengan $args['id_reseller']
            $stmt = $db->prepare("SELECT reseller_avg(:id_reseller)");
            $stmt->bindParam(':id_reseller', $args['id_reseller'], PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (empty($result)) {
                $response->getBody()->write(json_encode(['error' => 'Data rata-rata harga tidak ditemukan.']));
                return $response->withStatus(404);
            }
    
            $response->getBody()->write(json_encode($result));
            return $response->withHeader("Content-Type", "application/json");
    
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Gagal mengambil data rata-rata harga: ' . $e->getMessage()]));
            return $response->withStatus(500);
        }
    });
    


    // VIEW
    // Supplier - Reseller
    $app->get('/supplier-reseller', function(Request $request, Response $response) {
        $db = $this->get(PDO::class);
    
        try {
            $stmt = $db->prepare("CALL SupplierReseller()");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            // Menutup statement setelah penggunaan
            $stmt->closeCursor();
    
            if (empty($result)) {
                $response->getBody()->write(json_encode(['error' => 'Data tidak ditemukan.']));
                return $response->withStatus(404); // Atur status kode ke 404 Not Found atau sesuai kebutuhan
            }
    
            $response->getBody()->write(json_encode($result));
            return $response->withHeader("Content-Type", "application/json");

        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Gagal mengambil data: ' . $e->getMessage()]));
            return $response->withStatus(500); // Atur status kode ke 500 Internal Server Error atau sesuai kebutuhan
        }
    });

    // Detail Order
    $app->get('/detail-order', function(Request $request, Response $response) {
        $db = $this->get(PDO::class);
    
        try {
            $stmt = $db->prepare("CALL OrderDetails()");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            // Menutup statement setelah penggunaan
            $stmt->closeCursor();
    
            if (empty($result)) {
                $response->getBody()->write(json_encode(['error' => 'Detail Order tidak ditemukan.']));
                return $response->withStatus(404); // Atur status kode ke 404 Not Found atau sesuai kebutuhan
            }
    
            $response->getBody()->write(json_encode($result));
            return $response->withHeader("Content-Type", "application/json");

        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Gagal mengambil Detail Order: ' . $e->getMessage()]));
            return $response->withStatus(500); // Atur status kode ke 500 Internal Server Error atau sesuai kebutuhan
        }
    });
};
