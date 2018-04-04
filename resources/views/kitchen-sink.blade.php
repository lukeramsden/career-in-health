@extends('layouts.app')
@section('content')
    <div class="container-fluid mt-3">
        <div class="card card-custom" style="width: 24rem;">
          <div class="card-body">
            <h5 class="card-title">Card title</h5>
            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
          </div>
          <ul class="list-group list-group-flush">
            <li class="list-group-item">Cras justo odio</li>
            <li class="list-group-item">Dapibus ac facilisis in</li>
            <li class="list-group-item">Vestibulum at eros</li>
          </ul>
          <div class="card-body">
            <a href="javascript:" class="card-link">Card link</a>
            <a href="javascript:" class="card-link">Another link</a>
          </div>
        </div>
    
        <br>
        
        <ul class="pagination">
          <li class="page-item page-item-previous disabled"><a class="page-link" href="javascript:" tabindex="-1">&lt;</a></li>
          <li class="page-item page-item-active"><a class="page-link" href="javascript:">1</a></li>
          <li class="page-item"><a class="page-link" href="javascript:">2</a></li>
          <li class="page-item"><a class="page-link" href="javascript:">3</a></li>
          <li class="page-item"><a class="page-link" href="javascript:">4</a></li>
          <li class="page-item page-item-ellipses disabled"><a class="page-link" href="javascript:">...</a></li>
          <li class="page-item"><a class="page-link" href="javascript:">11</a></li>
          <li class="page-item"><a class="page-link" href="javascript:">12</a></li>
          <li class="page-item"><a class="page-link" href="javascript:">13</a></li>
          <li class="page-item"><a class="page-link" href="javascript:">14</a></li>
          <li class="page-item page-item-next"><a class="page-link" href="javascript:">&gt;</a></li>
        </ul>
    </div>
@endsection