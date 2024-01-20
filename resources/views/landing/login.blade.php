@extends('landing.layouts.main')
@section('content')
    <main id="main">

        <!-- ======= Intro Single ======= -->
        <section class="intro-single">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-lg-8">
                        <div class="title-single-box">
                            <h1 class="title-single">Login</h1>
                            <span class="color-text-a">Aut voluptas consequatur unde sed omnis ex placeat quis eos. Aut natus
                                officia corrupti qui autem fugit consectetur quo. Et ipsum eveniet laboriosam voluptas
                                beatae possimus qui ducimus. Et voluptatem deleniti. Voluptatum voluptatibus amet. Et esse
                                sed omnis inventore hic culpa.</span>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-4">
                        <nav aria-label="breadcrumb" class="breadcrumb-box d-flex justify-content-lg-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                  <a href="{{route('index')}}">Home</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Login
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </section><!-- End Intro Single-->

        <!-- ======= Contact Single ======= -->
        <section class="contact">
            <div class="container">
                <div class="row">
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="col-sm-12 section-t8">
                        <div class="row">
                            <div class="col-md-7 mx-auto d-flex align-items-center border-2">

                                <form action="{{ route('login.post') }}" method="post" role="form">
                                    @csrf <!-- Add CSRF token field -->

                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <div class="form-group">
                                                <input type="email" name="email"
                                                    class="form-control form-control-lg form-control-a" placeholder="Email"
                                                    required value="{{ old('email') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <div class="form-group">
                                                <input type="password" name="password"
                                                    class="form-control form-control-lg form-control-a"
                                                    placeholder="Password" required>
                                            </div>
                                        </div>

                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btn btn-a">Login</button>
                                        </div>
                                    </div>
                                </form>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section><!-- End Contact Single-->

    </main><!-- End #main -->

@endsection
