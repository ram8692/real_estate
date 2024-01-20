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
                            <li class="breadcrumb-item"><a href="{{route('admin.index')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{route('property.list')}}">Property</a></li>
                            <li class="breadcrumb-item active">Gallery</li>
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
                            @if (session('success'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger" role="alert">
                                    {{ session('error') }}
                                </div>
                            @endif
                            <div class="card-header">
                                <h3 class="card-title">Bordered Table</h3>

                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 10px">#</th>
                                            <th>images</th>
                                            <th style="width: 40px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $currentPage = $galleries->currentPage();
                                            $itemsPerPage = $galleries->perPage();
                                            $serialNumber = ($currentPage - 1) * $itemsPerPage + 1;
                                        @endphp
                                        @foreach ($galleries as $gallery)
                                            <tr>
                                                <td>{{ $serialNumber++ }}</td>
                                                <td>
                                                    <a href="{{ asset('storage/assets/gallery_images/' . $gallery->image_path) }}"
                                                        target="_blank">
                                                        <img src="{{ asset('storage/assets/gallery_images/' . $gallery->image_path) }}"
                                                            alt="Gallery Image"
                                                            style="max-width: 100px; max-height: 100px;">
                                                    </a>
                                                </td>
                                                <td><a href="{{ route('gallery.destroy', ['id' => $gallery->id]) }}"
                                                        onclick="event.preventDefault(); document.getElementById('delete-form-{{ $gallery->id }}').submit();">Delete</a>
                                                    <form id="delete-form-{{ $gallery->id }}"
                                                        action="{{ route('gallery.destroy', ['id' => $gallery->id]) }}"
                                                        method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer clearfix">
                                {{ $galleries->appends(request()->query())->links('vendor.pagination.simple-bootstrap-4') }}
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
