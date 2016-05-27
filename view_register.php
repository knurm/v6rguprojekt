<!doctype html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="style.css">
        <meta charset="utf8" />
        <title>Registreeri konto</title>
    </head>
    <body>

        <?php
foreach (message_list() as $message):
?>
           <p class="message_list">
                <?= $message; ?>
           </p>
        <?php
endforeach;
?>
   
        <h1>Registreeri konto</h1>

        <form method="post" action="<?= $_SERVER['PHP_SELF']; ?>">

            <input type="hidden" name="action" value="register">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

            <table class="center">
                <tr>
                    <td>Kasutajanimi</td>
                    <td>
                        <input type="text" name="kasutajanimi" required>
                    </td>
                </tr>
                <tr>
                    <td>Parool</td>
                    <td>
                        <input type="password" name="parool" required>
                    </td>
                </tr>
            </table>

            <p>
                <button type="submit">Registreeri konto</button>
            </p>

        </form>
    </body>
</html>