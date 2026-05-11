<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Client;
use App\Models\Venta;
use App\Models\WeekClientSell;
use App\Models\WeekSell;
use App\Models\Bill;
use App\Models\Box;
use App\Models\Pay;
use App\Models\Discount;
use App\Models\DeliveryPoint;
use App\Models\ClientAddress;
use App\Models\Delivery;
use Carbon\Carbon;

class ApiController extends Controller
{
    //ROUTES FOR CLIENTS
    public function addClient(Request $request)
    {
        $status = $this->verifyClient($request->phone, $request->name);

        return response()->json($status);
    }

    public function verifyClient($phone, $name)
    {
        $clientNumber = Client::where('phone', $phone)->get();
        if (count($clientNumber) > 0) {
            return ["status" => 'replied', "user" => $clientNumber];
        } else {
            $client = ["name" => $name, "phone" => $phone];
            $cl = Client::create($client);
            return ["status" => 'success', "user" => $cl];
        }
        $client = ["name" => $name, "phone" => $phone];
        $cl = Client::create($client);
        return ["status" => 'success', "user" => $cl];
    }

    public function getClients()
    {
        $clients = Client::all();

        return response()->json($clients);
    }

    public function getRaffleParticipants(Request $request)
    {
        $mode = $request->has('mode') ? $request->mode : 'classic';
        $minAmount = $request->has('min_amount') ? floatval($request->min_amount) : 0;
        
        $query = Client::query();
        $clients = $query->get();
        $eligibleClients = [];

        if ($mode === 'loyalty') {
            // Últimas 5 semanas
            $last5Weeks = WeekSell::orderBy('id', 'desc')->take(5)->pluck('id')->toArray();
            // Últimas 2 semanas (están incluidas en las 5, pero tomamos las 2 más recientes)
            $last2Weeks = WeekSell::orderBy('id', 'desc')->take(2)->pluck('id')->toArray();

            foreach ($clients as $client) {
                if ($client->phone == '1111' || $client->phone == '2222') continue;

                $purchasesInLast5 = Venta::where('id_client', $client->id)->whereIn('id_week', $last5Weeks)->distinct('id_week')->count('id_week');
                $purchasesInLast2 = Venta::where('id_client', $client->id)->whereIn('id_week', $last2Weeks)->distinct('id_week')->count('id_week');

                if ($purchasesInLast5 >= 3 && $purchasesInLast2 >= 1) {
                    $totalSpent = Venta::where('id_client', $client->id)->whereIn('id_week', $last5Weeks)->sum('price');
                    
                    if ($totalSpent >= $minAmount) {
                        $client->total_purchases_count = $purchasesInLast5;
                        $client->total_amount_spent = $totalSpent;
                        $eligibleClients[] = $client;
                    }
                }
            }

        } elseif ($mode === 'tickets') {
            // Semana seleccionada o la actual si no se selecciona nada
            $hasWeeksFilter = $request->has('weeks') && is_array($request->weeks) && count($request->weeks) > 0;
            
            if ($hasWeeksFilter) {
                $targetWeeks = $request->weeks;
            } else {
                $currentWeek = WeekSell::orderBy('id', 'desc')->first();
                $targetWeeks = $currentWeek ? [$currentWeek->id] : [];
            }

            if (count($targetWeeks) > 0) {
                foreach ($clients as $client) {
                    if ($client->phone == '1111' || $client->phone == '2222') continue;

                    $totalSpent = Venta::where('id_client', $client->id)->whereIn('id_week', $targetWeeks)->sum('price');
                    $tickets = floor($totalSpent / 1000);

                    if ($tickets >= 1) {
                        for ($i = 1; $i <= $tickets; $i++) {
                            // Clonamos el cliente para que tenga un nombre con el número de boleto
                            $clientClone = clone $client;
                            $name = $clientClone->name && trim($clientClone->name) !== '' && $clientClone->name !== 'null' ? $clientClone->name : 'N/A';
                            
                            if ($tickets > 1) {
                                $clientClone->name = $name . ' (Boleto ' . $i . ' de ' . $tickets . ')';
                            } else {
                                $clientClone->name = $name;
                            }
                            
                            $clientClone->total_purchases_count = Venta::where('id_client', $client->id)->whereIn('id_week', $targetWeeks)->count();
                            $clientClone->total_amount_spent = $totalSpent;
                            $eligibleClients[] = $clientClone;
                        }
                    }
                }
            }

        } else {
            // MODO CLASSIC
            $hasWeeksFilter = $request->has('weeks') && is_array($request->weeks) && count($request->weeks) > 0;
            $minPurchases = $request->has('min_purchases') ? intval($request->min_purchases) : 0;

            foreach ($clients as $client) {
                if ($client->phone == '1111' || $client->phone == '2222') continue;

                $clientSalesQuery = Venta::where('id_client', $client->id);

                if ($hasWeeksFilter) {
                    $clientSalesQuery->whereIn('id_week', $request->weeks);
                }

                $ventas = $clientSalesQuery->get();
                $purchasesCount = $ventas->count();
                $totalAmount = $ventas->sum('price');

                if ($hasWeeksFilter && $purchasesCount == 0) {
                    continue;
                }

                if (!$hasWeeksFilter && $purchasesCount == 0 && $minPurchases == 0 && $minAmount == 0) {
                    continue;
                }

                if ($purchasesCount >= $minPurchases && $totalAmount >= $minAmount) {
                    $client->total_purchases_count = $purchasesCount;
                    $client->total_amount_spent = $totalAmount;
                    $eligibleClients[] = $client;
                }
            }
        }

        return response()->json($eligibleClients);
    }

    //ROUTES for Pays

    public function addSell(Request $request)
    {
        $client = Client::where('phone', $request->phone)->first();
        if ($client == null) {

            $client = ["name" => $request->name, "phone" => $request->phone];
            $client = Client::create($client);
            $pay = [
                'id_client' => $client->id,
                'product' => $request->product,
                'description' => $request->description,
                'price' => $request->price,
                'id_week' => $request->id_week,
            ];
        } else {
            $pay = [
                'id_client' => $client->id,
                'product' => $request->product,
                'description' => $request->description,
                'price' => $request->price,
                'id_week' => $request->id_week,
            ];

        }


        $payed = Venta::create($pay);

        if (request()->payedB == 1) {
            //Código realizar pago completo

            $py = [
                'id_client' => $client->id,
                'id_week' => $request->id_week,
                'id_box' => $request->id_box,
                'pay' => $request->price,
                'concept' => 'Pagado en el momento',
            ];

            $pay = Pay::create($py);
        }

        return ["status" => "saved", "pay" => $payed];
    }

    public function getSells(Request $request)
    {
        $sells = Venta::where('id_week', $request->id_week)->get();

        foreach ($sells as $sell) {
            $client = Client::where('id', $sell->id_client)->first();
            $pays = Pay::where('id_week', $request->id_week)->where('id_client', $sell->id_client)->get();

            $date = new Carbon($sell->created_at);

            $sell['pays'] = $pays;
            $sell['date'] = $date->format('d-m-Y');
            $sell['phone'] = $client->phone ?? 'N/A';
            $sell['client_name'] = $client->name ?? 'N/A';
        }

        return response()->json($sells);
    }

    //ROUTES FOR PAYS
    public function addPay(Request $request)
    {
        $num = intval($request->phone);
        $client = Client::where('phone', $num)->first();
        $py = [
            'id_client' => $client->id,
            'id_week' => $request->id_week,
            'id_box' => $request->id_box,
            'pay' => $request->pay,
            'concept' => $request->concept,
        ];

        $pay = Pay::create($py);

        return ["status" => "saved"];
    }

    public function getPaysWeek(Request $request)
    {
        $weeks = Pay::where('id_week', $request->id_week)->get();
        foreach ($weeks as $week) {
            $client = Client::where('id', $week->id_client)->first();
            $week['phone'] = $client->phone ?? 'N/A';
            $date = new Carbon($week->created_at);
            $week['date'] = $date->format('d-m-Y');
        }
        return response()->json($weeks);
    }



    //ROUTES for weeks
    public function addWeek(Request $request)
    {

        $wk = [
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ];

        $week = WeekSell::create($wk);

        return ["status" => "saved"];
    }

    public function getWeeks()
    {
        $weeks = WeekSell::all();

        return response()->json($weeks);
    }

    public function getWeek(Request $request)
    {
        $weeks = WeekSell::where('id', $request->id)->first();

        return response()->json($weeks);
    }


    //ROUTES FOR BOXES

    public function getBoxes(Request $request)
    {
        $boxes = Box::all();

        return ["boxes" => $boxes];
    }

    public function getBox(Request $request)
    {
        $box = Box::where('id', $request->id)->first();

        $pays = Pay::where('id_box', $request->id)->get();
        $bills = Bill::where('id_box', $request->id)->get();

        foreach ($pays as $pay) {
            $client = Client::where('id', $pay->id_client)->first();
            $date = new Carbon($pay->created_at);

            $pay["client"] = $client->name ?? '';
            $pay["phone"] = $client->phone ?? '';
            $pay['date'] = $date->format('d-m-Y');
        }

        foreach ($bills as $bill) {
            $date = new Carbon($pay->created_at);
            $bill['date'] = $date->format('d-m-Y');
        }

        $box['pays'] = $pays;
        $box['bills'] = $bills;



        return response()->json($box);
    }

    //Bills
    public function addBill(Request $request)
    {
        $bill = [
            'id_box' => $request->id_box,
            'bill' => $request->bill,
            'concept' => $request->concept,
        ];

        $bl = Bill::create($bill);

        return ['status' => 'saved'];
    }


    //Credits
    public function credits(Request $request)
    {
        $clients = Client::all();
        $totalDeuda = 0;

        foreach ($clients as $client) {
            if ($client->phone != '1111' && $client->phone != '2222') {
                $deuda = 0;
                $pagos = 0;
                $descuentos = 0;
                $restante = 0;

                $pays = Pay::where('id_client', $client->id)->get();
                $sells = Venta::where('id_client', $client->id)->get();
                $descs = Discount::where('id_client', $client->id)->get();

                foreach ($pays as $pay) {
                    $pagos += $pay->pay;
                }
                foreach ($sells as $sell) {
                    $deuda += $sell->price;
                }
                foreach ($descs as $d) {
                    $descuentos += $d->discount;
                }

                // deuda neta considerando descuentos
                $restante = $deuda - $pagos - $descuentos;

                $client['deuda'] = $deuda;
                $client['pagos'] = $pagos;
                $client['descuentos'] = $descuentos;
                $client['restante'] = $restante;

                $totalDeuda += $restante;
            }
        }

        $boxes = Box::all();
        $balanceCajas = 0;

        foreach ($boxes as $box) {
            $pays = Pay::where('id_box', $box->id)->get();
            $bills = Bill::where('id_box', $box->id)->get();
            $pagos = 0;
            $gastos = 0;

            foreach ($pays as $pay) {
                $pagos += $pay->pay;
            }
            foreach ($bills as $bill) {
                $gastos += $bill->bill;
            }

            $balance = $pagos - $gastos;
            $balanceCajas += $balance;

            $box['balance'] = $balance;
        }

        $credits = [];
        $credits['balanceTotal'] = $totalDeuda + $balanceCajas;
        $credits['boxes'] = $boxes;
        $credits['clients'] = $clients;
        $credits['credito'] = $totalDeuda;   // deuda neta (incluye descuentos)
        $credits['totalCredito'] = $totalDeuda;   // lo mismo

        return response()->json($credits);
    }


    public function deleteVenta(Request $request)
    {
        $venta = Venta::where('id', $request->id)->delete();

        return ['status' => 'success'];
    }

    public function deletePago(Request $request)
    {
        $pay = Pay::where('id', $request->id)->delete();

        return ['status' => 'success'];
    }


    public function seeDetails(Request $request)
    {
        // id del cliente
        $id = $request->id;

        $pays = Pay::where('id_client', $id)->get();
        foreach ($pays as $pay) {
            $box = Box::where('id', $pay->id_box)->first();
            $pay['box_name'] = $box ? $box->name : 'N/A';
        }

        $ventas = Venta::where('id_client', $id)->get();
        $discounts = Discount::where('id_client', $id)->get();
        $cliente = Client::where('id', $id)->first();

        // Cálculos
        $totalVentas = $ventas->sum('price');
        $totalPagos = $pays->sum('pay');
        $totalDiscount = $discounts->sum('discount');
        $restante = $totalVentas - $totalPagos - $totalDiscount;

        // Adjuntamos info calculada al cliente
        $cliente['total'] = $totalVentas;
        $cliente['pagos'] = $totalPagos;
        $cliente['descuentos'] = $totalDiscount;
        $cliente['restante'] = $restante;

        $data = [
            'pagos' => $pays,
            'ventas' => $ventas,
            'discounts' => $discounts,
            'cliente' => $cliente,
        ];

        return response()->json($data);
    }

    // DISCOUNTS
    public function addDiscount(Request $request)
    {
        $discount = Discount::create([
            'id_client' => $request->id_client,
            'discount' => $request->discount,
            'concept' => $request->concept,
        ]);

        return response()->json([
            'status' => 'saved',
            'discount' => $discount,
        ]);
    }

    public function deleteDiscount(Request $request)
    {
        Discount::where('id', $request->id)->delete();

        return ['status' => 'success'];
    }

    public function deleteBill(Request $request)
    {
        Bill::where('id', $request->id)->delete();
        return ['status' => 'success'];
    }

    public function financeDashboard(Request $request)
    {
        // Totales globales
        $totalSalesGlobal = Venta::sum('price');
        $totalPaysGlobal = Pay::sum('pay');
        $totalBillsGlobal = Bill::sum('bill');

        // Procesar semanas (Comparativas)
        $sem = WeekSell::all();
        $weeksData = [];
        foreach ($sem as $w) {
            $ventasSemana = Venta::where('id_week', $w->id)->sum('price');
            $pagosSemana = Pay::where('id_week', $w->id)->sum('pay');

            // Los gastos (Bills) no tienen id_week actualmente.
            // Trataremos de filtrar los gastos por el created_at usando el start_date y end_date de la semana si existen.
            $gastosSemana = 0;
            if ($w->id == 1) {
                // Semana general (id 1) no lleva gastos
                $gastosSemana = 0;
            } else {
                if ($w->start_date && $w->end_date) {
                    $start = Carbon::parse($w->start_date)->startOfDay();
                    $end = Carbon::parse($w->end_date)->endOfDay();

                    $gastosSemana = Bill::whereBetween('created_at', [$start, $end])->sum('bill');
                }
            }

            $weeksData[] = [
                'id' => $w->id,
                'name' => $w->name,
                'start_date' => $w->start_date,
                'end_date' => $w->end_date,
                'ventas' => $ventasSemana,
                'pagos' => $pagosSemana,
                'gastos' => $gastosSemana,
                // Balance utilitario de la semana (Pagos recibidos menos gastos de la semana)
                'balance' => $pagosSemana - $gastosSemana
            ];
        }

        // Top Compradores (Calculado como en Credits)
        $clients = Client::all();
        $topClientsData = [];
        $totalDebtGlobal = 0; // NEW: acumulador de la deuda pendiente global

        foreach ($clients as $client) {
            if ($client->phone != '1111' && $client->phone != '2222') {
                $deuda = 0;
                $pagos = 0;
                $descuentos = 0;

                // Todas las transacciones globales para la deuda restante
                $pays = Pay::where('id_client', $client->id)->get();
                $sells = Venta::where('id_client', $client->id)->get();
                $descs = Discount::where('id_client', $client->id)->get();

                foreach ($pays as $pay) {
                    $pagos += $pay->pay;
                }
                foreach ($sells as $sell) {
                    $deuda += $sell->price;
                }
                foreach ($descs as $d) {
                    $descuentos += $d->discount;
                }

                $restante = $deuda - $pagos - $descuentos;
                $totalDebtGlobal += $restante; // Acumulando lo pendiente de la persona (global siempre)

                // Filtro para total comprado:
                // Si no hay id_week, usamos el $deuda (comprado histórico)
                // Si hay id_week, sumamos SOLO las ventas de esa semana para rankear y mostrar en Frontend
                $totalCompradoFiltrado = 0;

                if ($request->has('id_week') && $request->id_week != null) {
                    $ventasFiltradas = Venta::where('id_client', $client->id)->where('id_week', $request->id_week)->get();
                    foreach ($ventasFiltradas as $v) {
                        $totalCompradoFiltrado += $v->price;
                    }
                } else {
                    $totalCompradoFiltrado = $deuda;
                }

                // Solo incluimos a los que han comprado (tienen un histórico > 0)
                // y en el caso de filtrar, que hayan comprado esa semana específica
                if ($totalCompradoFiltrado > 0) {
                    $topClientsData[] = [
                        'id' => $client->id,
                        'name' => $client->name,
                        'phone' => $client->phone,
                        'total_comprado' => $totalCompradoFiltrado, // Valor dinámico
                        'total_pagado' => $pagos,
                        'restante' => $restante
                    ];
                }
            }
        }

        // Ordenar de mayor a menor según el "total_comprado"
        usort($topClientsData, function ($a, $b) {
            return $b['total_comprado'] <=> $a['total_comprado'];
        });

        // Limitar a top 15 para Dashboard (opcional)
        $topClientsData = array_slice($topClientsData, 0, 15);

        // Desglose por Cajas
        $boxes = Box::all();
        $boxesData = [];

        foreach ($boxes as $box) {
            $pays = Pay::where('id_box', $box->id)->get();
            $bills = Bill::where('id_box', $box->id)->get();

            $pagos = 0;
            $gastos = 0;

            foreach ($pays as $p) {
                $pagos += $p->pay;
            }
            foreach ($bills as $b) {
                $gastos += $b->bill;
            }

            $balance = $pagos - $gastos;

            $boxesData[] = [
                'id' => $box->id,
                'name' => $box->name,
                'pagos' => $pagos,
                'gastos' => $gastos,
                'balance' => $balance
            ];
        }

        return response()->json([
            'globals' => [
                'total_sales' => $totalSalesGlobal,
                'total_pays' => $totalPaysGlobal,
                'total_bills' => $totalBillsGlobal,
                'balance' => $totalPaysGlobal - $totalBillsGlobal,
                'total_debt' => $totalDebtGlobal // NEW: Deuda global
            ],
            'weeks' => $weeksData,
            'top_clients' => $topClientsData,
            'boxes' => $boxesData // NEW: Desglose de Cajas
        ]);
    }

    // --- DELIVERY POINTS ---
    public function getDeliveryPoints()
    {
        return response()->json(DeliveryPoint::all());
    }

    public function addDeliveryPoint(Request $request)
    {
        $dp = DeliveryPoint::create([
            'name' => $request->name,
            'google_maps_url' => $request->google_maps_url
        ]);
        return ['status' => 'saved', 'point' => $dp];
    }

    public function deleteDeliveryPoint(Request $request)
    {
        DeliveryPoint::where('id', $request->id)->delete();
        return ['status' => 'success'];
    }

    // --- CLIENT ADDRESSES ---
    public function getClientAddresses(Request $request)
    {
        $addresses = ClientAddress::where('id_client', $request->id_client)->get();
        return response()->json($addresses);
    }

    public function addClientAddress(Request $request)
    {
        $address = ClientAddress::create([
            'id_client' => $request->id_client,
            'alias' => $request->alias,
            'type' => $request->type ?? 'local',
            'address_details' => $request->address_details,
            'google_maps_url' => $request->google_maps_url
        ]);
        return ['status' => 'saved', 'address' => $address];
    }

    public function updateClientAddress(Request $request)
    {
        $address = ClientAddress::find($request->id);
        if ($address) {
            if ($request->has('address_details')) {
                $address->address_details = $request->address_details;
            }
            if ($request->has('google_maps_url')) {
                $address->google_maps_url = $request->google_maps_url;
            }
            $address->save();
            return ['status' => 'success'];
        }
        return ['status' => 'error'];
    }

    // --- DELIVERIES ---
    public function getDeliveries(Request $request)
    {
        if ($request->has('id_week')) {
            $deliveries = Delivery::where('id_week', $request->id_week)->orderBy('created_at', 'desc')->get();
        } else {
            $deliveries = Delivery::orderBy('created_at', 'desc')->get();
        }

        foreach ($deliveries as $d) {
            $client = Client::find($d->id_client);
            $d->client_name = $client ? $client->name : 'N/A';
            $d->client_phone = $client ? $client->phone : 'N/A';
            if ($d->id_address) {
                $addr = ClientAddress::find($d->id_address);
                $d->address = $addr;
            }
        }

        return response()->json($deliveries);
    }

    public function addDelivery(Request $request)
    {
        $delivery = Delivery::create([
            'id_client' => $request->id_client,
            'id_week' => $request->id_week,
            'id_address' => $request->id_address,
            'delivery_type' => $request->delivery_type ?? 'local',
            'delivery_day' => $request->delivery_day,
            'notes' => $request->notes,
            'delivery_status' => 'Por entregar',
            'payment_status' => 'Revisando y no pagado'
        ]);

        return ['status' => 'saved', 'delivery' => $delivery];
    }

    public function updateDeliveryStatus(Request $request)
    {
        $delivery = Delivery::find($request->id);
        if ($delivery) {
            if ($request->has('delivery_status')) {
                $delivery->delivery_status = $request->delivery_status;
            }
            if ($request->has('payment_status')) {
                $delivery->payment_status = $request->payment_status;
            }
            if ($request->has('tracking_number')) {
                $delivery->tracking_number = $request->tracking_number;
            }
            if ($request->has('notes')) {
                $delivery->notes = $request->notes;
            }
            if ($request->has('delivery_day')) {
                $delivery->delivery_day = $request->delivery_day;
            }
            $delivery->save();
        }
        return ['status' => 'success'];
    }

    // --- PORTAL CLIENTE ---
    public function checkDeliveryPortal(Request $request)
    {
        $client = Client::where('phone', $request->phone)->first();
        if (!$client) {
            return response()->json(['status' => 'not_found', 'message' => 'No encontramos registro con ese número.']);
        }

        // Obtener la semana activa de interés (la última o la enviada)
        $week = null;
        if ($request->has('id_week')) {
            $week = WeekSell::find($request->id_week);
        } else {
            $week = WeekSell::orderBy('id', 'desc')->first();
        }

        if (!$week) {
            return response()->json(['status' => 'no_weeks']);
        }

        // Ventas del cliente en esta semana particular
        $ventas_detalles = Venta::where('id_client', $client->id)->where('id_week', $week->id)->get();
        $ventas = $ventas_detalles->sum('price');

        // Pagos del cliente correspondientes a esta semana
        $pagos_detalles = \App\Models\Pay::where('id_client', $client->id)->where('id_week', $week->id)->get();
        $pagos = $pagos_detalles->sum('pay');

        $restante = $ventas - $pagos;

        // Entregas activas para esta semana
        $delivery = Delivery::where('id_client', $client->id)->where('id_week', $week->id)->first();
        if ($delivery && $delivery->id_address) {
            $addr = ClientAddress::find($delivery->id_address);
            $delivery->address = $addr;
        }

        return response()->json([
            'status' => 'success',
            'client' => $client,
            'current_week' => $week,
            'total_week_purchases' => $ventas,
            'purchases_details' => $ventas_detalles,
            'total_week_payments' => $pagos,
            'payments_details' => $pagos_detalles,
            'week_debt' => $restante,
            'delivery_info' => $delivery
        ]);
    }
}
