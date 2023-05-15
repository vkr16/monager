<?php

class LoanModel extends CI_Model
{
    public function insertLoanNote($loanNote)
    {
        $this->db->set($loanNote)
            ->insert('loans');
        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }

    public function getAllMyLoans()
    {
        $user_id = $this->UserModel->getUserIdBySession();

        $query = $this->db->select('id, amount, borrower, description, payment_status, due_date')
            ->from('loans')
            ->where('user_id', $user_id)
            ->where('deleted_at', NULL)
            ->order_by('payment_status', 'asc')
            ->order_by('due_date', 'asc')
            ->limit(500)
            ->get();
        return $query->result();
    }

    public function isExist($loanId)
    {
        $count = $this->db->select('id')
            ->from('loans')
            ->where('id', $loanId)
            ->where('deleted_at', NULL)
            ->count_all_results();
        return $count > 0 ? TRUE : FALSE;
    }

    public function isAuthorOf($loanId)
    {
        $user_id = $this->UserModel->getUserIdBySession();

        $query = $this->db->select('user_id')
            ->from('loans')
            ->where('id', $loanId)
            ->get();

        return $user_id == $query->result()[0]->user_id ? TRUE : FALSE;
    }

    public function getLoanDetail($loanId)
    {
        $query = $this->db->select('id,amount,borrower,description,payment_status,paid,unpaid,due_date,created_at')
            ->from('loans')
            ->where('id', $loanId)
            ->where('deleted_at', NULL)
            ->get();

        return $query->result();
    }

    public function deleteLoanNote($id)
    {
        $this->db->set('deleted_at', time())
            ->where('id', $id)
            ->update('loans');

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }
}
