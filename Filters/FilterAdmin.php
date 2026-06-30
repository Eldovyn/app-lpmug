<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class FilterAdmin implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if(session()->user_id == ''){
            return redirect()->to(site_url('login'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // FilterAdmin after() should not redirect - role checking is done in controllers
        // This method is kept empty to fulfill the FilterInterface contract
    }
}