<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Loan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        isLoggedIn();
    }

    public function loanNoteAddProcess()
    {
        $data['borrower'] = $this->input->post('borrower');
        $data['amount'] = $this->input->post('amount');
        $data['description'] = $this->input->post('description');
        $data['due_date'] = $this->input->post('due_date');

        $user_id = $this->UserModel->getUserIdBySession();

        $data['user_id'] = $user_id;
        $data['created_at'] = time();
        $data['payment_status'] = 0;  // 0 is default = unpaid ; {0=Unpaid, 1=Partially paid, 2=Fully paid}
        $data['paid'] = 0;
        $data['unpaid'] = $data['amount'];

        $this->form_validation->set_rules('borrower', 'borrower', 'required');
        $this->form_validation->set_rules('amount', 'amount', 'required|numeric');
        $this->form_validation->set_rules('due_date', 'due date', 'required|numeric');
        $this->form_validation->set_error_delimiters('', '<.0.>');


        if ($this->form_validation->run() == FALSE) {
            echo explode('<.0.>', validation_errors())[0];
        } else {
            if ($this->LoanModel->insertLoanNote($data)) {
                echo 'SUCCESS_LOAN_NOTE_INSERTED';
            } else {
                echo 'ERR_LOAN_NOTE_NOT_INSERTED';
            }
        }
    }

    public function loanPaymentAddProcess()
    {
        $data['loan_id'] = $this->input->post('id');
        $data['amount'] = $this->input->post('amount');
        $data['channel'] = $this->input->post('channel');

        $data['created_at'] = time();

        $this->form_validation->set_rules('amount', 'amount', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            echo explode('<.0.>', validation_errors())[0];
        } else {
            $insert = $this->LoanpayModel->insertPaymentRecord($data);
            switch ($insert) {
                case 0:
                    echo 'ERR_PAYMENT_RECORD_NOT_INSERTED';
                    break;
                case 1:
                    echo 'SUCCESS_PAYMENT_RECORD_INSERTED';
                    break;
                case 2:
                    echo 'ERR_PAYMENT_MORE_THAN_LOAN';
                    break;
                default:
                    echo 'ERR_PAYMENT_RECORD_NOT_INSERTED';
                    break;
            }
        }
    }

    public function loanNoteDeleteProcess()
    {
        $id = $this->input->post('id');
        if ($this->LoanModel->isAuthorOf($id)) {
            if ($this->LoanModel->deleteLoanNote($id)) {
                echo 'SUCCESS_LOAN_NOTE_DELETED';
            } else {
                echo 'ERR_LOAN_NOTE_NOT_DELETED';
            }
        } else {
            echo 'ERR_UNAUTHORIZED_ACTION';
        }
    }
}
