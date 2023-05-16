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
                <span class="bg-white text-dark border border-1 border-dark rounded-0 px-2 py-1">Unpaid Debt : Rp <?= number_format($unpaidDebt == NULL ? '0' : $unpaidDebt, 0, ',', '.') ?></span><br><br>

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
                                <td class="align-middle" role="button" onclick="debtDetail('<?= $debt->id ?>')">
                                    <?php switch ($debt->payment_status) {
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
                                    } ?>
                                </td>
                                <td class="align-middle d-none d-md-table-cell" role="button" onclick="debtDetail('<?= $debt->id ?>')"><?= $debt->lender ?></td>
                                <td class="align-middle text-end" role="button" onclick="debtDetail('<?= $debt->id ?>')">Rp <?= number_format($debt->amount, 0, ',', '.') ?></td>
                                <td class="align-middle text-end" role="button" onclick="debtDetail('<?= $debt->id ?>')"><?= date('d/m/y', $debt->due_date) ?></td>
                                <td class="align-middle text-end"><button class="btn btn-danger rounded-0 <?= $debt->payment_status == 2 ? 'disabled' : '' ?>" onclick="openAddPaymentModal('<?= $debt->id ?>')"><i class="fa-regular fa-plus-square"></i></button></td>
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

    <div class="modal fade" id="modalAddPaymentRecord" tabindex="-1" aria-labelledby="modalAddPaymentRecordLabel" aria-hidden="true">
        <div class="modal-dialog" id="modalDialogAddPaymentRecord">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalAddPaymentRecordLabel">Add Payment Record</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="inputAmountPayment">Amount</label>
                        <input type="number" class="form-control" id="inputAmountPayment" placeholder="ex: 50000">
                    </div>
                    <div class="mb-3">
                        <label for="inputChannel">Transaction Channel</label>
                        <select id="inputChannel" class="form-select">
                            <option value="0">Cashless (Bank transfer, Gopay, Dana, SPay etc)</option>
                            <option value="1">Cash</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark rounded-0" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger rounded-0" id="submitNewPaymentRecordButton">Save changes</button>
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

        function debtDetail(debtId) {
            debtId = encodeURIComponent(btoa(btoa(btoa(debtId))));

            window.location.href = '<?= base_url('debt/note/detail/') ?>' + debtId;
        }

        function openAddPaymentModal(debtId) {
            $('#modalAddPaymentRecord').modal('show');
            $('#submitNewPaymentRecordButton').attr('onclick', "submitNewPaymentRecord('" + debtId + "')");
        }

        function submitNewPaymentRecord(debtId) {
            const amount = $('#inputAmountPayment').val();
            const channel = $('#inputChannel').val();
            Notiflix.Block.dots('#modalDialogAddPaymentRecord');

            $.post("<?= base_url('debt/payment/add') ?>", {
                    id: debtId,
                    amount: amount,
                    channel: channel
                })
                .done((data) => {
                    Notiflix.Block.remove('#modalDialogAddPaymentRecord');

                    switch (data) {
                        case 'SUCCESS_PAYMENT_RECORD_INSERTED':
                            Notiflix.Report.success(
                                'Success Alert',
                                'A new payment record has been added successfully',
                                'Okay',
                                () => {
                                    Notiflix.Loading.pulse();
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 1000);
                                }
                            );
                            break;
                        case 'ERR_PAYMENT_RECORD_NOT_INSERTED':
                            Notiflix.Notify.failure('Failed to add new payment record');
                            setTimeout(() => {
                                window.location.reload();
                            }, 1200);
                            break;
                        case 'ERR_PAYMENT_MORE_THAN_DEBT':
                            Notiflix.Report.failure(
                                'Failed',
                                'Debt payments should not exceed the amount owed',
                                'Okay'
                            );
                            break;
                        default:
                            Notiflix.Notify.failure(data);
                            // console.log(data);
                            break;
                    }
                })
        }
    </script>
</body>

</html>