<?php

class DebtModel extends CI_Model
{
    public function insertDebtNote($debtNote)
    {
        $this->db->set($debtNote)
            ->insert('debts');
        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }

    public function getAllMyDebts()
    {
        $user_id = $this->UserModel->getUserIdBySession();

        $query = $this->db->select('id, amount, lender, description, payment_status, due_date')
            ->from('debts')
            ->where('user_id', $user_id)
            ->where('deleted_at', NULL)
            ->order_by('payment_status', 'asc')
            ->order_by('due_date', 'asc')
            ->limit(500)
            ->get();
        return $query->result();
    }

    public function isExist($debtId)
    {
        $count = $this->db->select('id')
            ->from('debts')
            ->where('id', $debtId)
            ->where('deleted_at', NULL)
            ->count_all_results();
        return $count > 0 ? TRUE : FALSE;
    }

    public function isAuthorOf($debtId)
    {
        $user_id = $this->UserModel->getUserIdBySession();

        $query = $this->db->select('user_id')
            ->from('debts')
            ->where('id', $debtId)
            ->get();

        return $user_id == $query->result()[0]->user_id ? TRUE : FALSE;
    }

    public function getDebtDetail($debtId)
    {
        $query = $this->db->select('id,amount,lender,description,payment_status,paid,unpaid,due_date,created_at')
            ->from('debts')
            ->where('id', $debtId)
            ->where('deleted_at', NULL)
            ->get();

        return $query->result();
    }

    public function deleteDebtNote($id)
    {
        $this->db->set('deleted_at', time())
            ->where('id', $id)
            ->update('debts');

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }

    public function getTotalUnpaidDebt()
    {
        $user_id = $this->UserModel->getUserIdBySession();

        $query = $this->db->select('sum(unpaid) as unpaidDebt')
            ->from('debts')
            ->where('user_id', $user_id)
            ->where('deleted_at', NULL)
            ->get();

        return $query->result()[0];
    }
}
