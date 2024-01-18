@extends('admin.layouts.main')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Simple Tables</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Simple Tables</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              @if(session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger" role="alert">
        {{ session('error') }}
    </div>
@endif
              <div class="card-header">
                <h3 class="card-title">Bordered Table</h3>
                <div class="card-tools">
                    <form action="{{ route('property.list') }}" method="get">
                    <div class="input-group input-group-sm" style="width: 500px;">
                      <input type="text " name="city" class="form-control float-right" placeholder="Search City" value="{{ request('city') }}">
                      <input type="text" name="nofbrooms" class="form-control float-right" placeholder="Search No of Bebrooms" value="{{ request('nofbrooms') }}">
                      <input type="text" name="noffloor" class="form-control float-right" placeholder="Search No of Floor" value="{{ request('noffloor') }}">
  
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
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Name</th>
                      <th>price</th>
                      <th>Floor Area</th>
                      <th>Bedroom</th>
                      <th>city</th>
                      <th>Featured Image</th>
                      <th style="width: 40px">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php
        $currentPage = $properties->currentPage();
        $itemsPerPage = $properties->perPage();
        $serialNumber = ($currentPage - 1) * $itemsPerPage + 1;
    @endphp
                    @foreach($properties as $property)
                    <tr>
                      <td>{{ $serialNumber++ }}</td>
                      <td>{{ $property->title }}</td>
                      <td>{{ $property->price }}</td>
                      <td>{{ $property->floor_area }}</td>
                      <td>{{ $property->bedroom }}</td>
                      <td>{{ $property->city }}</td>
                      <td><a href="{{asset('storage/assets/featured_images/'.$property->featured_image)}}" target="_blank">Featured</a></td>
                      <td><a href="{{route('galleries.index', ['property_id' => $property->id]) }}" target="_blank">Gallery</a></td>
                      <td><a href="{{ route('property.edit', ['id' => $property->id]) }}">Edit</a><a href="{{ route('property.destroy', ['id' => $property->id]) }}" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $property->id }}').submit();">Delete</a><form id="delete-form-{{ $property->id }}" action="{{ route('property.destroy', ['id' => $property->id]) }}" method="POST" style="display: none;">
                          @csrf
                          @method('DELETE')
                      </form></td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer clearfix">
                {{ $properties->appends(request()->query())->links('vendor.pagination.simple-bootstrap-4') }}
              </div>
            </div>
            <!-- /.card -->

           
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection
  