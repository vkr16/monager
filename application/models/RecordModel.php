<?php
class RecordModel extends CI_Model
{
    public function insertNewRecord($record)
    {
        $record['created_at'] = time();

        $this->db->trans_begin();

        $query = $this->db->select('budget')
            ->from('budgets')
            ->where('id', $record['budget_id'])
            ->where('deleted_at', NULL)
            ->get_compiled_select();

        $queryForUpdate = $this->db->query($query . ' FOR UPDATE');
        $budgetRemaining = $queryForUpdate->result()[0]->budget;

        if ($record['type'] == 0) {
            if ($budgetRemaining < $record['amount']) {
                $this->db->trans_rollback();
                return 'ERR_NOT_ENOUGH_CREDIT';
            } else {
                $this->db->set('budget', $budgetRemaining - $record['amount'])
                    ->where('id', $record['budget_id'])
                    ->update('budgets');
            }
        } else {
            $this->db->set('budget', $budgetRemaining + $record['amount'])
                ->where('id', $record['budget_id'])
                ->update('budgets');
        }

        $this->db->set($record)
            ->insert('records');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }

    public function getAllRecordOfABudget($budgetId)
    {
        $query = $this->db->select('amount, type, description, created_at')
            ->from('records')
            ->where('budget_id', $budgetId)
            ->where('deleted_at', NULL)
            ->order_by('created_at', 'DESC')
            ->get();

        return $query->result();
    }

    public function insertNewTransferRecord($outLog, $inLog)
    {
        $this->db->trans_begin();
        $rawQueryGetOriginBudget = $this->db->select('budget')
            ->from('budgets')
            ->where('id', $outLog['budget_id'])
            ->get_compiled_select();

        $rawQueryGetDestinationBudget = $this->db->select('budget')
            ->from('budgets')
            ->where('id', $inLog['budget_id'])
            ->get_compiled_select();

        $queryGetOriginBudget = $rawQueryGetOriginBudget . ' FOR UPDATE';
        $queryGetDestinationBudget = $rawQueryGetDestinationBudget . ' FOR UPDATE';

        $originBudget = $this->db->query($queryGetOriginBudget)->result()[0]->budget;
        $destinationBudget = $this->db->query($queryGetDestinationBudget)->result()[0]->budget;

        if ($originBudget < $outLog['amount']) {
            return 'ERR_NOT_ENOUGH_CREDIT';
        } else {
            $outLog['created_at'] = time();
            $inLog['created_at'] = time();

            $this->db->set('budget', $originBudget - $outLog['amount'])
                ->where('id', $outLog['budget_id'])
                ->update('budgets');

            $this->db->set('budget', $destinationBudget + $inLog['amount'])
                ->where('id', $inLog['budget_id'])
                ->update('budgets');

            $this->db->insert('records', $outLog);
            $this->db->insert('records', $inLog);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return 'ERR_FAILED_TO_INSERT_RECORD';
            } else {
                $this->db->trans_commit();
                return 'SUCCESS_RECORD_INSERTED';
            }
        }
    }
}
