@extends('layouts.app')

@section('title')
  Adotantes
@endsection

@section('content')
    <div role="main">
      <div>
        <div class="page-title">
          <div class="title_left">
            <h3>Adotantes</h3>
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
              <div class="x_title">
                <h2>Alterar</h2>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                <br/>
                {!! Form::model($adotante, 
                    [
                      'method' => 'PATCH', 
                      'action' => ['AdotanteController@update', $adotante->id]
                    ]) 
                !!}
                  @include('adotante._form', ['nomeBotaoSubmit' => 'Alterar'])
                {!! Form::close() !!}
              </div>
            </div>
          </div>
        </div>
    </div>
@endsection
