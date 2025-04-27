<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;

class BaseController extends Controller
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function index()
    {

    }
}
