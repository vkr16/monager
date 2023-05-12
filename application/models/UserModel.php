<?php
class UserModel extends CI_Model
{
    public function isEmailExist($email)
    {
        $query = $this->db->select('id')
            ->from('users')
            ->where('email', $email)
            ->where('deleted_at', NULL)
            ->count_all_results();
        return $query > 0 ? TRUE : FALSE;
    }

    public function insertUser($data)
    {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $this->db->set($data)
            ->insert('users');
        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }

    public function validatePassword($email, $password)
    {
        $user = $this->db->select('password')
            ->from('users')
            ->where('email', $email)
            ->where('deleted_at', NULL)
            ->get()
            ->result()[0];
        return password_verify($password, $user->password) ? TRUE : FALSE;
    }

    public function getUserIdBySession()
    {
        $email = base64_decode($this->session->monager_user);
        $query = $this->db->select('id')
            ->from('users')
            ->where('email', $email)
            ->where('deleted_at', NULL)
            ->get();

        return $query->result()[0]->id;
    }
}
