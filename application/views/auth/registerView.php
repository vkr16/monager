<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="shortcut icon" href="<?= base_url('assets/img/logo.png') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= base_url('assets/library/bootstrap-5.2.1/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/library/fontawesome-6.2.0/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">
</head>

<body>

    <div class="wh-screen d-flex justify-content-center align-items-center bg-white font-nunito-sans">
        <div class="col-10" style="max-width: 480px">
            <p class="display-5 fw-semibold font-poppins">Register</p>
            <p class="h5 fw-normal">Register for an account</p>
            <hr style="max-width: 320px" class="mt-2 mb-4">
            <div class="mb-3">
                <label for="inputName" class="form-label">Name</label>
                <input required type="text" class="form-control rounded-0" id="inputName">
            </div>
            <div class="mb-3">
                <label for="inputEmail" class="form-label">Email</label>
                <input required autocomplete="email" type="email" class="form-control rounded-0" id="inputEmail">
            </div>
            <div class="mb-3">
                <label for="inputPassword" class="form-label">Password</label>
                <input required autocomplete="new-password" type="password" class="form-control rounded-0" id="inputPassword">
            </div>
            <div class="d-flex justify-content-between">
                <div class="mb-3 d-flex align-items-center me-auto">
                    <input type="checkbox" class="form-check-input rounded-0 mt-0" id="checkShowpassword" onchange="passwordVisible()">
                    <label for="checkShowpassword" class="form-label mb-0 ms-2">Show password</label>
                </div>
            </div>
            <button class="btn btn-danger rounded-0" onclick="submitRegistration()">Create account</button>

            <p class="mt-3">Or <a href="<?= base_url('login') ?>" class="text-danger">Log in instead</a></p>
            <p class="small text-muted text-center mt-5">&copy; <?= '2022 -' . date('Y') ?> Fikri Miftah Akmaludin</p>
        </div>
    </div>

    <script src="<?= base_url('assets/library/bootstrap-5.2.1/js/bootstrap.min.js') ?>"></script>
    <script src="<?= base_url('assets/library/jquery-3.6.1.min.js') ?>"></script>
    <script src="<?= base_url('assets/library/notiflix-aio-3.2.5.min.js') ?>"></script>

    <script>
        function passwordVisible() {
            if (document.getElementById('inputPassword').type == "password") {
                document.getElementById('inputPassword').type = "text"
            } else {
                document.getElementById('inputPassword').type = "password"
            }
        }

        function submitRegistration() {
            const name = $('#inputName').val();
            const email = $('#inputEmail').val();
            const password = $('#inputPassword').val();

            $.post("<?= base_url('register/process') ?>", {
                    name: name,
                    email: email,
                    password: password
                })
                .done(function(data) {
                    switch (data) {
                        case 'ERR_EMAIL_ADDRESS_CONFLICT':
                            Notiflix.Notify.failure('Email address already exist in database');
                            break;
                        case 'SUCCESS_USER_INSERTED':
                            Notiflix.Notify.success('User registered successfully');
                            Notiflix.Loading.pulse();
                            setTimeout(() => {
                                window.location.href = '<?= base_url('login') ?>'
                            }, 1000);
                            break;
                        case 'ERR_USER_NOT_INSERTED':
                            Notiflix.Notify.failure('Failed to insert user to database');
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