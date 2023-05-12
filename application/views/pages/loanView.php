<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan</title>
    <!-- Link -->
    <?php $this->load->view('partial_components/linkPart'); ?>
</head>

<body>
    <div class="d-flex font-nunito-sans bg-light">
        <!-- Sidebar -->
        <?php $this->load->view('partial_components/sidebarPart'); ?>
        <section class="vh-100 w-100 scrollable-y" id="middle-section">
            <!-- Topbar -->
            <?php $this->load->view('partial_components/topbarPart'); ?>
        </section>
    </div>
    <!-- Script -->
    <?php $this->load->view('partial_components/scriptPart'); ?>
</body>

</html>