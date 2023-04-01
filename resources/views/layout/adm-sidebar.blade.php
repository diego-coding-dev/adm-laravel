<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <img src="{{@asset('img/free_delivery.png')}}" alt="" width="50px;" height="50px;">
            <!--<i class="fas fa-laugh-wink"></i>-->
        </div>
        <!--<div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div>-->
        <div class="sidebar-brand-text mx-3">Free <br> Delivery</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        RH
    </div>

    <!-- Nav Clientes - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" data-toggle="collapse" data-target="#clients" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-minus"></i>
            <span>Clientes</span>
        </a>
        <div id="clients" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!-- <h6 class="collapse-header">Custom Components:</h6> -->
                <a class="collapse-item" href="{{route('client.list-search')}}">Listar</a>
                <a class="collapse-item" href="{{route('client.create')}}">Adicionar</a>
            </div>
        </div>
    </li>

    <!-- Nav FUncionários - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" data-toggle="collapse" data-target="#employees" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-minus"></i>
            <span>Funcionários</span>
        </a>
        <div id="employees" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!-- <h6 class="collapse-header">Custom Components:</h6> -->
                <a class="collapse-item" href="{{route('employee.list-search')}}">Listar</a>
                <a class="collapse-item" href="{{route('employee.create')}}">Adicionar</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Estoque e Produtos
    </div>

    <!-- Nav Categorias de produtos - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" data-toggle="collapse" data-target="#productCategory" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-minus"></i>
            <span>Categoria de produtos</span>
        </a>
        <div id="productCategory" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!-- <h6 class="collapse-header">Custom Components:</h6> -->
                <a class="collapse-item" href="{{route('type-product.list-search')}}">Listar</a>
                <a class="collapse-item" href="{{route('type-product.create')}}">Adicionar</a>
            </div>
        </div>
    </li>

    <!-- Nav Produtos - Utilities Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" data-toggle="collapse" data-target="#products" aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-minus"></i>
            <span>Produtos</span>
        </a>
        <div id="products" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!--<h6 class="collapse-header">Custom Utilities:</h6>-->
                <a class="collapse-item" href="{{route('product.list-search')}}">Listar</a>
                <a class="collapse-item" href="{{route('product.create')}}">Adicionar</a>
            </div>
        </div>
    </li>

    <!-- Nav Produtos - Utilities Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" data-toggle="collapse" data-target="#storage" aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-minus"></i>
            <span>Estoque</span>
        </a>
        <div id="storage" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!--<h6 class="collapse-header">Custom Utilities:</h6>-->
                <a class="collapse-item" href="{{route('storage.list-search')}}">Lista de produtos</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Serviços
    </div>

    <!-- Nav Produtos - Utilities Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" data-toggle="collapse" data-target="#order" aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-minus"></i>
            <span>Pedidos</span>
        </a>
        <div id="order" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!--<h6 class="collapse-header">Custom Utilities:</h6>-->
                <a class="collapse-item" href="{{route('order.list')}}">Lista de pedidos</a>
                <a class="collapse-item" href="{{route('order.searching-client')}}">Registrar pedido</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>


</ul>
<!-- End of Sidebar -->