<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget</title>
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
            <div class="mx-2 mx-lg-5 my-4 px-3 py-2">
                <h2 class="fw-semibold">Budget List</h2>
                <hr class="mt-05" style="max-width: 200px;border: 2px solid; opacity: 1 ">
                <span class="bg-white text-dark border border-1 border-dark rounded-0 px-2 py-1">Total Budget : Rp <?= number_format($totalBudgetAllocated == null ? '0' : $totalBudgetAllocated, 0, ',', '.') ?></span><br><br>
                <div class="d-flex mb-5">
                    <button class="btn btn-danger btn-sm rounded-0" data-bs-toggle="modal" data-bs-target="#modalAddBudgetCategory">
                        <i class="fa-regular fa-bookmark fa-fw"></i>&nbsp; New Category
                    </button>
                </div>
                <table class="table table-sm table-hover" id="table_budget">
                    <thead>
                        <tr>
                            <th class="align-middle">Category</th>
                            <th class="text-end align-middle">Budget</th>
                            <th class="text-end align-middle"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($categories as $key => $budget) {
                        ?>
                            <tr>
                                <td class="align-middle" role="button" onclick="budgetCategoryDetail('<?= $budget->id ?>')"><?= $budget->category ?></td>
                                <td class="text-end align-middle" role="button" onclick="budgetCategoryDetail('<?= $budget->id ?>')">Rp <?= number_format($budget->budget, 0, ',', '.') ?></td>
                                <td class="text-end align-middle"><button class="btn btn-danger rounded-0" onclick="openAddRecordModal('<?= $budget->id ?>')"><i class="fa-regular fa-plus-square"></i></button></td>
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
    <div class="modal fade" id="modalAddBudgetCategory" tabindex="-1" aria-labelledby="modalAddBudgetCategoryLabel" aria-hidden="true">
        <div class="modal-dialog" id="modalDialogAddBudgetCategory">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalAddBudgetCategoryLabel">Add Category</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="inputCategory">Category</label>
                        <input type="text" class="form-control" id="inputCategory" placeholder="Category">
                    </div>
                    <div class="mb-3">
                        <label for="inputBudget">Budget</label>
                        <input type="number" class="form-control" id="inputBudget" placeholder="Budget">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark rounded-0" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger rounded-0" onclick="submitNewBudgetCategory()">Save changes</button>
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
        $('#sidebar-budget').removeClass('link-dark').addClass('sidebar-active')
        $(document).ready(() => {
            // Datatables
            $('#table_budget').DataTable({
                "ordering": false
            });
        });

        function submitNewBudgetCategory() {
            Notiflix.Block.dots('#modalDialogAddBudgetCategory');
            const category = $('#inputCategory').val();
            const budget = $('#inputBudget').val();

            $.post('<?= base_url('budget/category/add') ?>', {
                    category: category,
                    budget: budget
                })
                .done((data) => {
                    Notiflix.Block.remove('#modalDialogAddBudgetCategory');

                    switch (data) {
                        case 'SUCCESS_BUDGET_CATEGORY_ADDED':
                            Notiflix.Report.success(
                                'Success Alert',
                                'Budget for ' + category + ' has been added successfully with initial value Rp' + budget.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."),
                                'Okay',
                                () => {
                                    Notiflix.Loading.pulse();
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 1500);
                                }
                            );
                            break;
                        case 'ERR_BUDGET_CATEGORY_NOT_ADDED':
                            Notiflix.Notify.failure('Failed to add category');
                            setTimeout(() => {
                                window.location.reload();
                            }, 1200);
                            break;
                        default:
                            Notiflix.Notify.failure(data);
                            break;
                    }
                })
        }

        function budgetCategoryDetail(id) {
            Notiflix.Loading.pulse();
            window.location.href = '<?= base_url('budget/record/detail/') ?>' + encodeURIComponent(btoa(btoa(btoa(id))));
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
                                    }, 500);
                                }
                            );
                            break;
                        case 'ERR_RECORD_NOT_INSERTED':
                            Notiflix.Notify.failure('Failed to add new record');
                            setTimeout(() => {
                                window.location.reload();
                            }, 500);
                            break;
                        case 'ERR_NOT_ENOUGH_CREDIT':
                            Notiflix.Notify.failure('Failed, you are out of budget');
                            break;
                        default:
                            Notiflix.Notify.failure(data);
                            break;
                    }
                })
        }
    </script>
</body>

</html>