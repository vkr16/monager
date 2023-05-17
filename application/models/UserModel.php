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

    public function getUserIdByEmail($email)
    {
        $query = $this->db->select('id')
            ->from('users')
            ->where('email', $email)
            ->where('deleted_at', NULL)
            ->get();

        return $query->result()[0]->id;
    }

    public function getUserNameBySession()
    {
        $email = base64_decode($this->session->monager_user);
        $query = $this->db->select('name')
            ->from('users')
            ->where('email', $email)
            ->where('deleted_at', NULL)
            ->get();

        return $query->result()[0]->name;
    }

    public function insertNewVerificationCode($email, $code, $expiration)
    {
        $user_id = $this->getUserIdByEmail($email);
        $this->db->set(['code' => $code, 'code_expiration' => $expiration])
            ->where('id', $user_id)
            ->update('users');

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }

    public function isRecoveryCodeValid($email, $code)
    {
        // LAST HERE DO SOMETHING TO CHECK IS RECOVERY LINK VALID TO A SPECIFIC USER THEN SHOW A NEW PASSWORD FORM ELSE KICK OUT FROM THIS URL

    }
}
