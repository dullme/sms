<?php

namespace App\Http\Controllers;

use App\TaskHistory;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function detail()
    {
        $task_histories = TaskHistory::with(['task' => function($query){
            $query->select('id', 'content', 'amount');
        }])
            ->where('user_id', Auth()->user()->id)->paginate(20);

        return view('detail', compact('task_histories'));
    }
}
