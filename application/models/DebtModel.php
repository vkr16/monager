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
}
