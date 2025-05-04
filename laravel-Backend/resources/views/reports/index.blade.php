@extends('layout.master')

@section('body')
<div class="container mx-auto p-6">
    <h2 class="text-2xl text-secondary font-bold mb-4">{{ __('web.reports') }}</h2>

    <div class="mb-4">
        <label for="reportSelect" class="block text-primary text-lg font-semibold">{{ __('web.select_report') }}</label>
        <select id="reportSelect" class="w-full p-2 border rounded-lg">
            <option value="" selected disabled>{{ __('web.select_report') }}</option>
            <option value="payments">{{ __('web.payments') }}</option>
            <option value="orders">{{ __('web.orders') }}</option>
            <option value="customers">{{ __('web.customers') }}</option>
        </select>
    </div>

    <button id="downloadExcel" class="bg-green-500 text-white px-4 py-2 rounded hidden">
        {{ __('web.download_excel') }}
    </button>

    <div id="reportContent" class="mt-6"></div>
</div>

<script>
    let currentLang = "{{ app()->getLocale() }}"; // تعريف اللغة العامة

    document.getElementById('reportSelect').addEventListener('change', function () {
        let reportType = this.value;
        if (!reportType) return;

        fetch(`/reports/${reportType}?lang=${currentLang}`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('reportContent').innerHTML = html;

                // إظهار زر التحميل عند تحميل التقرير
                let downloadBtn = document.getElementById('downloadExcel');
                downloadBtn.classList.remove('hidden');
                downloadBtn.setAttribute('onclick', `generateExcel('${reportType}')`);
            });
    });

    function generateExcel(reportType) {
        if (!document.querySelector('table')) {
            alert('لا توجد بيانات متاحة للتصدير!');
            return;
        }

        let workbook = XLSX.utils.book_new(); // إنشاء ملف Excel جديد
        let titles = {
            "order_status": currentLang === "ar" ? "عدد الطلبات حسب الحالة" : "Orders by Status",
            "top_items": currentLang === "ar" ? "أكثر المنتجات طلبًا" : "Top Ordered Items",
            "orders_per_customer": currentLang === "ar" ? "الطلبات لكل عميل" : "Orders per Customer",
            "new_customers": currentLang === "ar" ? "العملاء الجدد شهريًا" : "New Customers per Month",
            "top_spenders": currentLang === "ar" ? "العملاء الأكثر إنفاقًا" : "Top Spenders",
            "monthly_stats": currentLang === "ar" ? "إحصائيات شهرية" : "Monthly Statistics",
            "general_report": currentLang === "ar" ? "التقرير العام" : "General Report",
        };

        let tables = document.querySelectorAll('table');
        tables.forEach((table, index) => {
            let worksheet = XLSX.utils.table_to_sheet(table);
            let titleKey = Object.keys(titles)[index] || "general_report";
            XLSX.utils.book_append_sheet(workbook, worksheet, titles[titleKey]);
        });

        XLSX.writeFile(workbook, `report_${reportType}_${currentLang}.xlsx`);
    }
</script>

@endsection
