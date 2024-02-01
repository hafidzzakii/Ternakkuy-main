<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: ../index.html');
    exit;
}

$dtb_host = "localhost";
$dtb_name = "ternakkuy";
$dtb_pass = "root";
$dtb_password = "";
$conn = mysqli_connect($dtb_host, $dtb_pass, $dtb_password, $dtb_name);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

/* mencari tahu apakah tabel item berisi atau tidak */
$query = mysqli_query($conn, "SELECT * FROM items");
$jumlahProduk = mysqli_num_rows($query);

/* mengambil isi tabel item */
$sql = "SELECT * FROM items";
$data = $conn->query($sql);

/* mengambil isi tabel kategori */
$queryKategori = mysqli_query($conn, "SELECT * FROM kategori");

function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creating item</title>
    <link href="../app/scss/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
        integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <style>
        form div {
            margin-bottom: 10px;
        }

        .content {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .card {
            border-radius: 10px;
            border: 1px solid #ddd;
            transition: box-shadow 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-img-top {
            height: 200px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            object-fit: cover;
        }

        .card-body {
            padding: 1.25rem;
        }

        .card-title {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
        }

        .card-text {
            color: #666;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-primary:focus {
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.5);
        }

        .btn-primary:not(:disabled):not(.disabled):active {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-primary:not(:disabled):not(.disabled):active:focus {
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.5);
        }

        /* Responsiveness for headings */
        @media (max-width: 576px) {
            .content h2 {
                font-size: 1.5rem;
            }
        }

        /* Responsiveness for header and footer */
        @media (max-width: 576px) {
            .header__logo img {
                max-width: 100px;
            }

            .header__links {
                display: none;
            }

            .header__menu {
                display: block;
            }

            .header__menu a {
                display: block;
                margin-bottom: 10px;
            }
        }
    </style>
</head>

<body class="loggedin">
    <header class="header">
        <div class="overlay has-fade"></div>
        <nav class="container container--pall flex flex-jc-sb flex-ai-c">
            <a href="index.html" class="header__logo">
                <img src="../image/Ternakkuy.png" alt="Ternakkuy" />
            </a>

            <a id="btnHamburger" href="#" class="header__toggle hide-for-desktop">
                <span></span>
                <span></span>
                <span></span>
            </a>

            <div class="header__links hide-for-mobile">
                <a href="productpage.php"><i class="fas fa-arrow-left"></i> Home</a>
                <a href="profile.php"><i class="fas fa-user-circle"></i> Profile</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>

        </nav>

        <div class="header__menu has-fade">
            <a href="productpage.php"><i class="fas fa-arrow-left"></i> Home</a>
            <a href="profile.php"><i class="fas fa-user-circle"></i> Profile</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </header>
    <div class="container py-1">
        <div class="content">
            <h2 class="text-center mb-4">Menambahkan Produk</h2>
        </div>
        <!-- Form untuk menambahkan produk -->
        <form action="" method="post" enctype="multipart/form-data">
            <!-- Kolom nama produk -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Produk</label>
                    <input type="text" id="nama" name="nama" class="form-control" autocomplete="off" required>
                </div>
                <!-- Kolom kategori produk -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kategori Produk</label>
                    <select name="idkategori" id="idkategori" class="form-control" required>
                        <option value="">---Pilih satu---</option>
                        <?php
                        // Mengambil dan menampilkan pilihan kategori produk
                        $queryKategori = mysqli_query($conn, "SELECT * FROM kategori");
                        while ($kategori = mysqli_fetch_assoc($queryKategori)) {
                            echo "<option value='" . $kategori['idkategori'] . "'>" . $kategori['nama'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <!-- Kolom harga -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Harga</label>
                    <input type="number" name="harga" class="form-control" required>
                </div>
                <!-- Kolom foto -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Foto</label>
                    <input type="file" name="foto" id="foto" class="form-control">
                </div>
            </div>
            <div class="row">
                <!-- Kolom deskripsi dan kontak -->
                <div class="col-md-12 mb-3">
                    <label class="form-label">Deskripsi dan Kontak</label>
                    <textarea name="desk" id="desk" cols="30" rows="4.5" class="form-control"></textarea>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" name="simpan" class="btn btn-primary btn-lg">Simpan</button>
            </div>
        </form>

        <!-- Penampilan produk yang sudah ada -->
        <div class="content">
            <h2 class="text-center mb-4">
                <?php echo mysqli_num_rows($data); ?> Produk Yang Anda Jual
            </h2>
        </div>
        <?php if ($jumlahProduk == 0) { ?>
            <blockquote class="blockquote text-center">
                <p class="mb-0">Tidak ada produk yang dijual!</p>
            </blockquote>
        <?php } else { ?>
            <div class="row">
                <?php while ($row_product = mysqli_fetch_assoc($data)) { ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img class="card-img-top" src="../image/<?php echo $row_product["foto"]; ?>" alt="Card image cap">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?php echo $row_product["nama"]; ?>
                                </h5>
                                <h6 class="card-title">Rp
                                    <?php echo $row_product["harga"]; ?>
                                </h6>
                                <p class="card-text">
                                    <?php echo $row_product["desk"]; ?>
                                </p>
                                <a href="produkDetail.php?p=<?php echo $row_product['iditem'] ?>"
                                    class="btn btn-primary">Edit</a>
                                <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script src="../app/js/script.js"></script>
</body>

</html>