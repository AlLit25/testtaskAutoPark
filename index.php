<?php
session_start();

function saveAPark($id, $name, $address, $wh)
{
    $mysqli = new mysqli("localhost", "root", "", "autoparktest");
    if ($id == NULL) {
        $save = "INSERT INTO `autopark` (`id`, `name`, `address`, `workingHours`) VALUES (NULL, '$name', '" . $address . "', '" . $wh . "');";
    } else {
        $save = "UPDATE `autopark` SET `name` = '$name', `address` = '$address', `workingHours` = '$wh' WHERE `autopark`.`id` = $id;";
    }

    $result = $mysqli->query($save);
    $mysqli->close();
    if ($result) return false;
    else return true;
}

function editAPArk($id)
{
    $mysqli = new mysqli("localhost", "root", "", "autoparktest");
    $search = "SELECT * FROM `autopark` WHERE `id` = $id";

    if ($result = $mysqli->query($search)) {
        while ($row = $result->fetch_assoc()) {
            $_SESSION["id"] = $row["id"];
            $_SESSION["name"] = $row["name"];
            $_SESSION["address"] = $row["address"];
            $_SESSION["workingHours"] = $row["workingHours"];
        }
    }
}

function deleteAPArk($id)
{
    $mysqli = new mysqli("localhost", "root", "", "autoparktest");
    $del = "DELETE FROM `autopark` WHERE `id` = $id";

    $result = $mysqli->query($del);
    $mysqli->close();

    if ($result) return false;
    else return true;
}

if (isset($_POST["saveAPark"])) {
    $id = $_SESSION["id"];
    $name = htmlspecialchars($_POST["name"]);
    $address = htmlspecialchars($_POST["address"]);
    $wh = htmlspecialchars($_POST["workingHours"]);

    $error = false;

    $_SESSION["name"] = $name;
    $_SESSION["address"] = $address;
    $_SESSION["workingHours"] = $wh;

    if ($name == "" || $address == "") $error = true;
    else $error = saveAPark($id, $name, $address, $wh);

    if ($error) $errorMessage = "Не удалось корректно сделать запись в базу данных";
    else {
        session_destroy();
        header("Location: index.php");
    }
}

if (isset($_GET['editAPark'])) {
    $id = $_GET['editAPark'];
    editAuto($id);
}

if (isset($_GET['delAPark'])) {
    $id = $_GET['delAPark'];

    $error = deleteAPArk($id);
    if ($error) $errorMessage = "Не удалось удалить запись из базы данных";
    else  header("Location: index.php");
}

function saveAuto($id, $number, $driver)
{
    $mysqli = new mysqli("localhost", "root", "", "autoparktest");
    $check = "SELECT * FROM `auto` WHERE `id` = $id";
    $save = "INSERT INTO `auto` (`id`, `number`, `driver`) VALUES (NULL, '$number', '" . $driver . "');";
    $update = "UPDATE `auto` SET `number` = '$number', `driver` = '$driver' WHERE `auto`.`id` = $id;";

    $result = $mysqli->query($check);
    if ($result->num_rows > 0) {
        $resultUp = $mysqli->query($update);
        $mysqli->close();
        if ($resultUp) return false;
        else return true;
    } else {
        $mysqli->query($save);
        $mysqli->close();
        return false;
    }
}

function editAuto($id)
{
    $mysqli = new mysqli("localhost", "root", "", "autoparktest");
    $search = "SELECT * FROM `auto` WHERE `id` = $id";

    if ($result = $mysqli->query($search)) {
        while ($row = $result->fetch_assoc()) {
            $_SESSION["id"] = $row["id"];
            $_SESSION["number"] = $row["number"];
            $_SESSION["driver"] = $row["driver"];
        }
    }
}

function delAuto($id)
{
    $mysqli = new mysqli("localhost", "root", "", "autoparktest");
    $del = "DELETE FROM `auto` WHERE `id` = $id";

    $result = $mysqli->query($del);
    $mysqli->close();

    if ($result) return false;
    else return true;
}


if (isset($_POST["saveAuto"])) {
    $id = $_SESSION["id"];
    $number = htmlspecialchars($_POST["number"]);
    $driver = htmlspecialchars($_POST["driver"]);

    $errorAuto = false;

    $_SESSION["number"] = $number;
    $_SESSION["driver"] = $driver;

    if ($number == "" || $driver == "") $errorAuto = true;
    else $errorAuto = saveAuto($id, $number, $driver);

    if ($errorAuto) $errorMessageA = "Не удалось сделать запись в базу данных (номер машины должен быть уникальным)";
    else {
        session_destroy();
        header("Location: index.php");
    }
}

if (isset($_GET['editAuto'])) {
    $id = $_GET['editAuto'];
    editAuto($id);
}

if (isset($_GET['delAuto'])) {
    $id = $_GET['delAuto'];

    $error = delAuto($id);
    if ($error) $errorMessage = "Не удалось удалить запись из базы данных";
    else  header("Location: index.php");
}

function delAP($id)
{
    $mysqli = new mysqli("localhost", "root", "", "autoparktest");
    $del = "DELETE FROM `autopark_auto` WHERE `id` = $id";
    $mysqli->query($del);
    $mysqli->close();

    //if ($result) return false;
    //else return true;
}

if (isset($_GET["delAP"])) {
    $id = $_GET["delAP"];
    $idP = $_SESSION["idP"];
    delAP($id);

    header("Location: index.php?showAuto=$idP");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>Test Auto Park</title>
</head>

<body class=" container">
    <h1>Автопарк</h1>
    <div class="row">
        <form class=" col-6" method="POST">
            <div class=" row pb-2">
                <label for="name" class=" col-3">Название</label>
                <input class=" form-control col-9" type="text" name="name" value="<?= $_SESSION["name"] ?>">
            </div>
            <div class="row pb-2">
                <label for="address" class=" col-3">Адрес</label>
                <input type="text" name="address" class=" col-9 form-control" value="<?= $_SESSION["address"] ?>">
            </div>
            <div class="row pb-2">
                <label for="workingHours" class=" col-3">График работы</label>
                <input type="text" name="workingHours" class=" col-9 form-control" value="<?= $_SESSION["workingHours"] ?>">
            </div>
            <p class=" text-danger"><?= $errorMessage ?></p>
            <button class="btn btn-success mb-3" name="saveAPark">Сохранить</button>

            <div>
                <div class=" row">
                    <label class=" col-3">Название</label>
                    <label class=" col-3">Адрес</label>
                    <label class=" col-3">График работы</label>
                </div>

                <?php
                $mysqli = new mysqli("localhost", "root", "", "autoparktest");
                $query = "SELECT * FROM autopark";
                if ($result = $mysqli->query($query)) {
                    while ($row = $result->fetch_assoc()) {  ?>
                        <div class="row pb-2">
                            <label class=" col-3">
                                <a href="?showAuto=<? echo $row["id"];
                                                    $_SESSION["idP"] = $row["id"]; ?>"><?= $row["name"] ?>
                                </a>
                            </label>
                            <label class=" col-3"><?= $row["address"] ?></label>
                            <label class=" col-3"><?= $row["workingHours"] ?></label>
                            <a href="?editAPark=<?= $row["id"] ?>" class="mr-2">Изменить</a>
                            <a href="?delAPark=<?= $row["id"] ?>">Удалить</a>
                        </div>
                <?
                    }
                    $result->free();
                }
                $mysqli->close();
                ?>
            </div>
        </form>

        <div class="col-6 text-center">
            <h5>Авто в автопарке</h5>
            <div class=" justify-content-center">
                <label class="col-4">Автопарк</label>
                <label class="col-4">Номер машины</label>
                <label class="col-2"></label>
                <br>
                <?
                if (isset($_GET["showAuto"])) {
                    $id = $_GET["showAuto"];
                    $mysqli = new mysqli("localhost", "root", "", "autoparktest");
                    $show = "SELECT * FROM `autopark_auto` WHERE `idpark` = $id";
                    if ($result = $mysqli->query($show)) {
                        while ($row = $result->fetch_assoc()) {
                            $idpark = $row["idpark"];
                            $selectNP = "SELECT `name` FROM `autopark` WHERE `id` =  $idpark";
                            if ($parkname = $mysqli->query($selectNP)) {
                                while ($park = $parkname->fetch_assoc()) { ?>
                                    <label class="col-4"><?= $park["name"] ?></label>
                                <? }
                                $parkname->free();
                            }

                            $idauto = $row["idauto"];
                            $selectNA = "SELECT `number` FROM `auto` WHERE `id` = $idauto";
                            if ($autoNum = $mysqli->query($selectNA)) {
                                while ($auto = $autoNum->fetch_assoc()) { ?>
                                    <label class="col-4"><?= $auto["number"] ?></label>

                            <? }
                            }
                            ?>
                            <a class="col-2" href="?delAP=<?= $row["id"] ?>">Удалить</a>
                <?
                        }
                        $result->free();
                    }
                    $mysqli->close();
                }
                ?>
            </div>
            <span>
                <form class="row justify-content-center" method="POST">
                    <select class="form-control col-4 mr-2" name="nameApConn">
                        <option disabled selected>Имя автопарка</option>
                        <?
                        $mysqli = new mysqli("localhost", "root", "", "autoparktest");
                        $query = "SELECT * FROM autopark";
                        if ($result = $mysqli->query($query)) {
                            while ($row = $result->fetch_assoc()) { ?>
                                <option><?= $row["name"] ?></option>
                        <? }
                        }

                        ?>
                    </select>
                    <select class="form-control col-4 mr-2" name="numberAutoConn">
                        <option disabled selected>Номер машины</option>
                        <?
                        $mysqli = new mysqli("localhost", "root", "", "autoparktest");
                        $query = "SELECT * FROM auto";
                        if ($result = $mysqli->query($query)) {
                            while ($row = $result->fetch_assoc()) { ?>
                                <option><?= $row["number"] ?></option>
                        <? }
                        }
                        ?>
                    </select>
                    <button class=" col-2 btn btn-success" name="addConnPA">Добавить</button>
                </form>
            </span>
        </div>
    </div>

    <hr>

    <h1>Автомобили</h1>
    <form class="col-10 pb-3" method="POST">
        <div class=" row pb-2">
            <div class=" text-center col-4">
                <label for="number">Номер машины</label>
                <input type="text" name="number" class="form-control" value="<?= $_SESSION["number"] ?>">
            </div>

            <div class=" text-center col-4">
                <label for="driver">Имя водителя</label>
                <input type="text" name="driver" class="form-control" value="<?= $_SESSION["driver"] ?>">
            </div>
        </div>
        <p class=" text-danger"><?= $errorMessageA ?></p>
        <button class="btn btn-success" name="saveAuto">Сохранить </button>

        <div class=" row">
            <label class=" col-3">Номер машины</label>
            <label class=" col-3">Имя водителя</label>
        </div>

        <?php
        $mysqli = new mysqli("localhost", "root", "", "autoparktest");
        $query = "SELECT * FROM auto";
        if ($result = $mysqli->query($query)) {
            while ($row = $result->fetch_assoc()) { ?>
                <div class="row pb-2">
                    <label class=" col-3"><?= $row["number"] ?></label>
                    <label class=" col-3"><?= $row["driver"] ?></label>
                    <a href="?editAuto=<?= $row["id"] ?>" class="mr-2">Изменить</a>
                    <a href="?delAuto=<?= $row["id"] ?>">Удалить</a>
                </div>
        <?
            }
            $result->free();
        }
        $mysqli->close();
        ?>
    </form>
    <hr>

</body>

</html>