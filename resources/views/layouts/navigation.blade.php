<div id="sidebar-menu">
                        <!-- Left Menu Start -->
                        <ul class="metismenu list-unstyled" id="side-menu">
                            <li class="menu-title" data-key="t-menu">Menu</li>
                            @if (Auth::user()->rol_id == 1 || Auth::user()->rol_id == 2)
                            <li>
                                <a href="{{ route('dashboard') }}">
                                    <i data-feather="home"></i>
                                    <span data-key="t-dashboard">Dashboard</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('pos.index') }}">
                                    <i data-feather="airplay"></i>
                                    <span data-key="t-pos">POS</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('monitor.index') }}">
                                    <i data-feather="airplay"></i>
                                    <span data-key="t-monitor">Monitor</span>
                                </a>
                            </li>

                            <li class="menu-title" data-key="t-apps">Admin</li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow">
                                    <i data-feather="shopping-cart"></i>
                                    <span data-key="productos">Productos</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('productos.index') }}" key="t-products">Lista Productos</a></li>
                                    <li><a href="{{ route('productos.create') }}" data-key="t-product-add">Agregar producto</a></li>
                                    <li><a href="{{ route('productos.viewimport') }}" data-key="t-orders">Importar Productos</a></li>
                                    <li><a href="{{ route('products.allproductpdf') }}" target="_blank">Listado de Productos PDF</a></li>
                                    <li><a href="{{ route('products.exportproduct') }}" target="_blank">Listado de Productos Excel</a></li>
                                    <li><a href="{{ route('products.export') }}" target="_blank">Listado de Neumaticos Internacionales Excel</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="{{ route('kardex.index') }}">
                                    <i data-feather="sliders"></i>
                                    <span data-key="t-pos">Kardex</span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript: void(0);" class="has-arrow">
                                    <i data-feather="list"></i>
                                    <span data-key="categorias">Categorías</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('categorias.index') }}" key="t-categories">Lista Categorías</a></li>
                                    <li><a href="{{ route('categorias.create') }}" data-key="t-categories-add">Agregar Categoría</a></li>
                                    <li><a href="{{ route('categorias.viewimport') }}" data-key="t-categories-viewimport">Importar Categorías</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="javascript: void(0);" class="has-arrow">
                                    <i data-feather="shopping-bag"></i>
                                    <span data-key="ventas">Ventas</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('ventas.index') }}" key="t-sales">Ventas por caja</a></li>
                                    <li><a href="{{ route('ventas.indexmonth') }}" key="t-sales">Ventas por mes</a></li>
                                    <li><a href="{{ route('ventas.indexrange') }}" key="t-sales">Ventas por rango</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="javascript: void(0);" class="has-arrow">
                                    <i data-feather="file-text"></i>
                                    <span data-key="cotizacion">Cotización</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('cotizaciones.index') }}" key="t-Cotizacion">Lista Cotización</a></li>
                                    <li><a href="{{ route('cotizaciones.create') }}" data-key="t-Cotizacion-add">Agregar Cotización</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="javascript: void(0);" class="has-arrow">
                                    <i data-feather="briefcase"></i>
                                    <span data-key="Ordenes de Trabajo">Ordenes de Trabajo</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('ordenes-trabajo.index') }}" key="t-work-orders">Lista Ordenes de Trabajo</a></li>
                                    <li><a href="{{ route('ordenes-trabajo.create') }}" data-key="t-work-orders-add">Agregar OT</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="javascript: void(0);" class="has-arrow">
                                    <i data-feather="dollar-sign"></i>
                                    <span data-key="cotizacion">Compras</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('compras.index') }}" key="t-Compras">Lista Compras</a></li>
                                    <li><a href="{{ route('compras.create') }}" data-key="t-Compras-add">Agregar Compras</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="javascript: void(0);" class="has-arrow">
                                    <i data-feather="credit-card"></i>
                                    <span data-key="Gastos">Gastos</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('gastos.index') }}" key="t-gastos">Lista Gastos</a></li>
                                    <li><a href="{{ route('gastos.create') }}" data-key="t-gastos-add">Agregar Gastos</a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow">
                                    <i data-feather="users"></i>
                                    <span data-key="Clientes">Clientes</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('clientes.index') }}" key="t-products">Lista Clientes</a></li>
                                    <li><a href="{{ route('clientes.create') }}" data-key="t-product-add">Agregar Clientes</a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow">
                                    <i data-feather="user"></i>
                                    <span data-key="proveedores">Proveedores</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('proveedor.index') }}" key="t-products">Lista Proveedores</a></li>
                                    <li><a href="{{ route('proveedor.create') }}" data-key="t-product-add">Agregar Proveedores</a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow">
                                    <i data-feather="file"></i>
                                    <span data-key="reportes">Reportes</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('reportes.informeVentasxdia') }}">Ventas con Propina</a></li>
                                    <li><a href="{{ route('reportes.informeVentasxdiaxproducto') }}">Productos Vendidos por Dia</a></li>
                                    <li><a href="{{ route('reportes.informeProductosVendidos') }}">Productos Vendidos por Dia (Solo Productos)</a></li>
                                    <li><a href="{{ route('reportes.informeNeumaticosInternacionales') }}">Neumáticos Internaciones Vendidos y su Peso</a></li>
                                    <li><a href="{{ route('reportes.informegastos') }}" target="_blank">Informe de Gastos</a></li>
                                    <li><a href="{{ route('products.allproductpdf') }}" target="_blank">Informe de Productos</a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow">
                                    <i data-feather="settings"></i>
                                    <span data-key="Configuraciones">Configuraciones</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('tiendas.edit', 1) }}" key="t-products">Empresa</a></li>
                                    <li><a href="{{ route('users.index') }}" key="t-products">Lista Usuarios</a></li>
                                    <li><a href="{{ route('users.create') }}" data-key="t-product-add">Agregar Usuario</a></li>
                                </ul>
                            </li>
                            @endif
                            @if (Auth::user()->rol_id == 4)
                            <li>
                                <a href="{{ route('dashboard') }}">
                                    <i data-feather="home"></i>
                                    <span data-key="t-dashboard">Dashboard</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('pos.index') }}">
                                    <i data-feather="airplay"></i>
                                    <span data-key="t-pos">POS</span>
                                </a>
                            </li>
                            <li class="menu-title" data-key="t-apps">Admin</li>
                            <li>
                                <a href="javascript: void(0);" class="has-arrow">
                                    <i data-feather="file-text"></i>
                                    <span data-key="cotizacion">Cotización</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('cotizaciones.index') }}" key="t-Cotizacion">Lista Cotización</a></li>
                                    <li><a href="{{ route('cotizaciones.create') }}" data-key="t-Cotizacion-add">Agregar Cotización</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="javascript: void(0);" class="has-arrow">
                                    <i data-feather="briefcase"></i>
                                    <span data-key="Ordenes de Trabajo">Ordenes de Trabajo</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('ordenes-trabajo.index') }}" key="t-work-orders">Lista Ordenes de Trabajo</a></li>
                                    <li><a href="{{ route('ordenes-trabajo.create') }}" data-key="t-work-orders-add">Agregar OT</a></li>
                                </ul>
                            </li>
                            @endif
                        </ul>
                    </div>
