<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Debt extends CI_Controller
{

    public function debtNoteAddProcess()
    {
        $data['lender'] = $this->input->post('lender');
        $data['amount'] = $this->input->post('amount');
        $data['description'] = $this->input->post('description');
        $data['due_date'] = $this->input->post('due_date');

        $user_id = $this->UserModel->getUserIdBySession();

        $data['user_id'] = $user_id;
        $data['created_at'] = time();
        $data['payment_status'] = 0;  // 0 is default = unpaid ; {0=Unpaid, 1=Partially paid, 2=Fully paid}
        $data['paid'] = 0;
        $data['unpaid'] = $data['amount'];

        $this->form_validation->set_rules('lender', 'lender', 'required');
        $this->form_validation->set_rules('amount', 'amount', 'required|numeric');
        $this->form_validation->set_rules('due_date', 'due date', 'required|numeric');
        $this->form_validation->set_error_delimiters('', '<.0.>');

        if ($this->form_validation->run() == FALSE) {
            echo explode('<.0.>', validation_errors())[0];
        } else {
            if ($this->DebtModel->insertDebtNote($data)) {
                echo 'SUCCESS_DEBT_NOTE_INSERTED';
            } else {
                echo 'ERR_DEBT_NOTE_NOT_INSERTED';
            }
        }
    }
}
