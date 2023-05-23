<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Budget extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        isLoggedIn();
    }

    public function budgetCategoryAddProcess()
    {
        $data['category'] = $this->input->post('category');
        $data['budget'] = $this->input->post('budget');


        $this->form_validation->set_rules('category', 'category', 'required');
        $this->form_validation->set_rules('budget', 'budget', 'required|numeric');
        $this->form_validation->set_error_delimiters('', '<.0.>');

        if ($this->form_validation->run() == FALSE) {
            echo explode('<.0.>', validation_errors())[0];
        } else {
            $user_id = $this->UserModel->getUserIdBySession();
            $data['user_id'] = $user_id;
            $data['created_at'] = time();
            if ($this->BudgetModel->insertBudgetCategory($data)) {
                echo 'SUCCESS_BUDGET_CATEGORY_ADDED';
            } else {
                echo 'ERR_BUDGET_CATEGORY_NOT_ADDED';
            }
        }
    }

    public function budgetRecordAddProcess()
    {
        $data['budget_id'] = $this->input->post('id');
        $data['amount'] = $this->input->post('amount');
        $data['type'] = $this->input->post('type');
        $data['description'] = $this->input->post('description');

        $this->form_validation->set_rules('amount', 'amount', 'required');
        $this->form_validation->set_rules('description', 'description', 'required');
        $this->form_validation->set_error_delimiters('', '<.0.>');

        if ($this->form_validation->run() === FALSE) {
            echo explode('<.0.>', validation_errors())[0];
        } else {
            if ($this->BudgetModel->isAuthorOf($data['budget_id'])) {
                if ($this->RecordModel->insertNewRecord($data) === TRUE) {
                    echo 'SUCCESS_RECORD_INSERTED';
                } else if ($this->RecordModel->insertNewRecord($data) === FALSE) {
                    echo 'ERR_RECORD_NOT_INSERTED';
                } else if ($this->RecordModel->insertNewRecord($data) === 'ERR_NOT_ENOUGH_CREDIT') {
                    echo 'ERR_NOT_ENOUGH_CREDIT';
                }
            } else {
                echo 'ERR_UNAUTHORIZED_ACTION';
            }
        }
    }

    public function budgetCategoryUpdateProcess()
    {
        $category = $this->input->post('category');
        $id = $this->input->post('id');

        if ($this->BudgetModel->updateCategory($id, $category) === TRUE) {
            echo 'SUCCESS_CATEGORY_UPDATED';
        } else {
            echo 'ERR_CATEGORY_NOT_UPDATED';
        }
    }

    public function budgetCategoryDeleteProcess()
    {
        $id = $this->input->post('id');

        if ($this->BudgetModel->isAuthorOf($id)) {
            if ($this->BudgetModel->deleteBudgetCategory($id)) {
                echo 'SUCCESS_CATEGORY_DELETED';
            } else {
                echo 'ERR_CATEGORY_NOT_DELETED';
            }
        } else {
            echo 'ERR_UNAUTHORIZED_ACTION';
        }
    }

    public function budgetTransferProcess()
    {
        $transferOrigin = $this->input->post('transferOrigin');
        $transferDestination = $this->input->post('transferDestination');
        $transferAmount = $this->input->post('transferAmount');
        // $transferNote = $this->input->post('transferNote');

        $destinationBudgetName = $this->BudgetModel->getBudgetDetail($transferDestination)[0]->category;
        $originBudgetName = $this->BudgetModel->getBudgetDetail($transferOrigin)[0]->category;

        $transferNoteOut = "Transfer Rp " . number_format($transferAmount, 0, ',', '.') . " to \"" . $destinationBudgetName . "\"";
        $transferNoteIn = "Received Rp " . number_format($transferAmount, 0, ',', '.') . " from \"" . $originBudgetName . "\"";

        $outLog = [
            'budget_id' => $transferOrigin,
            'amount' => $transferAmount,
            'type' => 0,
            'description' => $transferNoteOut
        ];
        $inLog = [
            'budget_id' => $transferDestination,
            'amount' => $transferAmount,
            'type' => 1,
            'description' => $transferNoteIn
        ];

        switch ($this->RecordModel->insertNewTransferRecord($outLog, $inLog)) {
            case 'ERR_NOT_ENOUGH_CREDIT':
                echo 'ERR_NOT_ENOUGH_CREDIT';
                break;
            case 'ERR_FAILED_TO_INSERT_RECORD':
                echo 'ERR_FAILED_TO_INSERT_RECORD';
                break;
            case 'SUCCESS_RECORD_INSERTED':
                echo 'SUCCESS_RECORD_INSERTED';
                break;
            default:
                echo 'UNPREDICTED_ERROR_OCCURED';
                break;
        }
    }
}
