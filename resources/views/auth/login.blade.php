@extends('auth.layouts.appauth')

@section('title') {{ __('Login') }} @endsection

@section('content')
<div class="row g-0">
                    <div class="col-xxl-3 col-lg-4 col-md-5">
                        <div class="auth-full-page-content d-flex p-sm-5 p-4">
                            <div class="w-100">
                                <div class="d-flex flex-column h-100">
                                    <div class="mb-md-0 text-center">
                                        <a href="{{ route('home') }}" class="d-block auth-logo">
                                            <img src="{{ asset('') }}/{{ $empresa->logo }}" alt="logo" height="100">
                                            <br>
                                            <span class="logo-txt">POS</span>
                                        </a>
                                    </div>
                                    <div class="auth-content my-auto">
                                        <div class="text-center">
                                            <h5 class="mb-0">Bienvenido!</h5>
                                        </div>

                                        <form class="mt-4 pt-2" method="POST" action="{{ route('login') }}">
                                            @if ($errors->has('email'))
                                                <span class="text-danger ">
                                                    {{ $errors->first('email') }}
                                                </span>
                                            @endif
                                            @if ($errors->has('password'))
                                                <span class="text-danger ">
                                                    {{ $errors->first('password') }}
                                                </span>
                                            @endif
                                            @csrf
                                            <div class="form-floating form-floating-custom mb-4">
                                                <input type="email" class="form-control" id="input-username" name="email"
                                                value="{{ old('email') }}" placeholder="example@example.com">
                                                <label for="input-username">Correo Electrónico</label>
                                                <div class="form-floating-icon">
                                                   <i data-feather="users"></i>
                                                </div>
                                            </div>


                                            <div class="form-floating form-floating-custom mb-4 auth-pass-inputgroup">
                                                <input type="password" name="password" class="form-control pe-5" id="password-input" placeholder="********">

                                                <button type="button" class="btn btn-link position-absolute h-100 end-0 top-0" id="password-addon">
                                                    <i class="mdi mdi-eye-outline font-size-18 text-muted"></i>
                                                </button>
                                                <label for="input-password">Contraseña</label>
                                                <div class="form-floating-icon">
                                                    <i data-feather="lock"></i>
                                                </div>
                                            </div>


                                            <div class="row mb-4">
                                                <div class="col">
                                                    <div class="form-check font-size-15">
                                                        <input class="form-check-input" type="checkbox" name="remember id="remember-check">
                                                        <label class="form-check-label font-size-13" for="remember-check">
                                                            Recuerdame
                                                        </label>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="mb-3">
                                                <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">Ingresar</button>
                                            </div>
                                        </form>

                                    </div>
                                    <div class="mt-4 mt-md-5 text-center">
                                        <p class="mb-0">© <script>document.write(new Date().getFullYear())</script> Rey del Neumático.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end auth full page content -->
                    </div>
                    <!-- end col -->
                    <div class="col-xxl-9 col-lg-8 col-md-7">
                        <div class="auth-bg pt-md-5 p-4 d-flex">
                            <div class="bg-overlay"></div>
                            <ul class="bg-bubbles">
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                            </ul>
                            <!-- end bubble effect -->

                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
@endsection
