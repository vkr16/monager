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
                            <th class="align-middle">Status</th>
                            <th class="align-middle d-none d-md-table-cell">Lender / Creditor</th>
                            <th class="text-end align-middle">Amount</th>
                            <th class="text-end align-middle">Due Date</th>
                            <th></th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($debts as $key => $debt) {
                        ?>
                            <tr>
                                <td class="align-middle"><?php switch ($debt->payment_status) {
                                                                case 0:
                                                                    echo '<span class="badge rounded-pill text-bg-danger">Unpaid</span>';
                                                                    break;
                                                                case 1:
                                                                    echo '<span class="badge rounded-pill text-bg-primary">Outstanding</span>';
                                                                    break;
                                                                case 2:
                                                                    echo '<span class="badge rounded-pill text-bg-success">Paid</span>';
                                                                    break;
                                                                default:
                                                                    echo '<span class="badge rounded-pill text-bg-dark">Unknown</span>';
                                                                    break;
                                                            } ?></td>
                                <td class="align-middle d-none d-md-table-cell"><?= $debt->lender ?></td>
                                <td class="align-middle text-end">Rp <?= number_format($debt->amount, 0, ',', '.') ?></td>
                                <td class="align-middle text-end"><?= date('d/m/y', $debt->due_date) ?></td>
                                <td class="align-middle text-end"><button class="btn btn-danger rounded-0"><i class="fa-regular fa-plus-square"></i></button></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <!-- Modals -->
    <div class="modal fade" id="modalAddNewDebtNote" tabindex="-1" aria-labelledby="modalAddNewDebtNoteLabel" aria-hidden="true">
        <div class="modal-dialog" id="modalDialogAddNewDebtNote">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalAddNewDebtNoteLabel">Add Debt Note</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="inputLender">Lender / Creditor</label>
                        <input type="text" class="form-control" id="inputLender" placeholder="ex: KSP Maju Mundur">
                    </div>
                    <div class="mb-3">
                        <label for="inputAmount">Amount</label>
                        <input type="number" class="form-control" id="inputAmount" placeholder="ex: 500000 ">
                    </div>
                    <div class="mb-3">
                        <label for="inputDescription">Description</label>
                        <input type="text" class="form-control" id="inputDescription" placeholder="ex: Pinjaman koperasi Rp 500 ribu">
                    </div>
                    <div class="mb-3">
                        <label for="inputDueDate">Due Date</label>
                        <input type="date" class="form-control" id="inputDueDate" placeholder="Due Date" value="<?= date('Y-m-d', strtotime('tomorrow')) ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark rounded-0" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger rounded-0" onclick="submitNewDebtNote()">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script -->
    <?php $this->load->view('partial_components/scriptPart'); ?>

    <script>
        $('#sidebar-debt').removeClass('link-dark').addClass('sidebar-active')

        $(document).ready(() => {
            // Datatables
            $('#table_debt').DataTable({
                "ordering": false
            });
        });

        function submitNewDebtNote() {
            Notiflix.Block.pulse('#modalDialogAddNewDebtNote');
            const lender = $('#inputLender').val();
            const amount = $('#inputAmount').val();
            const description = $('#inputDescription').val();
            const due_date = $('#inputDueDate').val();
            $.post('<?= base_url('debt/note/add') ?>', {
                    lender: lender,
                    amount: amount,
                    description: description,
                    due_date: convertToTimestamp(due_date)
                })
                .done((data) => {
                    Notiflix.Block.remove('#modalDialogAddNewDebtNote', 250);

                    switch (data) {
                        case 'SUCCESS_DEBT_NOTE_INSERTED':
                            Notiflix.Report.success(
                                'Success',
                                'New debt Rp ' + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ' from ' + lender + ' has been noted successfully',
                                'Ok',
                                () => {
                                    Notiflix.Loading.pulse();
                                    window.location.reload();
                                },
                            );
                            break;
                        case 'ERR_DEBT_NOTE_NOT_INSERTED':
                            Notiflix.Report.failure(
                                'Error',
                                'Faield to insert data into database, please try again. If the error persist please contact the administrator',
                                'Ok',
                                () => {
                                    Notiflix.Loading.pulse();
                                    window.location.reload();
                                },
                            );
                            break;
                        default:
                            Notiflix.Notify.failure(data);
                            break;
                    }
                })
        }

        function convertToTimestamp(dateInput) {
            var dateObject = new Date(dateInput);
            var unixTimestamp = dateObject.getTime() / 1000;
            return unixTimestamp;
        }
    </script>
</body>

</html>