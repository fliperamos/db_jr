<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/icon_data.png"/>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/icons.css">
    <title>DB-Junior</title>
</head>

<body>
<header class="main_header gradient gradient-red">
    <div class="container">
        <div class="main_header_logo">
            <h1 class="icon-database"><b>DB</b> - Junior</h1>
        </div>
    </div>
</header>

<main class="main_content">
    <form class="auth_form" action="" method="post" enctype="multipart/form-data">
        <label>
            <div><span class="icon-envelope">Email:</span></div>
            <input type="email" name="email" placeholder="Informe seu e-mail:"/>
        </label>
        <label>
            <div class="unlock-alt">
                <span class="icon-unlock-alt">Senha:</span>

            </div>
            <input type="password" name="password" placeholder="Informe sua senha:"/>
        </label>
        <label class="check">
            <input type="checkbox" name="save"/>
            <span>Lembrar dados?</span>
        </label>
        <button class="auth_form_btn transition gradient gradient-red gradient-hover">Entrar</button>
    </form>
</main>

<footer class="footer">
    <p>Made by Felipe Ramos©</p>
</footer>
</body>

</html>