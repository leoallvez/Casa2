@extends('layouts.externo')

@section('title')
    Solicitar Cadastro
@endsection



@section('content')
<div class="container p-t-md">
    <div role="main">
      <div>
        {{-- <div class="page-title">
          <div class="title_left">
            <h3>Solicitar Cadastro</h3>
          </div>
        </div>
        <div class="clearfix"></div> --}}
        <div class="row row-centered">
          <div class="col-md-10 col-centered">
            <div class="x_panel">
              <div class="x_title">
                <h2 style="font-size: 200%">Solicitar Cadastro</h2>
                <div class="clearfix"></div>
              </div>
              {{-- @include('errors.list') --}}
              <div class="x_content">
                <br/>
                {!! Form::open(['url' => 'solicitar-cadastro']) !!}
                    @include('solicitacao._form', ['nomeBotaoSubmit' => 'Enviar Solicitação'])
                {!! Form::close() !!}
              </div>
            </div>
          </div>
        </div>
    </div>
    </div>
@endsection
