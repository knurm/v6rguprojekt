<?php
$host = 'localhost';
$user = 'test';
$pass = 't3st3r123';
$db = 'test';
$l = mysqli_connect($host, $user, $pass, $db);
mysqli_query($l, 'SET CHARACTER SET UTF8');

function model_load($page)
{
    global $l;
    $max = 15;
    $start = ($page - 1) * $max;
    $query = 'SELECT Id, Nimetus, Kogus FROM knurmber__kaubad ORDER BY Nimetus ASC LIMIT ?,?';
    $stmt = mysqli_prepare($l, $query);
    if (mysqli_error($l)) {
        echo mysqli_error($l);
        exit;
    }
    mysqli_stmt_bind_param($stmt, 'ii', $start, $max);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $nimetus, $kogus);
    $rows = array();
    while (mysqli_stmt_fetch($stmt)) {
        $rows[] = array(
            'id' => $id,
            'nimetus' => $nimetus,
            'kogus' => $kogus,
        );
    }
    mysqli_stmt_close($stmt);
    return $rows;
}

function model_add($nimetus, $kogus)
{
    global $l;
    $query = 'INSERT INTO knurmber__kaubad (Nimetus, Kogus) VALUES (?, ?)';
    $stmt = mysqli_prepare($l, $query);
    if (mysqli_error($l)) {
        echo mysqli_error($l);
        exit;
    }
    mysqli_stmt_bind_param($stmt, 'si', $nimetus, $kogus);
    mysqli_stmt_execute($stmt);
    $id = mysqli_stmt_insert_id($stmt);
    mysqli_stmt_close($stmt);
    return $id;
}

function model_delete($id)
{
    global $l;
    $query = 'DELETE FROM knurmber__kaubad WHERE Id=? LIMIT 1';
    $stmt = mysqli_prepare($l, $query);
    if (mysqli_error($l)) {
        echo mysqli_error($l);
        exit;
    }
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $deleted = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    return $deleted;
}

function model_update($id, $kogus)
{
    global $l;
    $query = 'UPDATE knurmber__kaubad SET Kogus=? WHERE Id=? LIMIT 1';
    $stmt = mysqli_prepare($l, $query);
    if (mysqli_error($l)) {
        echo mysqli_error($l);
        exit;
    }
    mysqli_stmt_bind_param($stmt, 'ii', $kogus, $id);
    mysqli_stmt_execute($stmt);
    if (mysqli_stmt_error($stmt)) {
        return false;
    }
    mysqli_stmt_close($stmt);
    return true;
}

function model_user_add($kasutajanimi, $parool)
{
    global $l;
    $hash = password_hash($parool, PASSWORD_DEFAULT);
    $query = 'INSERT INTO knurmber__kasutajad (Kasutajanimi, Parool) VALUES (?, ?)';
    $stmt = mysqli_prepare($l, $query);
    if (mysqli_error($l)) {
        echo mysqli_error($l);
        exit;
    }
    mysqli_stmt_bind_param($stmt, 'ss', $kasutajanimi, $hash);
    mysqli_stmt_execute($stmt);
    $id = mysqli_stmt_insert_id($stmt);
    mysqli_stmt_close($stmt);
    return $id;
}

function model_user_get($kasutajanimi, $parool)
{
    global $l;
    $query = 'SELECT Id, Parool FROM knurmber__kasutajad WHERE Kasutajanimi=? LIMIT 1';
    $stmt = mysqli_prepare($l, $query);
    if (mysqli_error($l)) {
        echo mysqli_error($l);
        exit;
    }
    mysqli_stmt_bind_param($stmt, 's', $kasutajanimi);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $hash);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
	
    if (password_verify($parool, $hash)) {
        return $id;
    }
    return false;
}