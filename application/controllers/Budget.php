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
}
