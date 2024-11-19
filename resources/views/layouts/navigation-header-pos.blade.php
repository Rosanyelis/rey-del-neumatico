
                    <div class="d-flex">
                        <div class="navbar-brand-box">
                            <a href="{{ route('dashboard') }}" class="logo logo-dark">
                                <img src="{{ asset('') }}/{{ $empresa->logo }}" alt="logo" height="73"> <span class="logo-txt"></span>
                            </a>

                            <a href="{{ route('dashboard') }}" class="logo logo-light">
                                <img src="{{ asset('') }}/{{ $empresa->logo }}" alt="logo" height="73"> <span class="logo-txt"></span>
                            </a>
                        </div>
                    </div>

                    <div class="d-flex">

                        <div class="dropdown d-inline-block">
                            <button type="button" class="btn header-item noti-icon position-relative" id="page-header-notifications-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i data-feather="bell" class="icon-lg"></i>
                                <span class="badge bg-danger rounded-pill">{{ count($productsQty) }}</span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                                aria-labelledby="page-header-notifications-dropdown">
                                <div class="p-3">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="m-0"> Productos sin posible Stock </h6>
                                        </div>
                                        <div class="col-auto">
                                            <!-- <a href="#!" class="small text-reset text-decoration-underline"> Unread (3)</a> -->
                                        </div>
                                    </div>
                                </div>
                                <div data-simplebar style="max-height: 230px;">
                                    @foreach ($productsQty as $item)
                                    <a href="#!" class="text-reset notification-item">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <img src="{{ asset('assets/images/alert.png') }}" class="rounded-circle avatar-sm" alt="user-pic">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">Producto "<em>{{ $item->name }}</em>" Agotado</h6>
                                                <div class="font-size-13 text-muted">
                                                    <p class="mb-1">Por favor, reponga el inventario, ya que el stock es de <em>{{ $item->quantity }}</em>.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    @endforeach
                                </div>
                                <div class="p-2 border-top d-grid">
                                    <!-- <a class="btn btn-sm btn-link font-size-14 text-center" href="{{ route('productos.index') }}">
                                        <i class="mdi mdi-arrow-right-circle me-1"></i> <span>Notificar al Adminis</span>
                                    </a> -->
                                </div>
                            </div>
                        </div>

                        <div class="dropdown d-inline-block">
                            <button type="button" class="btn header-item bg-light-subtle border-start border-end" id="page-header-user-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="rounded-circle header-profile-user"> <i class="fas fa-user"></i></span>

                                <span class="d-none d-xl-inline-block ms-1 fw-medium">{{ Auth::user()->name }}</span>
                                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                        <i class="mdi mdi-logout font-size-16 align-middle me-1"></i>
                                        Desconectar
                                    </a>
                                </form>
                            </div>
                        </div>

                    </div>

