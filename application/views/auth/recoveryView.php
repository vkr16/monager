<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <?php $this->load->view('partial_components/linkPart') ?>
</head>

<body>

    <div class="wh-screen d-flex justify-content-center align-items-center bg-white font-nunito-sans">
        <div class="col-10" style="max-width: 480px">
            <p class="display-5 fw-semibold font-poppins">Reset Password</p>
            <p class="h5 fw-normal">Request password reset link</p>
            <hr style="max-width: 320px" class="mt-2 mb-4">
            <div class="mb-3">
                <label for="inputEmail" class="form-label">Email</label>
                <input required autocomplete="email" type="email" class="form-control rounded-0" id="inputEmail">
            </div>
            <button class="btn btn-danger rounded-0" onclick="requestRecovery()">Request recovery</button>

            <p class="mt-3">Or <a href="<?= base_url('login') ?>" class="text-danger">Log in instead</a></p>
            <p class="small text-muted text-center mt-5">&copy; 2022 - <?= date('Y') ?> Fikri Miftah Akmaludin</p>
        </div>
    </div>


    <?php $this->load->view('partial_components/scriptPart') ?>
    <script>
        function requestRecovery() {
            const email = $('#inputEmail').val();
            Notiflix.Loading.standard();

            $.post('<?= base_url('recovery/process') ?>', {
                    email: email
                })
                .done((data) => {
                    Notiflix.Loading.remove();
                    console.log(data);
                })
        }
    </script>
</body>

</html>