<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debt</title>
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
            <!-- Main Content -->
            <div class="mx-2 mx-lg-5 my-4 px-3 py-2">
                <h2 class="fw-semibold">Debt List</h2>
                <hr class="mt-05" style="max-width: 200px;border: 2px solid; opacity: 1 ">
                <div class="d-flex mb-5">
                    <button class="btn btn-danger btn-sm rounded-0" data-bs-toggle="modal" data-bs-target="#modalAddNewDebtNote">
                        <i class="fa-regular fa-bookmark fa-fw"></i>&nbsp; New Debt Note
                    </button>
                </div>
                <table class="table table-sm table-hover" id="table_debt">
                    <thead>
                        <tr>
                            <th class="align-middle">Amount</th>
                            <th class="text-end align-middle">Lender</th>
                            <th class="text-end align-middle">Description</th>
                            <th class="text-end align-middle">Payment Status</th>
                            <th class="text-end align-middle">Due Date</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
    <!-- Script -->
    <?php $this->load->view('partial_components/scriptPart'); ?>
</body>

</html>