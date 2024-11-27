<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Ventas</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Estilos personalizados -->
    <style>
        /* Menú Superior */
        .navbar {
            height: 80px; /* Aumentamos la altura del menú superior */
            background: linear-gradient(45deg, #1e3c72, #2a5298); /* Gradiente de color azul */
            border-radius: 0px 0px 15px 15px;
        }

        .navbar-brand {
            font-size: 24px;
            color: #fff;
        }

        .navbar-nav .nav-link {
            color: #fff;
            font-weight: bold;
        }

        .navbar-nav .nav-link:hover {
            color: #f1c40f; /* Color de hover */
        }

        /* Menú Izquierdo */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 320px;
            background-color: #0979b0;
            padding-top: 30px;
            border-radius: 0px 15px 15px 0px;
        }

        .sidebar .btn {
            width: 85%;
            padding: 15px;
            text-align: left;
            border: none;
            background-color: #444;
            color: white;
            font-size: 18px;
            border-radius: 10px;
            margin: 10px 0;
            margin-left: 6%; 
        }

        .sidebar .btn:hover {
            background-color: #555;
            color: #f1c40f; /* Color de hover para los botones */
        }

      

        /* Ajustar contenido a la derecha */
        .content {
            margin-left: 320px;
            padding: 30px;
        }

        /* Fondo de la página */
        body {
            background-color: #f4f6f9;
        }


        /* Clase para la tabla con altura fija */
        .table-scroll {
            height: 300px;  /* Puedes ajustar esta altura según lo que necesites */
            overflow-y: auto; /* Habilita el scroll vertical si el contenido excede la altura */
            display: block;
        }

    </style>
</head>
<body>

    <!-- Menú Superior -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Configuraciones</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Cerrar sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Menú Izquierdo -->
    <div class="sidebar">
    <a class="navbar-brand" href="#">Logo del Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        <button class="btn btn-ventas">Ventas</button>
        <button class="btn btn-clientes">Clientes</button>
        <button class="btn btn-reportes">Reportes</button>
        <button class="btn btn-ventas">Productos</button>
        <button class="btn btn-clientes">Concesionarios</button>
        <button class="btn btn-reportes">Análisis</button>
    </div>

    <!-- Contenido del Dashboard -->
    <div class="content">
        <h2>Bienvenido al Dashboard de Ventas</h2>
        
        <form method="GET" action="{{ route('dashboard') }}">
    <div class="row mb-4">
    <label for="anio" class="form-label">Seleccione filtro</label>
        <!-- Filtro de Año -->
        <div class="col-md-6 mb-3">
            <select name="anio" class="form-select" onchange="this.form.submit()">
                <option value="">Seleccione Año</option>
                @foreach ($anios as $año)
                    <option value="{{ $año->anio }}" {{ request('anio') == $año->anio ? 'selected' : '' }}>{{ $año->anio }}</option>
                @endforeach
            </select>
        </div>

        <!-- Filtro de Mes -->
        <div class="col-md-6 mb-3">
            <select name="mes" class="form-select" onchange="this.form.submit()">
                <option value="">Seleccione Mes</option>
                @foreach ($meses as $mes)
                    <option value="{{ $mes->mes }}" {{ request('mes') == $mes->mes ? 'selected' : '' }}>{{ $mes->mes }}</option>
                @endforeach
            </select>
        </div>
    </div>
</form>

<div class="container mt-4">
    <div class="row">
        <!-- Card 1: Clientes Únicos -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Clientes Únicos
                </div>
                <div class="card-body">
                    <h5 class="card-title text-primary display-4">{{ $clientesUnicos }}</h5>
                </div>
            </div>
        </div>

        <!-- Card 2: Cantidad de Ventas -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    N° de Ventas
                </div>
                <div class="card-body">
                    <h5 class="card-title text-primary display-4">{{ $cantidadVentas }}</h5>
                </div>
            </div>
        </div>

        <!-- Card 3: Ingresos Totales -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    N° de Marcas 
                </div>
                <div class="card-body">
                    <h5 class="card-title text-primary display-4">{{ $cantidadMarcasUnicas }}</h5>
                    
                </div>
            </div>
        </div>

        <!-- Card 4: Clientes por Segmento -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Total de ventas.
                </div>
                <div class="card-body">
                    <h5 class="card-title text-primary display-4">S/. {{ $totalPrecio  }}</h5>
                    
                </div>
            </div>
        </div>
    </div>
</div>



        <!-- Gráficos dentro de Cards -->
<div class="row">

<!-- Card de Gráfico: Ventas por Año -->
<div class="col-md-6 mb-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">Ventas por Año</h5>
        </div>
        <div class="card-body">
            <canvas id="salesByYearChart"></canvas>
        </div>
    </div>
</div>

<!-- Card de Gráfico: Ventas por Marca -->
<div class="col-md-6 mb-4">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h5 class="card-title mb-0">Ventas por Marca</h5>
        </div>
        <div class="card-body">
            <canvas id="salesByBrandChart"></canvas>
        </div>
    </div>
</div>

<!-- Card de Gráfico: Ventas por Región -->
<div class="col-md-6 mb-4">
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
            <h5 class="card-title mb-0">Ventas por Región</h5>
        </div>
        <div class="card-body">
            <canvas id="salesByRegionChart"></canvas>
        </div>
    </div>
</div>

<!-- Card de Gráfico: Top Clientes -->
<div class="col-md-6 mb-4">
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-white">
            <h5 class="card-title mb-0">Top Clientes</h5>
        </div>
        <div class="card-body">
            <canvas id="topCustomersChart"></canvas>
        </div>
    </div>
</div>

<!-- Card de Gráfico: Clientes por Género -->
<div class="col-md-6 mb-4">
    <div class="card shadow-sm">
        <div class="card-header bg-danger text-white">
            <h5 class="card-title mb-0">Clientes por Género</h5>
        </div>
        <div class="card-body">
            <canvas id="clientesChart"></canvas>
        </div>
    </div>
</div>

<div class="col-md-6 mb-4">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h5 class="card-title mb-0">    Top 10 Concesionarios con Más Ventas</h5>
        </div>
        <div class="card-body table-scroll">
        <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nombre del Concesionario</th>
                        <th>Región</th>
                        <th>Total Ventas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($topConcesionarios as $concesionario)
                        <tr>
                            <td>{{ $concesionario->nombre_concesionario }}</td>
                            <td>{{ $concesionario->region }}</td>
                            <td>{{ number_format($concesionario->total_ventas, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- Card de Gráfico: Top Clientes -->
<div class="col-md-6 mb-4">
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-white">
            <h5 class="card-title mb-0">Ventas de Automóviles</h5>
        </div>
        <div class="card-body table-scroll">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Código de Venta</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Vehículo</th>
                    <th>Concesionario</th>
                    <th>Precio</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ventas as $venta)
                    <tr>
                        <td>{{ $venta->codigo_venta }}</td>
                        <td>{{ $venta->fecha }}</td>
                        <td>{{ $venta->cliente }}</td>
                        <td>{{ $venta->vehiculo }}</td>
                        <td>{{ $venta->concesionario }}</td>
                        <td>{{ number_format($venta->precio, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
</div>

</div>

</div>
</div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    // Función para actualizar los gráficos usando los datos proporcionados por el controlador
    function updateCharts(data) {
        const chartConfigs = [
            {
                elementId: 'salesByYearChart',
                type: 'bar',
                label: 'Ventas por Año',
                labels: data.salesByYear.map(item => item.anio),
                dataset: data.salesByYear.map(item => item.total_sales),
            },
            {
                elementId: 'salesByBrandChart',
                type: 'pie',
                label: 'Ventas por Marca',
                labels: data.salesByBrand.map(item => item.marca),
                dataset: data.salesByBrand.map(item => item.total_sales),
            },
            {
                elementId: 'salesByRegionChart',
                type: 'bar',
                label: 'Ventas por Región',
                labels: data.salesByRegion.map(item => item.region),
                dataset: data.salesByRegion.map(item => item.total_sales),
            },
            {
                elementId: 'topCustomersChart',
                type: 'bar',
                label: 'Top Clientes',
                labels: data.topCustomers.map(item => item.nombre_cliente),
                dataset: data.topCustomers.map(item => item.total_sales),
            },
            {
                elementId: 'clientesChart',
                type: 'bar',
                label: 'Clientes por Género',
                labels: data.clientesPorGenero.map(item => item.genero),
                dataset: data.clientesPorGenero.map(item => item.total),
            },
        ];

        // Usa requestAnimationFrame para optimizar la renderización
        const createChart = (config) => {
            requestAnimationFrame(() => {
                new Chart(document.getElementById(config.elementId), {
                    type: config.type,
                    data: {
                        labels: config.labels,
                        datasets: [{
                            label: config.label,
                            data: config.dataset,
                            backgroundColor: ['#FF5733', '#33FF57', '#5733FF'],
                            borderColor: '#fff',
                            borderWidth: 1,
                        }],
                    },
                    options: config.type === 'bar' ? { scales: { y: { beginAtZero: true } } } : {},
                });
            });
        };

        // Crear gráficos progresivamente
        chartConfigs.forEach((config, index) => {
            setTimeout(() => createChart(config), index * 100); // Incrementa el retraso con cada gráfico
        });
    }

    // Ejecutar la carga de los gráficos cuando el DOM esté completamente cargado
    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
            updateCharts({
                salesByYear: @json($salesByYear),
                salesByBrand: @json($salesByBrand),
                salesByRegion: @json($salesByRegion),
                topCustomers: @json($topCustomers),
                clientesPorGenero: @json($clientesPorGenero),
            });
        }, 100); // Retraso para asegurar que los gráficos se carguen después del DOM
    });
</script>

</body>
</html>
