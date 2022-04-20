@extends('adminlte::page')

@section('title', 'Produto')

@section('content_header')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Produtos</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Lista de Produtos</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
@endsection

@section('content')
    <section class="content-">
        <div class="container-fluid">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <a class="btn btn-success btn-sm" href="{{ route('users.create') }}">
                                <i class="fas fa-save">
                                </i>
                                Cadastrar Post
                            </a>
                        </h3>

                        <div class="card-tools">
                            <form action="{{ route('users.index') }}" method="GET">
                                <div class="input-group input-group-sm" style="width: 250px;">
                                    <input type="text" name="query" class="form-control float-right" placeholder="Buscar...">

                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.card-header -->

                    <div class="card-body table-responsive p-0">

                        @if(isset($users))
                            <table class="table table-hover text-nowrap">
                                <thead>
                                <tr>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>E-mail</th>
                                    <th>Ações</th>
                                </tr>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($users) > 0)
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{$user->id}}</td>
                                            <td>{{$user->name}}</td>
                                            <td>{{$user->email}}</td>
                                            <td>
                                                <a href="{{route('users.edit', ['user'=> $user->id])}}" class="btn btn-sm btn-info">Editar</a>
                                                @if($loggedId !== intval($user->id))
                                                    <form class="d-inline" action="{{ route('users.destroy', ['user'=> $user->id]) }}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger">Excluir</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td>Não foi encontrado nenhum resultado</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        @endif
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">
                        {{ $users->links('pagination::bootstrap-4') }}
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </section>
@endsection
