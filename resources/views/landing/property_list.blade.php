@extends('landing.layouts.main')
@section('content')
  <main id="main">

    <!-- ======= Intro Single ======= -->
    <section class="intro-single">
      <div class="container">
        <div class="row">
          <div class="col-md-12 col-lg-8">
            <div class="title-single-box">
              <h1 class="title-single">Our Amazing Properties</h1>
              <span class="color-text-a">Grid Properties</span>
            </div>
          </div>
          <div class="col-md-12 col-lg-4">
            <nav aria-label="breadcrumb" class="breadcrumb-box d-flex justify-content-lg-end">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">
                  <a href="#">Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                  Properties Grid
                </li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </section><!-- End Intro Single-->

    <!-- ======= Property Grid ======= -->
    <section class="property-grid grid">
      <div class="container">
        <div class="row">
          <div class="col-sm-12 mb-5">
          
            <form action="{{ route('property_list') }}" method="GET" class="form-inline">
              <div class="form-group mx-2">
                  <label for="property_name" class="sr-only">Property Name</label>
                  <input type="text" name="property_name" id="property_name" class="form-control" placeholder="Property Name" value="{{ request('property_name') }}">
              </div>
              <div class="form-group mx-2">
                  <label for="city" class="sr-only">City</label>
                  <input type="text" name="city" id="city" class="form-control" placeholder="City" value="{{ request('city') }}">
              </div>
              <div class="form-group mx-2">
                  <label for="bedroom" class="sr-only">Bedroom</label>
                  <input type="number" name="bedroom" id="bedroom" class="form-control" placeholder="Bedroom" value="{{ request('bedroom') }}">
              </div>
              <div class="form-group mx-2 mb-3">
                  <label for="max_price" class="sr-only">Max Price</label>
                  <input type="number" name="max_price" id="max_price" class="form-control" placeholder="Max Price" value="{{ request('max_price') }}">
              </div>
              <button type="submit" class="btn btn-primary mx-2">Apply Filters</button>
          </form>
          
            

          </div>
          @foreach ($properties as $property)
          <div class="col-md-4">
            <div class="card-box-a card-shadow">
              <div class="img-box-a">
                <img src="{{asset('storage/assets/featured_images/'.$property->featured_image)}}" alt="" class="img-a img-fluid">
              </div>
              <div class="card-overlay">
                <div class="card-overlay-a-content">
                  <div class="card-header-a">
                    <h2 class="card-title-a">
                      <a href="#">{{$property->title}}
</a>
                    </h2>
                  </div>
                  <div class="card-body-a">
                    <div class="price-box d-flex">
                      <span class="price-a">price | {{$property->price}}</span>
                    </div>
                    <a href="{{ route('property.info', ['id' => $property->id]) }}
                      " class="link-a">Click here to view
                      <span class="bi bi-chevron-right"></span>
                    </a>
                  </div>
                  <div class="card-footer-a">
                    <ul class="card-info d-flex justify-content-around">
                      <li>
                        <h4 class="card-info-title"> Floor Area</h4>
                        <span>{{$property->floor_area}}
                          <sup></sup>
                        </span>
                      </li>
                      <li>
                        <h4 class="card-info-title">Beds</h4>
                        <span>{{$property->bedroom}}</span>
                      </li>
                      <li>
                        <h4 class="card-info-title">Baths</h4>
                        <span>{{$property->bathroom}}</span>
                      </li>
                      
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endforeach
        </div>
        <div class="row">
          <div class="col-sm-12">
            {{ $properties->appends(request()->query())->links('vendor.pagination.landing-bootstrap-4') }}
          </div>
        </div>
      </div>
    </section><!-- End Property Grid Single-->

  </main><!-- End #main -->
  @endsection
