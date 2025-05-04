<header class="flex items-center justify-between px-6 py-4 bg-primary m-1 rounded-2xl">
    <div class="flex items-center">
        <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none lg:hidden">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </button>
        <!-- نموذج البحث -->
        <form id="search-form" action="{{ route('search') }}" method="GET" class="relative w-full max-w-md mx-auto">
            <input type="text" id="search-input" name="query" placeholder="{{ __('web.Search for pages...') }}"
                autocomplete="off"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <!-- صندوق الاقتراحات -->
            <div id="suggestions"
                class="absolute top-full mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-lg max-h-48 overflow-y-auto z-10 hidden">
            </div>
        </form>

        {{-- <div class="relative mx-4 lg:mx-0">
            <span class="absolute inset-y-0 rtl:right-0 ltr:left-0 flex items-center px-3">
                <svg class="w-5 h-5 text-gray-500" viewBox="0 0 24 24" fill="none">
                    <path
                        d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
            <input class="w-32 ltr:pl-10 rtl:pr-10 rtl:pl-4 ltr:pr-4 py-1 rounded-md form-input sm:w-64" type="text"
                placeholder="{{__('web.Search')}}">
        </div> --}}
    </div>

    <div class="flex items-center">
        <button class="lang-btn items-center p-1">
            <div class="icon text-xl text-beige">
                <i class='bx bx-world'></i>
            </div>
            <span class="text-xs text-beige">E/A</span>
        </button>

        <div id="notification-dropdown" class="relative">
            <!-- زر الإشعارات -->
            <button id="notification-button" class="relative flex mx-2 items-center">
                <svg class="text-beige w-7 h-7" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M15 17H20L18.5951 15.5951C18.2141 15.2141 18 14.6973 18 14.1585V11C18 8.38757 16.3304 6.16509 14 5.34142V5C14 3.89543 13.1046 3 12 3C10.8954 3 10 3.89543 10 5V5.34142C7.66962 6.16509 6 8.38757 6 11V14.1585C6 14.6973 5.78595 15.2141 5.40493 15.5951L4 17H9M15 17V18C15 19.6569 13.6569 21 12 21C10.3431 21 9 19.6569 9 18V17M15 17H9"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span id="unread-count"
                    class="absolute top-0 ltr:right-0 rtl:left-0 inline-block w-4 h-4 bg-red-600 text-white text-xs font-bold rounded-full hidden">0</span>
            </button>

            <!-- قائمة الإشعارات -->
            <div id="notifications-list"
                class="absolute ltr:right-0 rtl:left-0 z-10 mt-2 w-52 sm:w-60 md:w-72 lg:w-80 bg-white shadow-lg rounded-lg hidden">
                <div class="p-4">
                    <h4 class="text-lg font-semibold">
                        {{__('web.Notifications')}}
                    </h4>
                </div>
                <ul id="notifications-container" class="max-h-64 overflow-y-auto">
                    <!-- الإشعارات ستُضاف هنا باستخدام AJAX -->
                </ul>
                <div class="text-blue-500 p-3 m-2 text-xs lg:text-sm">
                    <a href="{{route('notifications.index')}}">
                        {{__('web.Show all notifications..')}}
                    </a>
                </div>

            </div>
        </div>




        <div x-data="{ dropdownOpen: false }" class="relative">
            <button @click="dropdownOpen = !dropdownOpen"
                class="relative block w-10 h-10 overflow-hidden rounded-full shadow focus:outline-none">
                <img class="object-cover w-full h-full"
                    src="{{ Auth::user()->user_image ? asset('storage/' . Auth::user()->user_image) : asset('default_images/default_profile.jpg') }}"
                    alt="Your avatar">
            </button>

            <div x-cloak x-show="dropdownOpen" @click="dropdownOpen = false" class="fixed inset-0 z-10 w-full h-full">
            </div>

            <div x-cloak x-show="dropdownOpen"
                class="absolute rtl:left-0 ltr:right-0 z-10 w-48 mt-2 overflow-hidden bg-white rounded-md shadow-xl">
                <a href="{{ LaravelLocalization::localizeUrl('/profile') }}"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-secondary hover:text-white">
                    {{__('web.My profile')}}
                </a>
                {{-- <div class="block px-4 py-2 text-sm text-gray-700 hover:bg-secondary hover:text-white"> --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                        this.closest('form').submit();">
                            {{ __('web.Log Out') }}
                        </x-dropdown-link>
                    </form>
                </div>
            </div>
        </div>
</header>
<script>
    //البحث
    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.getElementById("search-input");
        const suggestionsBox = document.getElementById("suggestions");

        let selectedIndex = -1; // لتتبع العنصر المحدد حاليًا في القائمة

        // عندما يتم كتابة شيء في الحقل
        searchInput.addEventListener("input", function () {
            const query = searchInput.value.trim();

            if (query.length > 0) {
                fetch(`{{ route('search') }}?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        // تفريغ النتائج السابقة
                        suggestionsBox.innerHTML = "";

                        if (data.length > 0) {
                            // عرض النتائج
                            data.forEach((page, index) => {
                                const language = "{{ LaravelLocalization::getCurrentLocale() }}";
                                const pageName = language === 'ar' ? page.name_ar : page.name_en;

                                const suggestionItem = document.createElement("div");
                                suggestionItem.className = "px-4 py-2 hover:bg-gray-100 cursor-pointer";
                                suggestionItem.innerHTML = `
                                <a href="${page.url}" class="block text-sm text-gray-800">${pageName}</a>
                            `;
                                suggestionsBox.appendChild(suggestionItem);

                                // إضافة خاصية الانتقال إلى العنصر المحدد
                                suggestionItem.addEventListener("mouseover", () => {
                                    selectedIndex = index;
                                    highlightSelectedItem();
                                });
                            });

                            suggestionsBox.classList.remove("hidden");
                        } else {
                            // إخفاء الصندوق إذا لم تكن هناك نتائج
                            suggestionsBox.classList.add("hidden");
                        }
                    });
            } else {
                // إخفاء الصندوق إذا كانت حقل الإدخال فارغًا
                suggestionsBox.classList.add("hidden");
            }
        });

        // إخفاء صندوق الاقتراحات عند النقر خارجًا
        document.addEventListener("click", function (event) {
            if (!searchInput.contains(event.target) && !suggestionsBox.contains(event.target)) {
                suggestionsBox.classList.add("hidden");
            }
        });

        // منع إرسال النموذج عند الضغط على زر Enter
        searchInput.addEventListener("keydown", function (event) {
            if (event.key === "Enter") {
                event.preventDefault(); // منع إرسال النموذج
                // العثور على العنصر المحدد وتوجيه المستخدم إليه
                const selectedItem = suggestionsBox.querySelectorAll("a")[selectedIndex];
                if (selectedItem) {
                    window.location.href = selectedItem.href; // توجيه المستخدم إلى الرابط
                }
            }

            // التعامل مع مفاتيح الأسهم
            if (event.key === "ArrowDown") {
                event.preventDefault(); // منع التمرير في الصفحة
                if (selectedIndex < suggestionsBox.children.length - 1) {
                    selectedIndex++;
                    highlightSelectedItem();
                    // التمرير لأسفل إذا كانت العنصر المحدد قريب من الأسفل
                    scrollToSelectedItem();
                }
            }

            if (event.key === "ArrowUp") {
                event.preventDefault(); // منع التمرير في الصفحة
                if (selectedIndex > 0) {
                    selectedIndex--;
                    highlightSelectedItem();
                    // التمرير لأعلى إذا كانت العنصر المحدد قريب من الأعلى
                    scrollToSelectedItem();
                }
            }
        });

        // تحديث المظهر لتمييز العنصر المحدد
        function highlightSelectedItem() {
            const items = suggestionsBox.children;
            Array.from(items).forEach((item, index) => {
                if (index === selectedIndex) {
                    item.classList.add("bg-gray-200");
                } else {
                    item.classList.remove("bg-gray-200");
                }
            });
        }

        // التمرير التلقائي إلى العنصر المحدد عند الاقتراب من الأعلى أو الأسفل
        function scrollToSelectedItem() {
            const selectedItem = suggestionsBox.children[selectedIndex];
            if (selectedItem) {
                const container = suggestionsBox;
                const itemTop = selectedItem.offsetTop;
                const itemBottom = itemTop + selectedItem.offsetHeight;
                const containerTop = container.scrollTop;
                const containerBottom = containerTop + container.clientHeight;

                // التمرير لأسفل إذا كانت العنصر المحدد قريب من الأسفل
                if (itemBottom > containerBottom) {
                    container.scrollTop = itemBottom - container.clientHeight;
                }
                // التمرير لأعلى إذا كانت العنصر المحدد قريب من الأعلى
                if (itemTop < containerTop) {
                    container.scrollTop = itemTop;
                }
            }
        }
    });




    //الإشعارات
    document.addEventListener('DOMContentLoaded', function () {
        const notificationButton = document.getElementById('notification-button');
        const notificationsList = document.getElementById('notifications-list');
        const notificationsContainer = document.getElementById('notifications-container');
        const unreadCount = document.getElementById('unread-count');
        fetchNotifications();
        // تحميل الإشعارات عند الضغط على الزر
        notificationButton.addEventListener('click', function () {
            if (notificationsList.classList.contains('hidden')) {
                fetchNotifications();
                notificationsList.classList.remove('hidden');
            } else {
                notificationsList.classList.add('hidden');
            }
        });
        document.addEventListener('click', function (event) {
            if (!notificationsList.contains(event.target) && !notificationButton.contains(event.target)) {
                notificationsList.classList.add('hidden');
            }
        });

        // جلب الإشعارات باستخدام AJAX
        function fetchNotifications() {
            fetch('{{ route('notifications.show') }}')
                .then(response => response.json())
                .then(data => {
                    notificationsContainer.innerHTML = '';

                    if (data.message) {
                        // إذا كانت هناك رسالة "لا توجد إشعارات"
                        notificationsContainer.innerHTML = `
                        <li class="px-4 py-2 text-center text-gray-500">
                            ${data.message}
                        </li>
                    `;
                    } else {
                        unreadCount.classList.toggle('hidden', data.unreadCount === 0);

                        data.notifications.forEach(notification => {
                            const li = document.createElement('li');
                            li.classList.add('flex', 'justify-between', 'items-center', 'px-4', 'py-2', 'hover:bg-gray-100');
                            function getCurrentLocale() {
                                const pathSegments = window.location.pathname.split('/');
                                return pathSegments[1] || 'ar';
                            }

                            li.innerHTML = `
                            <a href="${window.location.origin}/${getCurrentLocale()}/${notification.data.url}" class="w-full flex items-center space-x-3 text-sm text-gray-800">
                                <div class="flex-1">
                                <p class="font-medium">${notification.title}</p>
                                <p class="text-xs text-gray-600">${notification.message}</p>
                                <small class="text-gray-400">${new Date(notification.created_at).toLocaleString()}</small>
                                </div>
                            </a>
                             <button data-id="${notification.id}" class="mark-as-read text-blue-500 text-xs">
                            {{ __('web.Mark as Read') }}
                            </button>
                            `;
                            notificationsContainer.appendChild(li);
                        });

                        addMarkAsReadListeners();
                        updateUnreadCount(); // تحديث عدّاد الإشعارات
                    }
                })
                .catch(error => console.error('Error fetching notifications:', error));
        }

        // إضافة أحداث زر "Mark as Read"
        function addMarkAsReadListeners() {
            const buttons = document.querySelectorAll('.mark-as-read');
            buttons.forEach(button => {
                button.addEventListener('click', function () {
                    const notificationId = this.getAttribute('data-id');
                    markNotificationAsRead(notificationId, this);
                });
            });
        }

        // تحديث حالة الإشعار كمقروء
        function markNotificationAsRead(id, button) {
            fetch(`{{ url('/notifications/mark-as-read') }}/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // إزالة الإشعار من القائمة مباشرة
                        const parentElement = button.parentElement;
                        parentElement.remove();

                        // تحديث عدد الإشعارات غير المقروءة
                        updateUnreadCount();

                        // إذا لم تعد هناك إشعارات، عرض رسالة "لا توجد إشعارات"
                        if (notificationsContainer.children.length === 0) {
                            notificationsContainer.innerHTML = `
                            <li class="px-4 py-2 text-center text-gray-500">
                                {{ __('web.No notifications') }}
                            </li>
                        `;
                        }
                    } else {
                        console.error('Error marking notification as read:', data.message);
                    }
                })
                .catch(error => console.error('Error marking notification as read:', error));
        }

        // تحديث عدّاد الإشعارات غير المقروءة
        function updateUnreadCount() {
            const unreadNotifications = notificationsContainer.querySelectorAll('li').length;
            if (unreadNotifications > 0) {
                unreadCount.classList.remove('hidden');
                unreadCount.textContent = unreadNotifications;
            } else {
                unreadCount.classList.add('hidden');
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelector('.lang-btn').addEventListener('click', function () {
            const currentLang = '{{ LaravelLocalization::getCurrentLocale() }}';
            const newLang = currentLang === 'en' ? 'ar' : 'en';
            const url = `{{ LaravelLocalization::getLocalizedURL('ar') }}`.replace('/ar', `/${newLang}`);
            window.location.href = url;
        });
    });


</script>