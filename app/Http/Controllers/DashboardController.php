<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $anio = $request->input('anio');
        $mes = $request->input('mes');

        // Obtener los años y los meses
        $anios = DB::table('ventas_automoviles')
            ->join('dim_fecha', 'ventas_automoviles.id_fecha', '=', 'dim_fecha.id_fecha')
            ->select(DB::raw('YEAR(dim_fecha.fecha) as anio'))
            ->groupBy(DB::raw('YEAR(dim_fecha.fecha)'))
            ->get();

        // Obtener solo los 12 meses (1-12) disponibles en la tabla de hechos
        $meses = DB::table('ventas_automoviles')
        ->join('dim_fecha', 'ventas_automoviles.id_fecha', '=', 'dim_fecha.id_fecha')
        ->select(DB::raw('MONTH(dim_fecha.fecha) as mes'))
        ->distinct()  // Esto asegurará que solo obtengamos los meses únicos
        ->orderBy(DB::raw('MONTH(dim_fecha.fecha)'))  // Ordenamos de enero a diciembre
        ->get();

        // Consultas filtradas por Año y Mes
        $salesByYear = DB::table('ventas_automoviles')
            ->join('dim_fecha', 'ventas_automoviles.id_fecha', '=', 'dim_fecha.id_fecha')
            ->select(DB::raw('YEAR(dim_fecha.fecha) as anio'), DB::raw('SUM(ventas_automoviles.precio) as total_sales'))
            ->when($anio, function ($query, $anio) {
                return $query->whereYear('dim_fecha.fecha', $anio);
            })
            ->groupBy(DB::raw('YEAR(dim_fecha.fecha)'))
            ->get();

        $ventas = DB::table('ventas_automoviles')
        ->join('dim_fecha', 'ventas_automoviles.id_fecha', '=', 'dim_fecha.id_fecha')
        ->join('dim_concesionario', 'ventas_automoviles.id_concesionario', '=', 'dim_concesionario.id_concesionario')
        ->join('dim_cliente', 'ventas_automoviles.id_cliente', '=', 'dim_cliente.id_cliente')  // Aquí se une la tabla cliente
        ->join('dim_vehiculo', 'ventas_automoviles.id_vehiculo', '=', 'dim_vehiculo.id_vehiculo')  // Aquí se une la tabla vehiculo
        ->select(
            'ventas_automoviles.codigo_venta', 
            'ventas_automoviles.precio', 
            'dim_fecha.fecha',  // Se selecciona la fecha de la tabla dim_fecha
            'dim_concesionario.nombre_concesionario as concesionario',  // Se selecciona el nombre del concesionario
            'dim_cliente.nombre_cliente as cliente',  // Se selecciona el nombre del cliente
            'dim_vehiculo.marca as vehiculo'  // Se selecciona el modelo del vehículo
        )
        ->when($anio, function ($query, $anio) {
            return $query->whereYear('dim_fecha.fecha', $anio);
        })
        ->when($mes, function ($query, $mes) {
            return $query->whereMonth('dim_fecha.fecha', $mes);
        })
        ->get();

        $salesByBrand = DB::table('ventas_automoviles')
            ->join('dim_vehiculo', 'ventas_automoviles.id_vehiculo', '=', 'dim_vehiculo.id_vehiculo')
            ->join('dim_fecha', 'ventas_automoviles.id_fecha', '=', 'dim_fecha.id_fecha')
            ->select('dim_vehiculo.marca', DB::raw('SUM(ventas_automoviles.precio) as total_sales'))
            ->when($anio, function ($query, $anio) {
                return $query->whereYear('dim_fecha.fecha', $anio);
            })
            ->when($mes, function ($query, $mes) {
                return $query->whereMonth('dim_fecha.fecha', $mes);
            })
            ->groupBy('dim_vehiculo.marca')
            ->get();

        $salesByRegion = DB::table('ventas_automoviles')
            ->join('dim_concesionario', 'ventas_automoviles.id_concesionario', '=', 'dim_concesionario.id_concesionario')
            ->join('dim_fecha', 'ventas_automoviles.id_fecha', '=', 'dim_fecha.id_fecha')
            ->select('dim_concesionario.region', DB::raw('SUM(ventas_automoviles.precio) as total_sales'))
            ->when($anio, function ($query, $anio) {
                return $query->whereYear('dim_fecha.fecha', $anio);
            })
            ->when($mes, function ($query, $mes) {
                return $query->whereMonth('dim_fecha.fecha', $mes);
            })
            ->groupBy('dim_concesionario.region')
            ->get();

        $topCustomers = DB::table('ventas_automoviles')
            ->join('dim_cliente', 'ventas_automoviles.id_cliente', '=', 'dim_cliente.id_cliente')
            ->join('dim_fecha', 'ventas_automoviles.id_fecha', '=', 'dim_fecha.id_fecha')
            ->select('dim_cliente.nombre_cliente', DB::raw('SUM(ventas_automoviles.precio) as total_sales'))
            ->when($anio, function ($query, $anio) {
                return $query->whereYear('dim_fecha.fecha', $anio);
            })
            ->when($mes, function ($query, $mes) {
                return $query->whereMonth('dim_fecha.fecha', $mes);
            })
            ->groupBy('dim_cliente.nombre_cliente')
            ->orderBy('total_sales', 'desc')
            ->limit(5)
            ->get();

        $clientesPorGenero = DB::table('dim_cliente')
            ->select('genero', DB::raw('COUNT(*) as total'))
            ->join('ventas_automoviles', 'dim_cliente.id_cliente', '=', 'ventas_automoviles.id_cliente')
            ->join('dim_fecha', 'ventas_automoviles.id_fecha', '=', 'dim_fecha.id_fecha')
            ->when($anio, function ($query, $anio) {
                return $query->whereYear('dim_fecha.fecha', $anio);
            })
            ->when($mes, function ($query, $mes) {
                return $query->whereMonth('dim_fecha.fecha', $mes);
            })
            ->groupBy('genero')
            ->get();

        // Consulta para obtener la cantidad de clientes únicos
        $clientesUnicosQuery = DB::table('dim_cliente')
            ->join('ventas_automoviles', 'dim_cliente.id_cliente', '=', 'ventas_automoviles.id_cliente')
            ->join('dim_fecha', 'ventas_automoviles.id_fecha', '=', 'dim_fecha.id_fecha')
            ->select('dim_cliente.id_cliente')
            ->distinct();

        // Si se aplica un filtro de año, agregamos la condición
        if ($anio) {
            $clientesUnicosQuery->whereYear('dim_fecha.fecha', $anio);
        }

        // Si se aplica un filtro de mes, agregamos la condición
        if ($mes) {
            $clientesUnicosQuery->whereMonth('dim_fecha.fecha', $mes);
        }

        $clientesUnicos = $clientesUnicosQuery->count();


        // Consulta para obtener la cantidad de ventas
        $ventasQuery = DB::table('ventas_automoviles')
            ->join('dim_fecha', 'ventas_automoviles.id_fecha', '=', 'dim_fecha.id_fecha')
            ->select(DB::raw('COUNT(ventas_automoviles.id_venta) as cantidad_ventas'));

        // Filtros para ventas: año y mes
        if ($anio) {
            $ventasQuery->whereYear('dim_fecha.fecha', $anio);
        }

        if ($mes) {
            $ventasQuery->whereMonth('dim_fecha.fecha', $mes);
        }

        $cantidadVentas = $ventasQuery->value('cantidad_ventas');

        // Consulta para obtener la cantidad de marcas únicas
        $marcasUnicasQuery = DB::table('dim_vehiculo')
            ->join('ventas_automoviles', 'dim_vehiculo.id_vehiculo', '=', 'ventas_automoviles.id_vehiculo')
            ->join('dim_fecha', 'ventas_automoviles.id_fecha', '=', 'dim_fecha.id_fecha')
            ->select('dim_vehiculo.marca')
            ->distinct();

        // Filtros para marcas: año y mes
        if ($anio) {
            $marcasUnicasQuery->whereYear('dim_fecha.fecha', $anio);
        }

        if ($mes) {
            $marcasUnicasQuery->whereMonth('dim_fecha.fecha', $mes);
        }

        $cantidadMarcasUnicas = $marcasUnicasQuery->count();

        // Consulta para obtener la suma de precios con filtros de año y mes
        $sumaPrecios = DB::table('ventas_automoviles')
        ->join('dim_vehiculo', 'dim_vehiculo.id_vehiculo', '=', 'ventas_automoviles.id_vehiculo')
        ->join('dim_fecha', 'ventas_automoviles.id_fecha', '=', 'dim_fecha.id_fecha')
        ->select(DB::raw('SUM(ventas_automoviles.precio) as total_precio'));

        // Aplicar filtro de año si se proporciona
        if ($anio) {
        $sumaPrecios->whereYear('dim_fecha.fecha', $anio);
        }

        // Aplicar filtro de mes si se proporciona
        if ($mes) {
        $sumaPrecios->whereMonth('dim_fecha.fecha', $mes);
        }

        // Ejecutar la consulta y obtener el resultado
        $sumaPrecios = $sumaPrecios->first();

        // Si la consulta devuelve un valor, se accede al resultado
        $totalPrecio = $sumaPrecios ? $sumaPrecios->total_precio : 0;


        // Consulta para obtener los concesionarios con los filtros aplicados
        $topConcesionarios = DB::table('ventas_automoviles')
        ->join('dim_concesionario', 'ventas_automoviles.id_concesionario', '=', 'dim_concesionario.id_concesionario')
        ->select('dim_concesionario.nombre_concesionario', 'dim_concesionario.region', DB::raw('SUM(ventas_automoviles.precio) as total_ventas'))
        ->when($anio, function ($query) use ($anio) {
            return $query->join('dim_fecha', 'ventas_automoviles.id_fecha', '=', 'dim_fecha.id_fecha')
                ->whereYear('dim_fecha.fecha', $anio);  // Filtrar por Año
        })
        ->when($mes, function ($query) use ($mes) {
            return $query->join('dim_fecha', 'ventas_automoviles.id_fecha', '=', 'dim_fecha.id_fecha')
                ->whereMonth('dim_fecha.fecha', $mes);  // Filtrar por Mes
        })
        ->groupBy('dim_concesionario.nombre_concesionario', 'dim_concesionario.region')
        ->orderByDesc('total_ventas')
        ->limit(10)
        ->get();

        return view('dashboard', compact('anios', 'meses','totalPrecio', 'salesByYear', 'salesByBrand', 'salesByRegion', 'topCustomers', 'ventas', 'clientesPorGenero','topConcesionarios','cantidadVentas','clientesUnicos','cantidadMarcasUnicas'));
        
        
    
    }
}
