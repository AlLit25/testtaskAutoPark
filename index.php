<?php
session_start();

function saveAPark($id, $name, $address, $wh){
    $mysqli = new mysqli("localhost", "root", "", "autoparktest");
        if ($id == NULL) {
            $save = "INSERT INTO `autopark` (`id`, `name`, `address`, `workingHours`) VALUES (NULL, '$name', '" . $address . "', '" . $wh . "');";
        } 
        else {
            $save = "UPDATE `autopark` SET `name` = '$name', `address` = '$address', `workingHours` = '$wh' WHERE `autopark`.`id` = $id;";
        }
    
        $result = $mysqli->query($save);
        $mysqli->close();
        if ($result) return false;
        else return true;
}

function chekPark($name){
    $mysqli = new mysqli("localhost", "root", "", "autoparktest");
    $check = $mysqli->query("SELECT * FROM `autopark` WHERE `name` = '$name'");
    if($check->num_rows>0) return false;
    else return true;
}

function editAPArk($id){
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

function deleteAPArk($id){
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
    else {
        if(chekPark($name)) $error = saveAPark($id, $name, $address, $wh);
        else $error = true;
    }

    if ($error) $errorMessage = "Неудачно(Имя автопарка должно быть уникальным)";
    else {
        session_destroy();
        header("Location: index.php");
    }
}

if (isset($_GET['editAPark'])) {
    $id = $_GET['editAPark'];
    editAPArk($id);
}

if (isset($_GET['delAPark'])) {
    $id = $_GET['delAPark'];

    $error = deleteAPArk($id);
    if ($error) $errorMessage = "Не удалось удалить запись из базы данных";
    else  header("Location: index.php");
}

function saveAuto($id, $number, $driver){
    $mysqli = new mysqli("localhost", "root", "", "autoparktest");
    $save = "INSERT INTO `auto` (`id`, `number`, `driver`) VALUES (NULL, '$number', '" . $driver . "');";
    $update = "UPDATE `auto` SET `number` = '$number', `driver` = '$driver' WHERE `auto`.`id` = $id;";

    $check = $mysqli->query("SELECT * FROM `auto` WHERE `id` = $id");
    echo $check;
    if ($check->num_rows > 0) {
        print_r($check);
        $result = $mysqli->query($update);
        $mysqli->close();
        if ($result) return false;
        else return true;
    } 
    else {
        $chekErrorDB = $mysqli->query($save);
        $mysqli->close();
        if($chekErrorDB) return false;
        else return true;
        
    }
}

function editAuto($id){
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

function delAuto($id){
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

    if ($number == 0 || $driver == "") $errorAuto = true;
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

function delAP($id) {
    $mysqli = new mysqli("localhost", "root", "", "autoparktest");
    $del = "DELETE FROM `autopark_auto` WHERE `id` = $id";
    $mysqli->query($del);
    $mysqli->close();
}

if (isset($_GET["delAP"])) {
    $id = $_GET["delAP"];
    $idP = $_SESSION["idP"];
    delAP($id);

    header("Location: index.php?showAuto=$idP");
}

if(isset($_POST["addConnPA"])){
    $namePark = $_POST["nameApConn"];
    $numberAuto = $_POST["numberAutoConn"];

    if($namePark == "" || $numberAuto == 0){
        $errorRecord = true;
        $errorConnParkAuto = "Вы не выбрали все поля для заполнения";
    }
    else{
        $mysqli = new mysqli("localhost", "root", "", "autoparktest");
        $selectIdPark = $mysqli->query("SELECT `id` FROM `autopark` WHERE `name` = '".$namePark."'");
        $selectIdAuto = $mysqli->query("SELECT `id` FROM `auto` WHERE `number` = $numberAuto");

        while($sip = $selectIdPark->fetch_assoc()){
            $idP = $sip["id"];
        }
        while($sia = $selectIdAuto->fetch_assoc()){
            $idA = $sia["id"];
        }
    
        $errorRecord = false;
    
    
    
        $checkData = $mysqli->query("SELECT * FROM `autopark_auto` WHERE `idpark` = $idP AND `idauto` = $idA");
        if($checkData->num_rows>0) $errorRecord = true;
        else $mysqli->query("INSERT INTO `autopark_auto` (`id`, `idpark`, `idauto`) VALUES (NULL, '$idP', '$idA')");
        $mysqli->close();

        if($errorRecord) $errorConnParkAuto = "Эта машина уже есть в этом автопарке!";
        else header("Location: index.php?showAuto=$idP");
    }   
}

if(isset($_GET["type"])){
    $choice = $_GET["type"];
    if($choice == "manager"){
        $_SESSION["typeM"] = "hidden";
        $_SESSION["typeD"] = "";
    }
    
    if($choice == "driver"){
        $_SESSION["typeM"] = "";
        $_SESSION["typeD"] = "hidden";
    }
    if($choice == "all"){
        $_SESSION["typeM"] = "";
        $_SESSION["typeD"] = "";
    }
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
    <div class="row">
        <p>Отобразить возможности: </p>
    <a class="pr-2 pl-2" href="?type=manager">Менеджера</a>
    <a class="pr-2 pl-2" href="?type=driver">Водителя</a>
    <a class="pl-2" href="?type=all">Админ</a>
    </div>
    
    <h1>Автопарки</h1>
    <div class="row">
    
        <form class=" col-6" method="POST">
            <span <?= $_SESSION["typeD"];?>>
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
             </span>
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
                            <span <?= $_SESSION["typeD"];?>>
                            <a href="?editAPark=<?= $row["id"] ?>" class="mr-1">Изменить</a>
                            <a href="?delAPark=<?= $row["id"] ?>">Удалить</a>
                            </span>
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
                <span <?= $_SESSION["typeD"];?>><label class="col-2"></label></span>
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
                            <span <?= $_SESSION["typeD"];?>>
                                <a class="col-2" href="?delAP=<?= $row["id"] ?>">Удалить</a>
                            </span>
                            
                <?
                        }
                        $result->free();
                    }
                    $mysqli->close();
                }
                ?>
            </div>
            <span <?=$_SESSION["typeD"];?>>
                <form class="row justify-content-center" method="POST">
                    <p class=" text-danger col-11"><?= $errorConnParkAuto?></p>
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
    <span <?= $_SESSION["typeM"];?>>
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
    </span>
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
                    <span <?= $_SESSION["typeM"];?>>
                        <a href="?editAuto=<?= $row["id"] ?>" class="mr-2">Изменить</a>
                    </span>
                    <span <?= $typeD;?>>
                        <a href="?delAuto=<?= $row["id"] ?>">Удалить</a>
                    </span>
                    
                </div>
        <?
            }
            $result->free();
        }
        $mysqli->close();
        ?>
    </form>
    <hr>
<footer style="padding-top:150px;">
    <div class="row justify-content-around ">
    <label class=" col-5 border-bottom text-center"><b>"Test task AutoPark"</b> created by Lytvin Aleksandr</label>
    <label class=" col-5 border-bottom text-center text-success"> My contact: +380972687438, allitwin25@gamil.com</label>
    </div>
    <p class="text-center">2020</p>
</footer>
</body>
    
</html>