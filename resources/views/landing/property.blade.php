@extends('landing.layouts.main')
@section('content')
    <main id="main">

        <!-- ======= Intro Single ======= -->
        <section class="intro-single">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-lg-8">
                        <div class="title-single-box">
                            <h1 class="title-single">{{ $property->title }}</h1>
                            <span class="color-text-a">{{ $property->city }}</span>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-4">
                        <nav aria-label="breadcrumb" class="breadcrumb-box d-flex justify-content-lg-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{route('index')}}">Home</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{route('property_list')}}">Properties</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    property
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </section><!-- End Intro Single-->

        <!-- ======= Property Single ======= -->
        <section class="property-single nav-arrow-b">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div id="property-single-carousel" class="swiper">
                            <div class="swiper-wrapper">
                                <div class="carousel-item-b swiper-slide">
                                    <img src="{{ asset('storage/assets/featured_images/' . $property->featured_image) }}"
                                        alt="Featured Image">
                                </div>
                                @foreach ($property->galleries as $gallery)
                                    <div class="carousel-item-b swiper-slide">
                                        <img src="{{ asset('storage/assets/gallery_images/' . $gallery->image_path) }}"
                                            alt="Gallery Image">
                                    </div>
                                @endforeach

                            </div>
                        </div>
                        <div class="property-single-carousel-pagination carousel-pagination"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">

                        <div class="row justify-content-between">
                            <div class="col-md-5 col-lg-4">
                                <div class="property-price d-flex justify-content-center foo">
                                    <div class="card-header-c d-flex">
                                        <div class="card-box-ico">
                                            <span class="bi bi-cash">$</span>
                                        </div>
                                        <div class="card-title-c align-self-center">
                                            <h5 class="title-c">{{$property->price}}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="property-summary">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="title-box-d section-t4">
                                                <h3 class="title-d">Quick Summary</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="summary-list">
                                        <ul class="list">
                                            <li class="d-flex justify-content-between">
                                                <strong>Property ID:</strong>
                                                <span>{{ $property->id }}</span>
                                            </li>
                                            <li class="d-flex justify-content-between">
                                                <strong>Location:</strong>
                                                <span>{{ $property->city }}</span>
                                            </li>
                                            <li class="d-flex justify-content-between">
                                                <strong>Floor Area:</strong>
                                                <span>{{ $property->floor_area }}</span>
                                            </li>
                                            <li class="d-flex justify-content-between">
                                                <strong>Bedroom:</strong>
                                                <span>{{ $property->bedroom }}</span>
                                            </li>
                                            <li class="d-flex justify-content-between">
                                                <strong>Bathroom:</strong>
                                                <span>{{ $property->bathroom }}
                                                </span>
                                            </li>
                                            <li class="d-flex justify-content-between">
                                                <strong>Near By Place:</strong>
                                                <span>{{ $property->nearby_place }}</span>
                                            </li>

                                            <li class="d-flex justify-content-between">
                                                <strong>Address:</strong>
                                                <span>{{ $property->address }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7 col-lg-7 section-md-t3">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="title-box-d">
                                            <h3 class="title-d">Property Description</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="property-description">
                                    <p class="description color-text-a">
                                        {{ $property->description }}
                                    </p>

                                </div>
                                <div class="row section-t3">
                                    <div class="col-sm-12">
                                        <div class="title-box-d">
                                            <h3 class="title-d">Amenities</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="amenities-list color-text-a">
                                    <ul class="list-a no-margin">
                                        <li>Balcony</li>
                                        <li>Outdoor Kitchen</li>
                                        <li>Cable Tv</li>
                                        <li>Deck</li>
                                        <li>Tennis Courts</li>
                                        <li>Internet</li>
                                        <li>Parking</li>
                                        <li>Sun Room</li>
                                        <li>Concrete Flooring</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                    <div class="col-md-12">
                        <div class="row section-t3">
                            <div class="col-sm-12">
                                <div class="title-box-d">
                                    <h3 class="title-d">Contact Agent</h3>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <img src="{{ asset('storage/assets/profile_images/' . $property->user->profile_image) }}"
                                    alt="" class="img-fluid">
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="property-agent">
                                    <h4 class="title-agent">{{ $property->user->name }}</h4>
                                    <p class="color-text-a">
                                        Nulla porttitor accumsan tincidunt. Vestibulum ac diam sit amet quam vehicula
                                        elementum sed sit amet
                                        dui. Quisque velit nisi,
                                        pretium ut lacinia in, elementum id enim.
                                    </p>
                                    <ul class="list-unstyled">
                                        <li class="d-flex justify-content-between">
                                            <strong>Email:</strong>
                                            <span class="color-text-a">{{ $property->user->email }}</span>
                                        </li>
                                    </ul>
                                    <div class="socials-a">
                                        <ul class="list-inline">
                                            <li class="list-inline-item">
                                                <a href="#">
                                                    <i class="bi bi-facebook" aria-hidden="true"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item">
                                                <a href="#">
                                                    <i class="bi bi-twitter" aria-hidden="true"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item">
                                                <a href="#">
                                                    <i class="bi bi-instagram" aria-hidden="true"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item">
                                                <a href="#">
                                                    <i class="bi bi-linkedin" aria-hidden="true"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            @if(auth()->check())
                            <div class="col-md-12 col-lg-4">
                                <div class="property-contact">
                                    <!-- Check for success message -->
                                    @if (session('success'))
                                        <div class="alert alert-success" role="alert">
                                            {{ session('success') }}
                                        </div>
                                    @endif

                                    <!-- Check for error message -->
                                    @if ($errors->any())
                                        <div class="alert alert-danger" role="alert">
                                            @foreach ($errors->all() as $error)
                                                {{ $error }}<br>
                                            @endforeach
                                        </div>
                                    @endif
                                    <form action="{{ route('message.send') }}" method="post" class="form-a">
                                        @csrf <!-- Add CSRF token for security -->
                                        <input type="hidden" name="property_id" value="{{ $property->id }}">
                                        <div class="row">
                                            <div class="col-md-12 mb-1">
                                                <div class="form-group">
                                                    <input type="text"
                                                        class="form-control form-control-lg form-control-a" id="inputName"
                                                        name="name" placeholder="Name *" required>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mb-1">
                                                <div class="form-group">
                                                    <input type="email"
                                                        class="form-control form-control-lg form-control-a" name="email"
                                                        id="inputEmail1" placeholder="Email *" required>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mb-1">
                                                <div class="form-group">
                                                    <input type="number"
                                                        class="form-control form-control-lg form-control-a" name="contact"
                                                        id="inputContact" placeholder="Contact *" required>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mb-1">
                                                <div class="form-group">
                                                    <textarea id="textMessage" class="form-control" placeholder="Comment *" name="content" cols="45"
                                                        rows="8" required></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <button type="submit" class="btn btn-a">Send Message</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @endif
                            <div>
                                <div class="container chat-container">
                                    @foreach ($property->messages->where('parent_id', null) as $message)
                                        <div class="message bg-success text-white">
                                            {{ $message->content }}
                                        </div>
                                        @if ($message->replies->count() > 0)
                                            @foreach ($message->replies as $reply)
                                                <div class="reply bg-sucess">
                                                    {{ $reply->content }}
                                                </div>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- End Property Single-->

    </main><!-- End #main -->
@endsection
