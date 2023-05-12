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
        $this->load->view('pages/debtView');
    }

    public function loanView()
    {
        $this->load->view('pages/loanView');
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
}
