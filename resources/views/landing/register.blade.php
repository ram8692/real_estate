@extends('landing.layouts.main')
@section('content')
    <main id="main">

        <!-- ======= Intro Single ======= -->
        <section class="intro-single">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-lg-8">
                        <div class="title-single-box">
                            <h1 class="title-single">Register</h1>
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
                                    <a href="index.html">Home</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Contact
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
                            <div class="col-md-7 mx-auto d-flex align-items-center">


                                <form action="{{ route('register.post') }}" method="post" enctype="multipart/form-data">
                                    @csrf <!-- Add CSRF token field -->

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <input type="text" name="name"
                                                    class="form-control form-control-lg form-control-a"
                                                    placeholder="Your Name" required value="{{ old('name') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <input name="email" type="email"
                                                    class="form-control form-control-lg form-control-a"
                                                    placeholder="Your Email" required value="{{ old('email') }}">
                                            </div>
                                        </div>

                                        <!-- Add a password field -->
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <input name="password" type="password"
                                                    class="form-control form-control-lg form-control-a"
                                                    placeholder="Your Password" required>
                                            </div>
                                        </div>
                                        <!-- Add a role dropdown -->
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <select name="role_id" class="form-control form-control-lg form-control-a"
                                                    required>
                                                    <option value="2" {{ old('role_id') == 2 ? 'selected' : '' }}>
                                                        Customer</option>
                                                    <!-- Add other role options as needed -->
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label for="profile_image">Upload Profile Picture</label>
                                                <input type="file" class="form-control form-control-lg form-control-a"
                                                    name="profile_image" accept="image/*">
                                            </div>
                                        </div>


                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btn btn-a">Register</button>
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
