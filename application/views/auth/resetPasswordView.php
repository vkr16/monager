<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="shortcut icon" href="<?= base_url('assets/img/logo.png') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= base_url('assets/library/bootstrap-5.2.1/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/library/fontawesome-6.2.0/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">
</head>

<body>

    <div class="wh-screen d-flex justify-content-center align-items-center bg-white font-nunito-sans">
        <div class="col-10" style="max-width: 480px">
            <p class="display-5 fw-semibold font-poppins">Reset Password</p>
            <p class="h5 fw-normal">Create new password for your account</p>
            <hr style="max-width: 320px" class="mt-2 mb-4">
            <div class="mb-3">
                <label for="inputPassword2" class="form-label">Password</label>
                <input required autocomplete="new-password" type="password" class="form-control rounded-0" id="inputPassword2">
            </div>
            <div class="mb-3">
                <label for="inputPassword" class="form-label">Repeat Password</label>
                <input required autocomplete="new-password" type="password" class="form-control rounded-0" id="inputPassword">
            </div>
            <div class="d-flex justify-content-between">
                <div class="mb-3 d-flex align-items-center me-auto">
                    <input type="checkbox" class="form-check-input rounded-0 mt-0" id="checkShowpassword" onchange="passwordVisible()">
                    <label for="checkShowpassword" class="form-label mb-0 ms-2">Show password</label>
                </div>
            </div>
            <button class="btn btn-danger rounded-0" onclick="isMatch()">Create account</button>

            <p class="mt-3">Or <a href="<?= base_url('login') ?>" class="text-danger">Log in instead</a></p>
            <p class="small text-muted text-center mt-5">&copy; <?= '2022 - ' . date('Y') ?> Fikri Miftah Akmaludin</p>
        </div>
    </div>

    <?php $this->load->view('partial_components/scriptPart'); ?>

    <script>
        function passwordVisible() {
            if (document.getElementById('inputPassword').type == "password") {
                document.getElementById('inputPassword').type = "text"
                document.getElementById('inputPassword2').type = "text"
            } else {
                document.getElementById('inputPassword').type = "password"
                document.getElementById('inputPassword2').type = "password"
            }
        }

        function isMatch() {
            if ($('#inputPassword').val() !== $('#inputPassword2').val()) {
                Notiflix.Notify.failure('Password did not match');
            } else {
                setPassword();
            }
        }

        function setPassword() {
            $.post('<?= base_url('recovery/verified/process') ?>', {
                    email: '<?= $email ?>',
                    password: $("#inputPassword").val()
                })
                .done((data) => {
                    console.log(data);
                    switch (data) {
                        case 'ERR_TRANS_ROLLBACK':
                            Notiflix.Report.failure('Error', 'Failed to reset password, please try again.', 'Ok', () => {
                                window.location.reload();
                            })
                            break;
                        case 'SUCCESS_PASSWORD_RESET':
                            Notiflix.Report.success('Success', 'Your password has been reset, please log in using your new password.', 'Ok', () => {
                                window.location.href = '<?= base_url('login') ?>';
                            })
                            break;
                        default:
                            window.location.href = '<?= base_url('login') ?>';
                            break;
                    }
                })
        }
    </script>
</body>

</html>