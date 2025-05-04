@extends('layout.master')
@section('body')
<div class="cards grid lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 max-sm:grid-cols-2 gap-6">
    <a class="card" href="/customers">
        <div class="card-img text-8xl ">
            <i class='bx bxs-user-detail'></i>
        </div>
        <span class="text-secondary font-semibold">{{__('web.Customers')}}</span>
        <span>{{ $data['customers'] }}</span>
    </a>
    <a class="card" href="/items">
        <div class="card-img text-8xl">
            <i class='bx bxs-shopping-bag '></i>
        </div>
        <span class="text-secondary font-semibold">{{__('web.Products')}}</span>
        <span>{{ $data['products'] }}</span>
    </a>
    <a class="card" href="/requests">
        <div class="card-img text-8xl ">
            <i class='bx bx-mail-send'></i>
        </div>
        <span class="text-secondary font-semibold">{{__('web.Requests')}}</span>
        <span>{{ $data['requests'] }}</span>
    </a>
    <a class="card" href="/categories">
        <div class="card-img text-8xl ">
            <i class='bx bxs-category'></i>
        </div>
        <span class="text-secondary font-semibold">{{__('web.Categories')}}</span>
        <span>{{ $data['categories'] }}</span>
    </a>
    <a class="card" href="/assurances">
        <div class="card-img text-8xl ">
            <i class='bx bx-money-withdraw'></i>
        </div>
        <span class="text-secondary font-semibold">{{__('web.Assurances')}}</span>
        <span>{{ $data['assurances'] }}</span>
    </a>
    <a class="card" href="/ratings">
        <div class="card-img text-8xl">
            <i class='bx bx-chat'></i>
        </div>
        <span class="text-secondary font-semibold">{{__('web.Ratings')}}</span>
        <span>{{ $data['ratings'] }}</span>
    </a>
    <a class="card" href="/lateFees">
        <div class="card-img text-8xl ">
            <i class='bx bx-timer'></i>
        </div>
        <span class="text-secondary font-semibold">{{__('web.Late Fees')}}</span>
        <span>{{ $data['lateFees'] }}</span>
    </a>
    <a class="card" href="/bills">
        <div class="card-img text-8xl">
            <i class='bx bx-receipt'></i>
        </div>
        <span class="text-secondary font-semibold">{{__('web.Bills')}}</span>
        <span>{{ $data['bills'] }}</span>
    </a>
    <div>
    </div>
</div>
<div class="container mx-auto py-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">{{ __('web.Most Ordered Products') }}</h2>

    <!--  صندوق الرسم البياني للمنتجات الاكثر طلبا -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">{{ __('web.Top 5 Most Ordered Products') }}</h3>
        <div class="relative w-full h-96">
            <canvas id="mostOrderedChart"></canvas>
        </div>
    </div>
    <!-- مخطط الإحصائيات الدائري لعدد العملاء خلال الاشهر -->
    <div class="container mx-auto py-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">{{ __('web.Customers Statistics') }}</h2>

        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">{{ __('web.Customers Per Month') }}</h3>
            <div class="relative w-full h-96 flex justify-center">
                <canvas id="customersChart"></canvas>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const lang = document.documentElement.lang || 'en'; // الحصول على اللغة من الـ HTML

        // تعديل جميع الروابط
        const links = document.querySelectorAll('a[href^="/"]'); // البحث عن جميع الروابط التي تبدأ بـ "/"
        links.forEach(link => {
            let localizedUrl = link.getAttribute('href');
            link.setAttribute('href', '/' + lang + localizedUrl); // إضافة اللغة في بداية الرابط
        });

        fetch('{{ route("mostOrderedItems") }}')
            .then(response => {
                if (!response.ok) {
                    throw new Error('{{ __('web.Failed to fetch data') }}');
                }
                return response.json();
            })
            .then(data => {
                if (data.length === 0) {
                    throw new Error('{{ __('web.No data available') }}');
                }

                // الحصول على الأسماء وعدد الطلبات من البيانات
                const labels = data.map(item => item.name); // أسماء المنتجات
                const orderCounts = data.map(item => item.order_count); // عدد الطلبات

                let currentLang = "{{ app()->getLocale() }}"; // Get current language from Laravel
                // عكس الترتيب إذا كانت اللغة العربية
                if (currentLang === 'ar') {
                    labels.reverse();
                    orderCounts.reverse();
                }

                // إعداد الرسم البياني
                const ctx = document.getElementById('mostOrderedChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar', // نوع الرسم
                    data: {
                        labels: labels, // أسماء المنتجات
                        datasets: [{
                            label: '{{ __('web.Order Count') }}',
                            data: orderCounts, // عدد الطلبات
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.5)',
                                'rgba(54, 162, 235, 0.5)',
                                'rgba(255, 206, 86, 0.5)',
                                'rgba(75, 192, 192, 0.5)',
                                'rgba(153, 102, 255, 0.5)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true, // التأكد من أن المحور Y يبدأ من الصفر
                                position: "{{ app()->getLocale() }}" === 'ar' ? 'right' : 'left',
                                ticks: {
                                    color: '#4B5563',
                                    font: {
                                        size: 14
                                    }
                                },
                                grid: {
                                    color: '#E5E7EB'
                                }
                            },
                            x: {
                                ticks: {
                                    color: '#4B5563',
                                    font: {
                                        size: 14
                                    },
                                    align: currentLang === 'ar' ? 'right' : 'left'
                                },
                                grid: {
                                    display: false
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                labels: {
                                    color: '#374151',
                                    font: {
                                        size: 14
                                    }
                                },
                                rtl: currentLang === 'ar' ? true : false, // تغيير الاتجاه حسب اللغة
                            }
                        },
                        layout: {
                            padding: {
                                right: currentLang === 'ar' ? 20 : 0, // تعديل الحواف عند اللغة العربية
                                left: currentLang === 'ar' ? 0 : 20
                            }
                        },
                        direction: currentLang === 'ar' ? 'rtl' : 'ltr' // ضبط الاتجاه العام للرسم البياني

                    }
                });
            })
            .catch(error => {
                console.error('Error:', error);
                alert('{{ __('web.Failed to load chart data. Please try again later.') }}');
            });
    });

    //عرض احصائيات عدد العملاء
    document.addEventListener('DOMContentLoaded', function () {
        fetchCustomerStats();
        setInterval(fetchCustomerStats, 30000); // تحديث كل 30 ثانية
    });

    function fetchCustomerStats() {
        fetch('{{ route("customersStats") }}')
            .then(response => response.json())
            .then(data => {
                console.log('📊 Data received:', data); // فحص البيانات في Console

                if (data.status !== 'success' || Object.keys(data.data).length === 0) {
                    throw new Error('❌ لا توجد بيانات متاحة');
                }
                const lang = window.location.pathname.split('/')[1]; // استخراج "ar" أو "en" من الرابط

                const monthsLabels = {
                    ar: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو',
                        'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'],
                    en: ['January', 'February', 'March', 'April', 'May', 'June',
                        'July', 'August', 'September', 'October', 'November', 'December']
                };

                const labels = [];
                const values = [];
                const colors = [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                    '#FF9F40', '#FFCD56', '#C9CBCF', '#B3E283', '#FF6384',
                    '#36A2EB', '#FFCE56'
                ];

                Object.keys(data.data).forEach(month => {
                    if (data.data[month] > 0) { // تجاهل الأشهر التي قيمتها 0
                        labels.push(monthsLabels[lang]?.[month - 1] || monthsLabels.en[month - 1]);
                        values.push(data.data[month]);
                    }
                });

                console.log('✅ Labels:', labels);
                console.log('✅ Values:', values);

                if (values.length === 0) {
                    console.warn('⚠️ لا توجد بيانات كافية لرسم المخطط');
                    return;
                }

                // تدمير المخطط القديم إذا كان موجودًا
                const existingChart = Chart.getChart("customersChart");
                if (existingChart) {
                    existingChart.destroy();
                }

                // رسم المخطط الدائري
                const ctx = document.getElementById('customersChart').getContext('2d');
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: '{{ __("web.Customers") }}',
                            data: values,
                            backgroundColor: colors.slice(0, labels.length),
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            })
            .catch(error => {
                console.error('❌ Error fetching customer stats:', error);
            });
    }

</script>


@endsection
