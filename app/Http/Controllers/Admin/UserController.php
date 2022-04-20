<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:edit-users');
    }

    public function index()
    {
        $user = new User();
        $loggedId = intval(Auth::id());
        if(isset($_GET['query']) && ($_GET['query'] != null)){
            $search = $_GET['query'];
            $users = $user->where('name', 'LIKE', '%'.$search.'%')->paginate(5);
            return view('admin.users.index', ['users' => $users,
                'loggedId' => $loggedId
            ]);
        }

        $users = $user->paginate(5);
        return view('admin.users.index', [
            'users' => $users,
            'loggedId' => $loggedId
        ]);
    }

    public function create()
    {
        return view('admin.users.create');
    }


    public function store(Request $request)    {

        $data = $this->validate($request, [
            'name' => 'required|max:100',
            'email' => 'required|email|unique:users|max:100',
            'password' => 'required|min:4',
            'password_confirmation' => 'required|same:password|min:4'
        ]);

        $user = new User();
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->password = Hash::make($request['password']);

        $user->save();
        return redirect('/painel/users/create')
            ->with('type', 'alert-success')
            ->with('msg', 'Usuário cadastrado com sucesso!')->withInput();


    }


    public function show($id)
    {

    }


    public function edit($id)
    {
        $user = User::findOrFail($id);
        if($user){
           return view('admin.users.edit', [
               'user' => $user
           ]);
        }
        return view('admin.users.index');
    }


    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if($user){
            $data = $this->validate($request, [
                'name' => 'required|max:100',
                'email' => 'required|email|max:100',
            ]);

            $user->name = $data['name'];

            if($user->email != $data['email']){
                $hasEmail = User::where('email', $data['email'])->get();
                if(count($hasEmail) === 0){
                    $user->email = $data['email'];
                }

                return redirect()->route('users.edit', ['user'=> $user->id])
                    ->with('type', 'alert-danger')
                    ->with('msg', 'Email já cadastrado!');
            }

            if(!empty($request['password'])){

                $this->validate($request, [
                    'password' => 'required|min:4',
                    'password_confirmation' => 'required|same:password|min:4'
                ]);

                if($request['password'] === $request['password_confirmation']){
                    $user->password = Hash::make($request['password']);
                }
            }
        }
        $user->update();
        return redirect()->route('users.edit', ['user'=> $user->id])
            ->with('type', 'alert-primary')
            ->with('msg', 'Usuário atualizado com sucesso!');
    }


    public function destroy($id)
    {
       $loggedId = intval(Auth::id());

       if($loggedId !== intval($id)){
           $user = User::findOrFail($id);
           $user->delete();
       }
        return redirect()->route('users.index')
            ->with('type', 'alert-primary')
            ->with('msg', 'Usuário atualizado com sucesso!');
    }
}
