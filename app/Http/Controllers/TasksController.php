<?php

namespace App\Http\Controllers;
use App\Models\Task;
use App\Http\Controllers\Auth;

use Illuminate\Http\Request;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [];
        if (\Auth::check()) { // 認証済みの場合
            // 認証済みユーザーを取得
            $user = \Auth::user();
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
        }
        
        // dashboardビューでそれらを表示
        return view('dashboard', $data);
    }
    /*
    public function index()
    {
        // メッセージ一覧を取得
        $tasks = Task::all();
        // メッセージ一覧ビューでそれを表示
        return view('tasks.index', [
            'tasks' => $tasks,
        ]);
    }
    */
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (\Auth::check()) {// 認証済みの場合
            $task = new Task;
        
            return view('tasks.create', [
                'task' => $task,
            ]);
        }
        
        return redirect('/dashboard');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'status' => 'required|max:10',   // 追加
            'content' => 'required|max:255',
        ]);
        
        $request->user()->tasks()->create([
            'status' => $request->status,
            'content' => $request->content,
        ]);
        
        
        /*
        // メッセージを作成
        $task = new Task;
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();

        // tasklistへリダイレクトさせる
        return redirect('/dashboard');
        
        return back();
        */
        return redirect('/dashboard');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);

       if (\Auth::id() == $task->user_id) {
            // メッセージ編集ビューでそれを表示
            return view('tasks.show', [
                'task' => $task,
                ]);
        }
        
        return redirect('/dashboard');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        
        if (\Auth::id() == $task->user_id) {
            // メッセージ編集ビューでそれを表示
            return view('tasks.edit', [
                'task' => $task,
                ]);
        }
        
        return redirect('/dashboard');
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // バリデーション
        $request->validate([
            'status' => 'required|max:10',   // 追加
            'content' => 'required|max:255',
        ]);
        
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        
        if (\Auth::id() == $task->user_id) {
            // メッセージを更新
            $task->status = $request->status;
            $task->content = $request->content;
            $task->save();
            return redirect('/dashboard');
        }
        
        /*
        // メッセージを更新
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();
        */

        // トップページへリダイレクトさせる
        return redirect('/dashboard');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        /*
        // メッセージを削除
        $task->delete();

        // トップページへリダイレクトさせる
        return redirect('/dashboard');
        */
        
        // 認証済みユーザー（閲覧者）がその投稿の所有者である場合は投稿を削除
        if (\Auth::id() == $task->user_id) {
            $task->delete();
            return redirect('/dashboard');
        }

        // 前のURLへリダイレクトさせる
        return redirect('/dashboard');
    }
}
