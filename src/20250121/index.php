<?php
require('db.inc.php');
$errors = [];


$errors = [];
$upload_dir = "uploads/";


$items = getDbImages();
if (isset($_POST["formSubmit"])) {
    $extention = pathinfo($_FILES["imgupload"]["name"], PATHINFO_EXTENSION);
    $checkImageSize = getimagesize($_FILES["imgupload"]["tmp_name"]);
    if ($extention === "jpeg"  || $extention === "jpg" || $extention === "png") {
        echo "extensie is oke " . $extention . "<br>";
        if ((int)$_FILES["imgupload"]["size"] < 1000000) {
            echo "file is niet te zwaar<br>";
        } else {
            $errors[] = "extensie te zwaar";
            echo "file is te zwaar<br>";
        };
    } else {
        $errors[] = "niet te juist extensie";
        echo "extensie is niet oke" . $extention . "<br>";
    }
    if (!count($errors)) {
        echo "geen errors gevonden<br>";
        $randomName = bin2hex(random_bytes(10)) . '.' . $extention;
        $upload_file = $upload_dir . $randomName;


        if (move_uploaded_file($_FILES["imgupload"]["tmp_name"], $upload_file)) {
            echo "file toegevoegd in uploads<br>";
            if (insertDbImage($upload_file)) {
                echo "toegevoegd aan db";
            } else {
                echo "niet toegevoegd aan db<br>";
            }
        } else {
            echo "fout bij uploaden<br>";
        };
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DB Images</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" integrity="sha384-tViUnnbYAV00FLIhhi3v/dWt3Jxw4gZQcNoSCxCIFNJVCx7/D55/wXsrNIRANwdD" crossorigin="anonymous">
    <style>
        img.thumb {
            height: 50px;
        }
    </style>
</head>

<body>


    <div class="container">
        <section>
            <h2>Upload Image</h2>
            <hr />

            <?php if (count($errors)) : ?>
                <div class="alert alert-danger" role="alert">
                    <ul>
                        <?php foreach ($errors as $error) : ?>
                            <li><?= $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="post" action="index.php" enctype="multipart/form-data">

                <div class="form-group mt-3">
                    <label for="imgupload" class="col-sm-2 col-form-label">Image: *</label>
                    <div>
                        <input type="file" name="imgupload" id="imgupload">
                    </div>
                </div>

                <div class="form-group mt-5">
                    <div>
                        <button type="submit" class="btn btn-primary" name="formSubmit" style="width: 100%">Add</button>
                    </div>
                </div>
            </form>


        </section>
        <main>


            <h2>Images</h2>
            <div class="table-responsive small">
                <table class="table table-hover table-striped table-sm">
                    <thead>
                        <tr>
                            <th scope="col">#ID</th>
                            <th scope="col">Image</th>
                            <th scope="col">Date</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach ($items as $item): ?>

                            <tr>
                                <td><?= $item['id']; ?></td>
                                <td><?= '<img src="' . $item['path'] . '" class="thumb"/>'; ?></td>
                                <td><?= $item['created_date']; ?></td>

                            </tr>

                        <?php endforeach; ?>


                    </tbody>
                </table>


            </div>
        </main>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>