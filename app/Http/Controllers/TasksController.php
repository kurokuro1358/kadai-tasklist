<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;
use App\User;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        if(\Auth::check()){ // 認証済みの場合
            // 認証済みユーザを取得
            $user = \Auth::user();
            // ユーザのタスクを作成日時の降順で取得
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
            
            // Welcomeビューでそれらを表示
            return view('tasks.index', [
                'tasks' => $tasks
            ]);
        }
        else return view('welcome');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new Task;
        
        // タスク作成ビューを表示
        return view('tasks.create', [
            'task' => $task,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'content' => 'required',
            'status' => 'required|max:10',
        ]);
        
        // 認証済みユーザのタスクとして作成
        $request->user()->tasks()->create([
            'content' => $request->content,
            'status' => $request->status,
        ]);
        
        // 前のURLへリダイレクトさせる
        return redirect('tasks');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        // idの値でタスクを検索して取得
        $task = Task::find($id);
        if ($task == null){
            return redirect('/');
        }
        
        //////////////////////////////////////////////////////////////
        // タスクIDが自分のIDであるかどうか？をチェックする処理を追加
        //////////////////////////////////////////////////////////////
        $user = \Auth::user();
        $result = false;
        $result = $user->tasks()->find($id);

        if (!$result){
            return redirect('/');
        }
        
        // タスク詳細ビューでそれらを表示
        return view('tasks.show', [
            // 'user' => $user,
            'task' => $task,
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // idの値でタスクを検索して取得
        $task = Task::find($id);
        if ($task == null){
            return redirect('/');
        }
        
        //////////////////////////////////////////////////////////////
        // タスクIDが自分のIDであるかどうか？をチェックする処理を追加
        //////////////////////////////////////////////////////////////
        $user = \Auth::user();
        $result = false;
        $result = $user->tasks()->find($id);

        if (!$result){
            return redirect('/');
        }
        
        // タスク編集ビューでそれを表示
        return view('tasks.edit', [
            'task' => $task,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // バリデーション
        $request->validate([
            'content' => 'required',
            'status' => 'required|max:10',
        ]);
        
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        // タスクを更新
        $task->content = $request->content;
        $task->status = $request->status;
        $task->save();
        
        // トップページへリダイレクトさせる
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // idの値でタスクを検索して取得
        $task = Task::find($id);
        
        //////////////////////////////////////////////////////////////
        // タスクIDが自分のIDであるかどうか？をチェックする処理を追加
        //////////////////////////////////////////////////////////////
        $user = \Auth::user();
        $result = false;
        $result = $user->tasks()->find($id);

        if (!$result){
            return redirect('/');
        } else{
            $task->delete(); 
        }
        
        // 前のURLへリダイレクトさせる
        return back();
    }
}
