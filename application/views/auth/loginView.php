<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="shortcut icon" href="<?= base_url('assets/img/logo.png') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= base_url('assets/library/bootstrap-5.2.1/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/library/fontawesome-6.2.0/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">
</head>

<body>

    <div class="wh-screen d-flex justify-content-center align-items-center bg-white font-nunito-sans">
        <div class="col-10" style="max-width: 480px">
            <p class="display-5 fw-semibold font-poppins">Login</p>
            <p class="h5 fw-normal">Log in to your account</p>
            <hr style="max-width: 320px" class="mt-2 mb-4">
            <div class="mb-3">
                <label for="inputEmail" class="form-label">Email</label>
                <input required autocomplete="email" type="email" class="form-control rounded-0" id="inputEmail">
            </div>
            <div class="mb-3">
                <label for="inputPassword" class="form-label">Password</label>
                <input required autocomplete="current-password" type="password" class="form-control rounded-0" id="inputPassword">
            </div>
            <div class="d-flex justify-content-between">
                <div class="mb-3 d-flex align-items-center me-auto">
                    <input type="checkbox" class="form-check-input rounded-0 mt-0" id="checkShowpassword" onchange="passwordVisible()">
                    <label for="checkShowpassword" class="form-label mb-0 ms-2">Show password</label>
                </div>
                <a href="reset-password.php" class="text-danger ms-auto">Forgot password?</a>
            </div>
            <button class="btn btn-danger rounded-0" onclick="submitLogin()"><i class="fa-solid fa-right-to-bracket"></i> Login</button>

            <p class="mt-3">Or <a href="<?= base_url('register') ?>" class="text-danger">Create an account</a></p>
            <p class="small text-muted text-center mt-5">&copy; <?= '2022 -' . date('Y') ?> Fikri Miftah Akmaludin</p>
        </div>
    </div>


    <?php $this->load->view('partial_components/scriptPart') ?>
    <script>
        function passwordVisible() {
            if (document.getElementById('inputPassword').type == "password") {
                document.getElementById('inputPassword').type = "text"
            } else {
                document.getElementById('inputPassword').type = "password"
            }
        }

        function submitLogin() {
            const email = $('#inputEmail').val();
            const password = $('#inputPassword').val();

            $.post("<?= base_url('login/process') ?>", {
                    email: email,
                    password: password
                })
                .done(function(data) {
                    switch (data) {
                        case 'ERR_PASSWORD_INVALID':
                            Notiflix.Notify.failure('Password is invalid');
                            break;
                        case 'SUCCESS_PASSWORD_VALID':
                            Notiflix.Notify.success('Login valid');
                            Notiflix.Loading.pulse('Redirecting...');
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                            break;
                        case 'ERR_EMAIL_NOT_REGISTERED':
                            Notiflix.Notify.failure('Email address not registered');
                            break;
                        default:
                            Notiflix.Notify.failure(data);
                            break;
                    }
                });
        }
    </script>
</body>

</html>