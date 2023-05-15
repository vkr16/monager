<?php

class LoanpayModel extends CI_Model
{
    public function getAllPaymentsOfALoan($loanId)
    {
        $query = $this->db->select('amount,channel,created_at')
            ->from('loan_payments')
            ->where('loan_id', $loanId)
            ->where('deleted_at', NULL)
            ->get();

        return $query->result();
    }

    public function insertPaymentRecord($data)
    {
        $this->db->trans_start();

        $query_part = $this->db->select('paid,unpaid')
            ->from('loans')
            ->where('id', $data['loan_id'])
            ->get_compiled_select();


        $query = $this->db->query($query_part . ' FOR UPDATE');

        $result = $query->result()[0];
        $unpaid_amount = $result->unpaid;
        $payment_amount = $data['amount'];


        if ($unpaid_amount >= $payment_amount) {
            $loanData = [
                'paid' => $result->paid + $payment_amount,
                'unpaid' => $result->unpaid - $payment_amount
            ];
            if ($loanData['unpaid'] == 0) {
                $loanData['payment_status'] = 2;
            } else {
                $loanData['payment_status'] = 1;
            }



            $this->db->set($loanData)
                ->where('id', $data['loan_id'])
                ->update('loans');

            $this->db->set($data)
                ->insert('loan_payments');

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
