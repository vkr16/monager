<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record</title>
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
                <h2 class="fw-semibold">Budget Records</h2>
                <hr class="mt-05" style="max-width: 200px;border: 2px solid; opacity: 1 ">
                <div class="d-flex mb-5">
                    <button class="btn btn-sm btn-dark rounded-0" onclick="backToBudgetList()">
                        <i class="fa-solid fa-arrow-left fa-fw"></i>&nbsp; Back
                    </button>&emsp;
                    <button class="btn btn-sm btn-danger rounded-0" onclick="openAddRecordModal('<?= $budgetId ?>')">
                        <i class="fa-regular fa-bookmark fa-fw"></i>&nbsp; New Record
                    </button>&emsp;
                    <button class="btn btn-sm btn-outline-danger rounded-0" onclick="deleteBudgetCategory('<?= $budgetId ?>')">
                        <i class="fa-solid fa-radiation fa-fw"></i>&nbsp; Delete
                    </button>
                </div>

                <table class="table table-sm fw-bold">
                    <tr>
                        <td>Category&nbsp; <i role="button" class="fa-regular fa-pen-to-square" onclick="openUpdateCategoryNameModal('<?= $budgetId ?>')"></i></td>
                        <td>: <?= $budgetDetail->category ?></td>
                    </tr>
                    <tr>
                        <td>Budget</td>
                        <td>: Rp <?= number_format($budgetDetail->budget, 0, ',', '.') ?></td>
                    </tr>
                </table>

                <table class="table table-sm table-hover" id="table_record">
                    <thead>
                        <tr>
                            <th class="align-middle">Timestamp</th>
                            <th class="text-end align-middle">Amount</th>
                            <th class="text-end align-middle d-none d-md-table-cell">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($records as $key => $record) {
                        ?>
                            <tr onclick="showDescription('<?= $record->description ?>')">
                                <td class="align-middle small"><?= date('d/m/y H:i A', $record->created_at) ?></td>
                                <td class="text-end align-middle small <?= $record->type == 0 ? 'text-danger' : 'text-success' ?>">
                                    <?= 'Rp ' . number_format($record->amount, 0, ',', '.') ?>
                                </td>
                                <td class="small text-end d-none  d-md-table-cell"><?= $record->description ?></td>
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
    <div class="modal fade" id="modalUpdateCategoryName" tabindex="-1" aria-labelledby="modalUpdateCategoryNameLabel" aria-hidden="true">
        <div class="modal-dialog" id="modalDialogUpdateCategoryName">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalUpdateCategoryNameLabel">Update Category</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="updateCategory">Category</label>
                        <input type="text" class="form-control" id="updateCategory" placeholder="Category">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark rounded-0" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger rounded-0" onclick="submitUpdateBudgetCategory()">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAddBudgetRecord" tabindex="-1" aria-labelledby="modalAddBudgetRecordLabel" aria-hidden="true">
        <div class="modal-dialog" id="modalDialogAddBudgetRecord">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalAddBudgetRecordLabel">Add Record</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="inputAmount">Amount</label>
                        <input type="number" class="form-control" id="inputAmount" placeholder="Amount">
                    </div>
                    <div class="mb-3">
                        <label for="inputType">Record Type</label>
                        <select id="inputType" class="form-select">
                            <option value="0">Out</option>
                            <option value="1">In</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="inputDescription">Description</label>
                        <input type="text" class="form-control" id="inputDescription" placeholder="Description">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark rounded-0" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger rounded-0" id="submitNewRecordButton">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Script -->
    <?php $this->load->view('partial_components/scriptPart'); ?>

    <script>
        $(document).ready(() => {
            // Datatables
            $('#table_record').DataTable({
                "ordering": false
            });
        });

        function submitUpdateBudgetCategory() {
            Notiflix.Block.dots('#modalDialogUpdateCategoryName');
            const category = $('#updateCategory').val();

            $.post('<?= base_url('budget/category/update') ?>', {
                    id: '<?= $budgetId ?>',
                    category: category
                })
                .done((data) => {
                    Notiflix.Block.remove('#modalDialogUpdateCategoryName');
                    switch (data) {
                        case 'ERR_CATEGORY_NOT_UPDATED':
                            Notiflix.Notify.failure('Failed to update category');
                            break;
                        case 'SUCCESS_CATEGORY_UPDATED':
                            Notiflix.Report.success(
                                'Success Alert',
                                'Category has been updated successfully',
                                'Okay',
                                () => {
                                    Notiflix.Loading.pulse();
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 1000);
                                }
                            );
                            break;
                        default:
                            break;
                    }
                })
        }

        function openAddRecordModal(budgetId) {
            $('#modalAddBudgetRecord').modal('show');
            $('#submitNewRecordButton').attr('onclick', "submitNewBudgetRecord('" + budgetId + "')");
        }

        function submitNewBudgetRecord(budgetId) {
            const amount = $('#inputAmount').val();
            const type = $('#inputType').val();
            const description = $('#inputDescription').val();
            Notiflix.Block.dots('#modalDialogAddBudgetRecord');

            $.post("<?= base_url('budget/record/add') ?>", {
                    id: budgetId,
                    amount: amount,
                    type: type,
                    description: description
                })
                .done((data) => {
                    Notiflix.Block.remove('#modalDialogAddBudgetRecord');

                    switch (data) {
                        case 'SUCCESS_RECORD_INSERTED':
                            Notiflix.Report.success(
                                'Success Alert',
                                'A new entry has been recorded of Rp' + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ' for "' + description + '"',
                                'Okay',
                                () => {
                                    Notiflix.Loading.pulse();
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 1000);
                                }
                            );
                            break;
                        case 'ERR_RECORD_NOT_INSERTED':
                            Notiflix.Notify.failure('Failed to add new record');
                            setTimeout(() => {
                                window.location.reload();
                            }, 1200);
                            break;
                        case 'ERR_NOT_ENOUGH_CREDIT':
                            Notiflix.Report.failure(
                                'Failed',
                                'You are out of budget for this transaction',
                                'Okay'
                            );
                            break;
                        default:
                            Notiflix.Notify.failure(data);
                            break;
                    }
                })
        }

        function showDescription(desc) {
            Notiflix.Notify.info(desc);
        }

        function backToBudgetList() {
            Notiflix.Loading.pulse();
            window.location.href = "<?= base_url('budget') ?>";
        }

        function openUpdateCategoryNameModal(budgetId) {
            $('#updateCategory').val('<?= $budgetDetail->category ?>')
            $('#modalUpdateCategoryName').modal('show');
        }

        function deleteBudgetCategory(budgetId) {
            const randomConfirmation = generateRandomString(4);
            Notiflix.Confirm.prompt(
                'Confirm Delete Budget Category',
                'Please write down "' + randomConfirmation + '" to confirm deletion',
                '',
                'Send',
                'Cancel',
                (clientAnswer) => {
                    if (clientAnswer === randomConfirmation) {
                        //    continue delete
                        $.post('<?= base_url('budget/category/delete') ?>', {
                                id: budgetId
                            })
                            .done((data) => {
                                switch (data) {
                                    case 'SUCCESS_CATEGORY_DELETED':
                                        Notiflix.Report.success(
                                            'Success Alert',
                                            'Budget category deleted successfully',
                                            'Okay',
                                            () => {
                                                Notiflix.Loading.pulse();
                                                setTimeout(() => {
                                                    window.location.href = '<?= base_url('budget') ?>';
                                                }, 1000);
                                            }
                                        );
                                        break;
                                    case 'ERR_CATEGORY_NOT_DELETED':
                                        Notiflix.Report.failure(
                                            'Failed',
                                            'Failed to delete budget category',
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