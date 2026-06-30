<?php

namespace App\Controllers;

class Auth extends BaseController
{
    public function index()
    {
        return redirect()->to(site_url('login'));
    }

    public function login()
    {
        $data['title_tab'] = 'Login &mdash; LPM UG';

        if (session('user_id') == true) {
            return redirect()->to(site_url('dashboard'));
        }

        $num1 = rand(1, 9);
        $num2 = rand(1, 9);
        $data['captcha_num1'] = $num1;
        $data['captcha_num2'] = $num2;
        session()->set('captcha_answer', $num1 + $num2);

        return view('auth/login', $data);
    }

    public function loginProcess()
    {
        $post = $this->request->getPost();

        // Validasi Captcha
        $captchaInput = $post['captcha'] ?? '';
        $captchaAnswer = session()->get('captcha_answer');

        if ($captchaInput === '' || (int)$captchaInput !== (int)$captchaAnswer) {
            return redirect()->back()->with('error', 'Jawaban keamanan salah.');
        }

        $query = $this->db->table('tbl_users')->getWhere(['nidn' => $post['nidn']]);
        $user = $query->getRow();

        if ($user && password_verify($post['password'], $user->password)) {
            // Regenerate session ID to prevent session fixation
            session()->regenerate(true);

            $params = ['user_id' => $user->user_id];
            session()->set($params);

            return redirect()->to(site_url('dashboard'))->with('success', 'Selamat datang.');
        } else {
            // Generic message to prevent username enumeration
            return redirect()->back()->with('error', 'NIDN/Username atau password salah.');
        }
    }

    public function registrasi()
    {
        $data['title_tab'] = 'Registrasi &mdash; LPM UG';
        $data['validation'] = \Config\Services::validation();

        if (session('user_id') == true) {
            return redirect()->to(site_url('dashboard'));
        }

        $num1 = rand(1, 9);
        $num2 = rand(1, 9);
        $data['captcha_num1'] = $num1;
        $data['captcha_num2'] = $num2;
        session()->set('captcha_answer', $num1 + $num2);

        // Load models for dropdowns
        $jurusanModel = new \App\Models\JurusanModel();
        $kotaModel = new \App\Models\KotaModel();

        $data['jurusan'] = $jurusanModel->getAll();
        $data['kota'] = $kotaModel->getAll();

        return view('auth/registrasi', $data);
    }

    public function registrasiProcess()
    {

        $db      = \Config\Database::connect();
        $builder = $db->table('tbl_users');

        $data['title_tab'] = 'Registrasi &mdash; LPM UG';

        $captchaInput = $this->request->getPost('captcha') ?? '';
        $captchaAnswer = session()->get('captcha_answer');

        if ($captchaInput === '' || (int)$captchaInput !== (int)$captchaAnswer) {
            return redirect()->back()->withInput()->with('error', 'Maaf, jawaban keamanan (Captcha) salah.');
        }

        $rules = [
            'user_name' => [
                // 'rules' => 'required|min_length[3]',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama user tidak boleh kosong.',
                    // 'min_length' => 'Nama minimal 3 huruf'
                ],
            ],
            'sinta_id' => [
                // 'rules' => 'required|is_unique[tbl_users.nidn]|min_length[7]|max_length[7]',
                'rules' => 'required|is_unique[tbl_users.nidn]',
                'errors' => [
                    'required' => 'SINTA ID tidak boleh kosong.',
                    'is_unique' => 'SINTA ID sudah terdaftar, silahkan masukan SINTA ID yang berbeda.',
                    // 'min_length' => 'SINTA ID harus 7 karakter.',
                    // 'max_length' => 'SINTA ID harus 7 karakter.'
                ],
            ],
            'nidn' => [
                // 'rules' => 'required|is_unique[tbl_users.nidn]|min_length[10]|max_length[16]',
                'rules' => 'required|is_unique[tbl_users.nidn]',
                'errors' => [
                    'required' => 'Username / NIDN (NUPTK) tidak boleh kosong.',
                    'is_unique' => 'Username / NIDN (NUPTK) sudah terdaftar, silahkan masukan NIDN (NUPTK) yang berbeda.',
                    // 'min_length' => 'Username / NIDN (NUPTK) harus 10 - 16 karakter.',
                    // 'max_length' => 'Username / NIDN (NUPTK) harus 10 - 16 karakter.'
                ],
            ],
            'password' => [
                'rules' => 'required|min_length[8]',
                'errors' => [
                    'required'   => 'Password tidak boleh kosong.',
                    'min_length' => 'Password minimal 8 karakter.',
                ],
            ],
            'password_konfirmasi' => [
                // 'rules' => 'required|min_length[6]|matches[password]',
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required'   => 'Password Konfirmasi tidak boleh kosong.',
                    // 'min_length' => 'Password harus 6 karakter.',
                    'matches'    => 'Password konfirmasi harus sama dengan dengan password.'
                ],
            ],
            'email' => [
                'rules' => 'required',
                'errors' => [
                    'required'      => 'Pastikan email anda terisi dengan benar.'
                ],
            ],
            'role_id' => [
                'rules' => 'required|in_list[4,5]',
                'errors' => [
                    'required' => 'Silahkan pilih mendaftar sebagai terlebih dahulu.',
                    'in_list'  => 'Role pendaftaran tidak valid.',
                ],
            ],
            'syarat' => [
                'rules' => 'required',
                'errors' => [
                    'required'   => 'Silahkan menyutujui syarat dan ketentuan yang berlaku.'
                ],
            ],
        ];

        // Add conditional rules based on role_id
        $role_id = $this->request->getVar('role_id');
        if ($role_id == 4) { // Dosen
            $rules['jurusan_id'] = [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Bidang Ilmu wajib diisi untuk Dosen.'
                ],
            ];
        } elseif ($role_id == 5) { // Mitra
            $rules['kontak'] = [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kontak wajib diisi untuk Mitra.'
                ],
            ];
            $rules['kota_id'] = [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kota wajib diisi untuk Mitra.'
                ],
            ];
            $rules['alamat'] = [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Alamat wajib diisi untuk Mitra.'
                ],
            ];
        }

        if (!$this->validate($rules)) {
            $validation = \Config\Services::validation();
            // dd($validation);
            return redirect()->back()->withInput()->with('validation', $validation);
        }

        $data = [
            'user_name'     => $this->request->getVar('user_name'),
            'sinta_id'      => $this->request->getVar('sinta_id'),
            'nidn'          => $this->request->getVar('nidn'),
            'email'         => $this->request->getVar('email'),
            'password'      => password_hash($this->request->getVar('password'), PASSWORD_BCRYPT),
            'role_id'       => $this->request->getVar('role_id'),
            'status'        => 1,
        ];

        // Add role-specific fields
        if ($this->request->getVar('role_id') == 4) { // Dosen
            $data['jurusan_id'] = $this->request->getVar('jurusan_id');
        } elseif ($this->request->getVar('role_id') == 5) { // Mitra
            $data['kontak'] = $this->request->getVar('kontak');
            $data['kota_id'] = $this->request->getVar('kota_id');
            $data['alamat'] = $this->request->getVar('alamat');
        }

        $builder->insert($data);
        return redirect()->to(site_url('login'))->with('success', 'Data baru anda berhasil terdaftar. Silahkan Login.');
    }

    public function logout()
    {
        // Destroy the entire session to prevent hijacking
        session()->destroy();

        return redirect()->to(site_url('login'));
    }
}
