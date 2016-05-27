<?php
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(20));
}

require 'model.php';
require 'controller.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $result = false;
    
    if (!empty($_POST['csrf_token']) && $_POST['csrf_token'] == $_SESSION['csrf_token']) {
        switch ($_POST['action']) {
            case 'add':
                $nimetus = $_POST['nimetus'];
                $kogus   = intval($_POST['kogus']);
                $result  = controller_add($nimetus, $kogus);
                break;
            case 'delete':
                $id     = intval($_POST['id']);
                $result = controller_delete($id);
                break;
            case 'update':
                $id     = intval($_POST['id']);
                $kogus  = intval($_POST['kogus']);
                $result = controller_update($id, $kogus);
                break;
            case 'register':
                $kasutajanimi = $_POST['kasutajanimi'];
                $parool       = $_POST['parool'];
                $result       = controller_register($kasutajanimi, $parool);
                break;
            case 'login':
                $kasutajanimi = $_POST['kasutajanimi'];
                $parool       = $_POST['parool'];
                $result       = controller_login($kasutajanimi, $parool);
                break;
            case 'logout':
                $result = controller_logout();
                break;
        }
    } else {
        message_add('Vigane päring, CSRF token ei vasta oodatule');
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

if (!empty($_GET['view'])) {
    switch ($_GET['view']) {
        case 'login':
            require 'view_login.php';
            break;
        case 'register':
            require 'view_register.php';
            break;
        default:
            header('Content-type: text/plain; charset=utf-8');
            echo 'Tundmatu valik!';
            exit;
    }
} else {
    if (!controller_user()) {
        header('Location: ' . $_SERVER['PHP_SELF'] . '?view=login');
        exit;
    }
    if (empty($_GET['page'])) {
        $page = 1;
    } else {
        $page = intval($_GET['page']);
        if ($page < 1) {
            $page = 1;
        }
    }
    require 'view.php';
}
mysqli_close($l);