<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pages extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        isLoggedIn();
    }

    public function budgetView()
    {
        $data['categories'] = $this->BudgetModel->getBudgetCategoriesForCurrentUser();
        $this->load->view('pages/budgetView', $data);
    }

    public function debtView()
    {
        $data['debts'] = $this->DebtModel->getAllMyDebts();
        $this->load->view('pages/debtView', $data);
    }

    public function loanView()
    {
        $data['loans'] = $this->LoanModel->getAllMyLoans();
        $this->load->view('pages/loanView', $data);
    }

    public function budgetRecordView($budgetId)
    {
        $data['budgetId'] = base64_decode(base64_decode(base64_decode(urldecode($budgetId))));
        if ($this->BudgetModel->isExist($data['budgetId'])) {
            $data['records'] = $this->RecordModel->getAllRecordOfABudget($data['budgetId']);
            $data['budgetDetail'] = $this->BudgetModel->getBudgetDetail($data['budgetId'])[0];

            $this->load->view('pages/budgetRecordView', $data);
        } else {
            $this->load->view('errors/custom/404');
        }
    }

    public function debtNoteView($debtId)
    {
        $data['debtId'] = base64_decode(base64_decode(base64_decode(urldecode($debtId))));

        if ($this->DebtModel->isExist($data['debtId'])) {
            if ($this->DebtModel->isAuthorOf($data['debtId'])) {
                $data['debt_payments'] = $this->DebtpayModel->getAllPaymentsOfADebt($data['debtId']);
                $data['debt_detail'] = $this->DebtModel->getDebtDetail($data['debtId'])[0];
                $this->load->view('pages/debtPaymentView', $data);
            } else {
                $this->load->view('errors/custom/404');
            }
        } else {
            $this->load->view('errors/custom/404');
        }
    }

    public function loanNoteView($loanId)
    {
        $data['loanId'] = base64_decode(base64_decode(base64_decode(urldecode($loanId))));

        if ($this->LoanModel->isExist($data['loanId'])) {
            if ($this->LoanModel->isAuthorOf($data['loanId'])) {
                $data['loan_payments'] = $this->LoanpayModel->getAllPaymentsOfALoan($data['loanId']);
                $data['loan_detail'] = $this->LoanModel->getLoanDetail($data['loanId'])[0];
                $this->load->view('pages/loanPaymentView', $data);
            } else {
                $this->load->view('errors/custom/404');
            }
        } else {
            $this->load->view('errors/custom/404');
        }
    }
}
