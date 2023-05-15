<?php

class DebtpayModel extends CI_Model
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

    public function insertPaymentRecord($data)
    {
        $this->db->trans_start();

        $query_part = $this->db->select('paid,unpaid')
            ->from('debts')
            ->where('id', $data['debt_id'])
            ->get_compiled_select();


        $query = $this->db->query($query_part . ' FOR UPDATE');

        $result = $query->result()[0];
        $unpaid_amount = $result->unpaid;
        $payment_amount = $data['amount'];


        if ($unpaid_amount >= $payment_amount) {
            $debtData = [
                'paid' => $result->paid + $payment_amount,
                'unpaid' => $result->unpaid - $payment_amount
            ];
            if ($debtData['unpaid'] == 0) {
                $debtData['payment_status'] = 2;
            } else {
                $debtData['payment_status'] = 1;
            }

            $this->db->set($debtData)
                ->where('id', $data['debt_id'])
                ->update('debts');

            $this->db->set($data)
                ->insert('debt_payments');

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return 0;
            } else {
                $this->db->trans_commit();
                return 1;
            }
        } else {
            return 2;
        }
    }
}
