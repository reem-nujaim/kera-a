<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RentalRequest;
use App\Models\Bill;
use App\Models\User;
use Illuminate\Support\Facades\App;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    // public function getReport($type)
    // {
    //     switch ($type) {
    //         case 'payments':
    //             $data = [
    //                 'payments' => Bill::selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total')
    //                     ->groupBy('payment_method')->get(),
    //                 'monthlyPayments' => Bill::selectRaw('MONTH(created_at) as month, SUM(amount) as total')
    //                     ->groupBy('month')->orderBy('month')->get()
    //             ];
    //             $view = 'reports.partials.payments';
    //             break;

    //         case 'orders':
    //             $data = [
    //                 'orders' => RentalRequest::selectRaw('status, COUNT(*) as count')->groupBy('status')->get(),
    //                 'topItems' => RentalRequest::selectRaw('item_id, COUNT(*) as count')
    //                     ->groupBy('item_id')->orderByDesc('count')->with('item')->limit(5)->get(),
    //                 'ordersPerCustomer' => RentalRequest::selectRaw('user_id, COUNT(*) as count')
    //                     ->groupBy('user_id')->with('user')->get()
    //             ];
    //             $view = 'reports.partials.orders';
    //             break;

    //             case 'customers':
    //                 $data = [
    //                     'newCustomers' => User::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
    //                         ->groupBy('month')->orderBy('month')->get(),

    //                     'topSpenders' => Bill::selectRaw('rental_requests.user_id, SUM(bills.amount) as total_spent')
    //                         ->join('rental_requests', 'bills.rental_request_id', '=', 'rental_requests.id')
    //                         ->groupBy('rental_requests.user_id')
    //                         ->with(['rental_request.user']) // استخدام العلاقة الصحيحة مع rental_request
    //                         ->orderByDesc('total_spent')
    //                         ->limit(5)
    //                         ->get()
    //                 ];
    //                 $view = 'reports.partials.customers';
    //                 break;


    //         default:
    //             return response()->json(['message' => 'نوع التقرير غير معروف'], 400);
    //     }

    //     return view($view, $data);
    // }

    public function getReport($type, Request $request)
    {
        $lang = $request->query('lang', App::getLocale()); // تحديد اللغة الحالية
        App::setLocale($lang); // ضبط اللغة في التطبيق

        switch ($type) {
            case 'payments':
                $data = [
                    'payments' => Bill::selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total')
                        ->groupBy('payment_method')->get(),
                    'monthlyPayments' => Bill::selectRaw('MONTH(created_at) as month, SUM(amount) as total')
                        ->groupBy('month')->orderBy('month')->get()
                ];
                $view = 'reports.partials.payments';
                break;

            case 'orders':
                $data = [
                    'orders' => RentalRequest::selectRaw('status, COUNT(*) as count')->groupBy('status')->get(),
                    'topItems' => RentalRequest::selectRaw('item_id, COUNT(*) as count')
                        ->groupBy('item_id')->orderByDesc('count')->with('item')->limit(5)->get(),
                    'ordersPerCustomer' => RentalRequest::selectRaw('user_id, COUNT(*) as count')
                        ->groupBy('user_id')->with('user')->get()
                ];
                $view = 'reports.partials.orders';
                break;

                case 'customers':
                    $data = [
                        'newCustomers' => User::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                            ->groupBy('month')->orderBy('month')->get(),

                        'topSpenders' => Bill::selectRaw('rental_requests.user_id, SUM(bills.amount) as total_spent')
                            ->leftJoin('rental_requests', 'bills.rental_request_id', '=', 'rental_requests.id') // استخدام leftJoin بدلاً من join
                            ->groupBy('rental_requests.user_id')
                            ->with(['rental_request.user']) // تحميل العلاقات
                            ->orderByDesc('total_spent')
                            ->limit(5)
                            ->get()
                            ->filter(fn($bill) => $bill->rental_request !== null && $bill->rental_request->user !== null) // التحقق من وجود rental_request و user
                    ];

                    $view = 'reports.partials.customers';
                    break;



            default:
                return response()->json(['message' => __('messages.unknown_report')], 400);
        }

        return view($view, $data)->with('lang', $lang);
    }
}
