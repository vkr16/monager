<?php
class BudgetModel extends CI_Model
{
    public function insertBudgetCategory($newBudgetData)
    {
        $this->db->trans_start();
        $this->db->set($newBudgetData)
            ->insert('budgets');

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }

    public function isExist($id)
    {
        $count = $this->db->select('id')
            ->from('budgets')
            ->where('id', $id)
            ->where('deleted_at', NULL)
            ->count_all_results();
        return $count > 0 ? TRUE : FALSE;
    }

    public function getBudgetCategoriesForCurrentUser()
    {
        $user_id = $this->UserModel->getUserIdBySession();
        $query = $this->db->select('id,category,budget')
            ->from('budgets')
            ->where('user_id', $user_id)
            ->where('deleted_at', NULL)
            ->get();

        return $query->result();
    }

    public function isAuthorOf($budgetId)
    {
        $user_id = $this->UserModel->getUserIdBySession();

        $query = $this->db->select('user_id')
            ->from('budgets')
            ->where('id', $budgetId)
            ->where('deleted_at', NULL)
            ->get();

        return $user_id == $query->result()[0]->user_id ? TRUE : FALSE;
    }

    public function getBudgetDetail($budgetId)
    {
        $query = $this->db->select('category, budget')
            ->from('budgets')
            ->where('id', $budgetId)
            ->where('deleted_at', NULL)
            ->get();

        return $query->result();
    }

    public function updateCategory($id, $category)
    {
        if ($this->isAuthorOf($id)) {
            $this->db->set('category', $category)
                ->where('id', $id)
                ->update('budgets');

            return $this->db->affected_rows() > 0 ? TRUE : FALSE;
        } else {
            return FALSE;
        }
    }

    public function deleteBudgetCategory($id)
    {
        $this->db->where('id', $id)
            ->set('deleted_at', time())
            ->update('budgets');

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }
}
