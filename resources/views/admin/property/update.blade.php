@extends('admin.layouts.main')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Update Property</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('admin.index')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{route('property.list')}}">Property</a></li>
                            <li class="breadcrumb-item active">Property Update</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- jquery validation -->
                        <div class="card card-primary">

                            <!-- /.card-header -->
                            <!-- form start -->
                            <form id="quickForm" method="POST"
                                action="{{ route('property.update', ['id' => $property->id]) }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT') {{-- Use the HTTP PUT method for updates --}}
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Title*</label>
                                                <input type="text" class="form-control" name="title"
                                                    placeholder="Enter Title" value="{{ old('title', $property->title) }}"
                                                    required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Price*</label>
                                                <input type="number" class="form-control" name="price"
                                                    value="{{ old('price', $property->price) }}" placeholder="Enter Price"
                                                    required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Floor Area*</label>
                                                <input type="number" class="form-control" name="floor_area"
                                                    value="{{ old('floor_area', $property->floor_area) }}"
                                                    placeholder="Floor Area" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Bedroom*</label>
                                                <input type="number" class="form-control" name="bedroom"
                                                    value="{{ old('bedroom', $property->bedroom) }}"
                                                    placeholder="Floor Area" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Bathroom*</label>
                                                <input type="number" class="form-control" name="bathroom"
                                                    value="{{ old('bathroom', $property->bathroom) }}"
                                                    placeholder="Floor Area" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>City*</label>
                                                <input type="text" class="form-control" name="city"
                                                    value="{{ old('city', $property->city) }}" placeholder="Enter Title"
                                                    required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <!-- textarea -->
                                            <div class="form-group">
                                                <label>Address*</label>
                                                <textarea class="form-control" rows="3" name="address" placeholder="Enter Address" required>{{ old('address', $property->address) }}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <!-- textarea -->
                                            <div class="form-group">
                                                <label>Description*</label>
                                                <textarea class="form-control" rows="3" name="description" placeholder="Enter Description" required>{{ old('description', $property->description) }}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Upload Featured Images</label>
                                                <input type="file" class="form-control-file" name="featured"
                                                    accept="image/*">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Upload Gallery Images</label>
                                                <input type="file" class="form-control-file" name="gallery[]"
                                                    accept="image/*" multiple>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Near By Place</label>
                                                <input type="text" class="form-control" name="nearby_place"
                                                    value="{{ old('nearby_place', $property->nearby_place) }}"
                                                    placeholder="Enter Near By Place">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>

                        </div>
                        <!-- /.card -->
                    </div>
                    <!--/.col (left) -->
                    <!-- right column -->
                    <div class="col-md-6">

                    </div>
                    <!--/.col (right) -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
