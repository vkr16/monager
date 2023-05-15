<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Details</title>
    <!-- Link -->
    <?php $this->load->view('partial_components/linkPart'); ?>
</head>

<body>
    <div class="d-flex font-nunito-sans bg-light">
        <!-- Sidebar -->
        <?php $this->load->view('partial_components/sidebarPart'); ?>
        <section class="wh-screen scrollable-y" id="middle-section">
            <!-- Topbar -->
            <?php $this->load->view('partial_components/topbarPart'); ?>
            <!-- Main Content -->
            <div class="mx-2 mx-lg-5 my-4 px-3 py-2 table-responsive">
                <h2 class="fw-semibold">Loan Note</h2>
                <hr class="mt-05" style="max-width: 200px;border: 2px solid; opacity: 1 ">
                <div class="d-flex flex-wrap mb-5">
                    <button class="btn btn-sm btn-dark rounded-0 mb-3" onclick="backToLoanList()">
                        <i class="fa-solid fa-arrow-left fa-fw"></i>&nbsp; Back
                    </button>&emsp;
                    <?= $loan_detail->payment_status == 2 ? '' : '<button class="btn btn-sm btn-danger rounded-0 mb-3" onclick="openAddPaymentModal(\'' . $loan_detail->id . '\')">
                        <i class="fa-regular fa-bookmark fa-fw"></i>&nbsp; New Payment Record
                    </button>&emsp;' ?>
                    <button class="btn btn-sm btn-outline-danger rounded-0 mb-3" onclick="deleteLoanNote('<?= $loan_detail->id ?>')">
                        <i class="fa-solid fa-radiation fa-fw"></i>&nbsp; Delete
                    </button>
                </div>

                <table class="table table-sm fw-bold">
                    <tr>
                        <td class="align-middle">Borrower</td>
                        <td class="align-middle">: <?= $loan_detail->borrower ?></td>
                    </tr>
                    <tr>
                        <td class="align-middle">Loan</td>
                        <td class="align-middle">: Rp <?= number_format($loan_detail->amount, 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td class="align-middle">Description</td>
                        <td class="align-middle">: <?= $loan_detail->description ?></td>
                    </tr>
                    <tr>
                        <td class="align-middle">Status</td>
                        <td class="align-middle">:
                            <?php switch ($loan_detail->payment_status) {
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
                    </tr>
                    <tr>
                        <td class="align-middle">Paid</td>
                        <td class="align-middle">: Rp <?= number_format($loan_detail->paid, 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td class="align-middle">Unpaid</td>
                        <td class="align-middle">: Rp <?= number_format($loan_detail->unpaid, 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td class="align-middle">Due date</td>
                        <td class="align-middle">
                            : <?= date('j F Y', $loan_detail->due_date) ?>
                            (<?php
                                $days = ceil(($loan_detail->due_date - time()) / (60 * 60 * 24));
                                echo abs($days);
                                echo abs($days) > 1 ? ' days' : ' day';
                                echo $days < 0 ? ' ago' : ' left';
                                ?>)
                        </td>
                    </tr>
                </table>
                <br>
                <table class="table table-sm table-hover" id="table_record">
                    <thead>
                        <tr>
                            <th class="align-middle">Timestamp</th>
                            <th class="text-end align-middle">Amount</th>
                            <th class="text-end align-middle d-none d-md-table-cell">Channel</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($loan_payments as $key => $loan_payment) {
                        ?>
                            <tr>
                                <td class="align-middle small"><?= date('d/m/y H:i A', $loan_payment->created_at) ?></td>
                                <td class="text-end align-middle small <?= $loan_payment->amount ?>">
                                    <?= 'Rp ' . number_format($loan_payment->amount, 0, ',', '.') ?>
                                </td>
                                <td class="small text-end d-none d-md-table-cell"><?= $loan_payment->channel == 0 ? 'Cashless' : 'Cash' ?></td>
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
    <div class="modal fade" id="modalAddPaymentRecord" tabindex="-1" aria-labelledby="modalAddPaymentRecordLabel" aria-hidden="true">
        <div class="modal-dialog" id="modalDialogAddPaymentRecord">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalAddPaymentRecordLabel">Add Payment Record</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="inputAmount">Amount</label>
                        <input type="number" class="form-control" id="inputAmount" placeholder="ex: 50000">
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
        $('#sidebar-loan').removeClass('link-dark').addClass('sidebar-active')

        $(document).ready(() => {
            // Datatables
            $('#table_record').DataTable({
                "ordering": false
            });
        });


        function openAddPaymentModal(loanId) {
            $('#modalAddPaymentRecord').modal('show');
            $('#submitNewPaymentRecordButton').attr('onclick', "submitNewPaymentRecord('" + loanId + "')");
        }

        function submitNewPaymentRecord(loanId) {
            const amount = $('#inputAmount').val();
            const channel = $('#inputChannel').val();
            Notiflix.Block.dots('#modalDialogAddPaymentRecord');

            $.post("<?= base_url('loan/payment/add') ?>", {
                    id: loanId,
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
                        case 'ERR_PAYMENT_MORE_THAN_LOAN':
                            Notiflix.Report.failure(
                                'Failed',
                                'Loan payments should not exceed the amount owed',
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

        function showDescription(desc) {
            Notiflix.Notify.info(desc);
        }

        function backToLoanList() {
            Notiflix.Loading.pulse();
            window.location.href = "<?= base_url('loan') ?>";
        }

        function deleteLoanNote(loanId) {
            const randomConfirmation = generateRandomString(4);
            Notiflix.Confirm.prompt(
                'Confirm Delete Loan Note',
                'Please write down "' + randomConfirmation + '" to confirm deletion',
                '',
                'Send',
                'Cancel',
                (clientAnswer) => {
                    if (clientAnswer === randomConfirmation) {
                        //    continue delete
                        $.post('<?= base_url('loan/note/delete') ?>', {
                                id: loanId
                            })
                            .done((data) => {
                                switch (data) {
                                    case 'SUCCESS_LOAN_NOTE_DELETED':
                                        Notiflix.Report.success(
                                            'Success Alert',
                                            'Loan note deleted successfully',
                                            'Okay',
                                            () => {
                                                Notiflix.Loading.pulse();
                                                setTimeout(() => {
                                                    window.location.href = '<?= base_url('loan') ?>';
                                                }, 1000);
                                            }
                                        );
                                        break;
                                    case 'ERR_LOAN_NOTE_NOT_DELETED':
                                        Notiflix.Report.failure(
                                            'Failed',
                                            'Failed to delete debt note',
                                            'Okay',
                                            () => {
                                                Notiflix.Loading.pulse();
                                                setTimeout(() => {
                                                    window.location.reload();
                                                }, 1000);
                                            }
                                        );
                                        break;
                                    case 'ERR_UNAUTHORIZED_ACTION':
                                        Notiflix.Report.failure(
                                            'Failed',
                                            'You have no authorization to do this action',
                                            'Okay',
                                            () => {
                                                Notiflix.Loading.pulse();
                                                setTimeout(() => {
                                                    window.location.href = '<?= base_url('logout') ?>';
                                                }, 1000);
                                            }
                                        );
                                        break;

                                    default:
                                        break;
                                }
                            })
                    } else {
                        Notiflix.Notify.failure('Confirmation code mismatch');
                    }
                },
            );
        }

        function generateRandomString(length) {
            let result = '';
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            const charactersLength = characters.length;

            for (let i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }

            return result;
        }
    </script>
</body>

</html>