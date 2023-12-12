<?php

namespace App\Services\UserManagement;

use App\Repositories\UserManagement\UserManagementRepository;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class UserManagementService implements UserManagementServiceInterface
{
    private $mainRepository;

    public function __construct(UserManagementRepository $mainRepository)
    {
        $this->mainRepository = $mainRepository;
    }

    public function rolePermission($request = '', $id = 0)
    {
        if ($id == 0) :
            $id = auth()->id();
        endif;
        $data = $this->mainRepository->rolePermission($id, $request);
        return $data;
    }

    public function getUserAll(): object
    {
        $data = $this->mainRepository->getUserAll();
        return $data;
    }

    public function getUserActive(): object
    {
        return $this->mainRepository->getUserActive();
    }

    public function getEngineer()
    {
        return $this->mainRepository->getEngineer();
    }

    public function getEngineerManager()
    {
        return $this->mainRepository->getEngineerManager();
    }

    public function getManager()
    {
        return $this->mainRepository->getManager();
    }

    public function getGM()
    {
        return $this->mainRepository->getGM();
    }

    public function getByRoleDepartment($request)
    {
        $data = $this->mainRepository->getByRoleDepartment($request);
        return $data;
    }

    public function createUser(object $request)
    {
        $data = $this->mainRepository->createUser($request);
        return $data;
    }

    public function updateUser(object $request, int $id): object
    {
        $data = $this->mainRepository->updateUser($request, $id);
        return $data;
    }

    public function login(object $request)
    {
        // $resultCheckCaptcha = $this->_checkCaptcha($request->token, $request->action);

        // if (!$resultCheckCaptcha) {
        //     throw new BadRequestException('Invalid Captcha');
        // }

        $user = $this->mainRepository->getOneUser((object)['username' => $request->username]);

        if (!$user) {
            throw new BadRequestException('Username atau password anda salah');
        }

        if ($user && !$user->is_enable) {
            throw new BadRequestException('Akun anda tidak aktif');
        }

        if ($user && $user->total_try >= 5 && time() < strtotime($user->last_try . '+ 7 minutes 30 seconds')) {
            throw new BadRequestException('Anda gagal login beberapa kali. Silahkan coba beberapa saat lagi');
        }

        // USE LDAP
        // [
        //     'success' => $success,
        //     'msg' => $msg
        // ] = $this->_loginLdap($request->input('username'), $request->input('password'));

        // if (!$success) {
        //     if ($user) {
        //         $data = (object)[
        //             'total_try' => $user->total_try + 1,
        //             'last_try' => $user->total_try >= 4 ? date('Y-m-d H:i:s') : null
        //         ];

        //         $this->mainRepository->updateUserRAW($data, $user->id);
        //     }

        //     throw new BadRequestException($msg);
        // }

        Auth::login($user);

        // END USE LDAP

        // USE ACCOUNT
        // if (!Auth::attempt($request->only('username', 'password'))) {
        //     throw new BadRequestException('Username atau password anda salah');
        // }

        session()->put([
            'last_check' => null,
            'limitSession' => strtotime(date('Y-m-d H:i:s') . '+ 2 hours'),
        ]);

        $data = (object)[
            'last_login_on' => date('Y-m-d H:i:s'),
            'total_try' => 0,
            'last_try' => null
        ];

        $this->mainRepository->updateUserRAW($data, auth()->id());
    }

    public function updatePassword($request, $id = 0)
    {
        if (empty($id)) {
            $id = auth()->id();
        }
        $data = $this->mainRepository->updatePassword($request, $id);
        return $data;
    }


    public function exportUserManage()
    {
        $data = $this->mainRepository->exportUserManage();
        return $data;
    }

    /**
     * Check Captcha
     *
     * @param string $token
     * @param string $action
     * @return bool
     */
    private function _checkCaptcha(string $token, string $action): bool
    {
        $response = Http::get('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA3_SECRET_KEY'),
            'response' => $token
        ]);

        $response = (object)$response->json();

        return $response->success && $response->action === $action && $response->score >= 0.5;
    }

    /**
     * Login with LDAP
     * @param $username
     * @param $password
     * @return array
     */
    private function _loginLdap($username = '', $password = '')
    {
        $success = FALSE;
        $msg = 'Username atau Password tidak boleh kosong.';
        if (empty($username) || empty($password)) {
            return compact('success', 'msg');
        }

        $ldap_server = 'ldap://ldap.telkomsat.co.id';
        $username = ldap_escape($username, '', LDAP_ESCAPE_DN);
        $base_dn = 'dc=telkomsat,dc=co,dc=id';
        $dn = 'uid=' . $username . ',cn=users,' . $base_dn;

        if ($connect = @ldap_connect($ldap_server)) {
            ldap_set_option($connect, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($connect, LDAP_OPT_REFERRALS, 0);
            ldap_start_tls($connect);
            if ($bind = @ldap_bind($connect, $dn, $password)) {
                $success = TRUE;
                $msg = '';
            } else {
                $success = FALSE;
                $msg = 'LDAP Username atau Password salah.';
            }

            $attributes = array();
            $attributes[] = 'shadowexpire';
            $attributes[] = 'pwdchangedtime';
            $attributes[] = 'pwdpolicysubentry';
            $result = @ldap_search($connect, $dn, '(objectClass=*)', $attributes);

            if (!$result) {
                $success = FALSE;
                $msg = 'Username atau Password salah.';

                return compact('success', 'msg');
            }

            $ldap_user = ldap_get_entries($connect, $result);

            if ($ldap_user) {
                if ($ldap_user[0]['shadowexpire'][0] == 1) {
                    $success = FALSE;
                    $msg = 'Akun Dinonaktifkan.';
                } else {
                    $pwdchangedtime = DateTime::createFromFormat('YmdHisP', $ldap_user[0]['pwdchangedtime'][0])
                        ->format('U');

                    if (empty($ldap_user[0]['pwdpolicysubentry'][0])) {
                        $pwpolicy_dn = 'cn=default,ou=pwpolicies,' . $base_dn;
                    } else {
                        $pwpolicy_dn = $ldap_user[0]['pwdpolicysubentry'][0];
                    }
                    $attributes = array();
                    $attributes[] = 'pwdmaxage';
                    $result = ldap_search($connect, $pwpolicy_dn, '(objectClass=*)', $attributes);
                    $ldap_pwpolicy = ldap_get_entries($connect, $result);
                    if ($ldap_pwpolicy[0]['pwdmaxage'][0] > 0 && $pwdchangedtime + $ldap_pwpolicy[0]['pwdmaxage'][0] < time()) {
                        $success = FALSE;
                        $msg = 'Password Akun Kadaluarsa.';
                    }
                }
            }

            @ldap_close($connect);
        }

        return compact('success', 'msg');
    }
}
