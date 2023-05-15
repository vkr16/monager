<?php

class LoanpayModel extends CI_Model
{
    public function getAllPaymentsOfADebt($debtId)
    {
        $query = $this->db->select('amount,channel,created_at')
            ->from('debt_payments')
            ->where('debt_id', $debtId)
            ->where('deleted_at', NULL)
            ->get();

        return $query->result();
    }
}
