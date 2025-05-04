@extends('layout.master')

@section('body')
    <div class="container w-full mx-auto bg-white p-6 shadow-md rounded-lg">
        <h1 class="text-2xl font-bold text-secondary mb-4">{{ __('web.Transactions Log') }}</h1>
        <div class="mb-4">
            <form method="GET" action="{{ route('activity.index') }}" class="flex flex-wrap gap-4">
                <!-- فلترة حسب اسم النشاط -->
                <select name="log_name" class="border-gray-300 rounded px-4 py-2">
                    <option value="">{{ __('web.All Logs') }}</option>
                    <option value="categories">{{ __('web.Categories') }}</option>
                    <option value="bills">{{ __('web.Bills') }}</option>
                    <option value="assurances">{{ __('web.Assurances') }}</option>
                    <option value="lateFees">{{ __('web.Late Fees') }}</option>
                    <option value="setting">{{ __('web.Setting') }}</option>
                    <option value="ratings">{{ __('web.Ratings') }}</option>
                    <option value="users">{{ __('web.Users') }}</option>
                </select>

                <!-- فلترة حسب نوع الحدث -->
                <select name="event" class="border-gray-300 rounded px-4 py-2">
                    <option value="">{{ __('web.All Events') }}</option>
                    <option value="created">{{ __('web.Created') }}</option>
                    <option value="updated">{{ __('web.Updated') }}</option>
                    <option value="deleted">{{ __('web.Deleted') }}</option>
                </select>

                <!-- فلترة حسب المستخدم -->
                <input type="number" name="causer_id" placeholder="{{ __('web.User ID') }}"
                    class="border-gray-300 rounded px-4 py-2" value="{{ request('causer_id') }}">

                <!-- فلترة حسب التاريخ -->
                <input type="date" name="date" class="border-gray-300 rounded px-4 py-2" value="{{ request('date') }}">

                <!-- زر التصفية -->
                <button type="submit"
                    class="bg-primary text-white rounded px-4 py-2 hover:bg-secondary">{{ __('web.Filter') }}</button>

                <!-- زر لإزالة التصفية -->
                <a href="{{ route('activity.index') }}"
                    class="bg-sky_blue text-black rounded px-4 py-2 hover:bg-gray-200">{{ __('web.Reset') }}</a>
            </form>
        </div>

        <table
            class="w-full p-3 text-center border-separate border-spacing-y-3 rounded-lg border-2 border-gray-300 text-xs sm:text-sm">
            <thead>
                <tr class="bg-sky_blue">
                    <th class="px-4 py-2">{{ __('web.ID') }}</th>
                    <th class="px-4 py-2">{{ __('web.Activety Name') }}</th>
                    <th class="px-4 py-2">{{ __('web.User Name') }}</th>
                    <th class="px-4 py-2">{{ __('web.Activity Type') }}</th>
                    <th class="px-4 py-2">{{ __('web.Details') }}</th>
                    <th class="px-4 py-2">{{ __('web.Time And Date') }}</th>
                    {{-- <th class="px-4 py-2">Procedures</th> --}}
                </tr>
            </thead>
            <tbody>
                @forelse ($activities as $activity)
                    <tr class="hover:bg-beige">
                        <td class="px-4 py-2">{{ $activity->id }}</td>
                        <td class="px-4 py-2">
                            @if ($activity->log_name === 'categories')
                                {{ app()->getLocale() === 'ar' ? 'الفئات' : 'Categories' }}
                            @elseif ($activity->log_name === 'bills')
                                {{ app()->getLocale() === 'ar' ? 'السندات' : 'Bills' }}
                                @elseif ($activity->log_name === 'assurances')
                                {{ app()->getLocale() === 'ar' ? 'التأمينات' : 'assurances' }}
                                @elseif ($activity->log_name === 'lateFees')
                                {{ app()->getLocale() === 'ar' ? 'رسوم المتأخرات' : 'lateFees' }}
                                @elseif ($activity->log_name === 'setting')
                                {{ app()->getLocale() === 'ar' ? 'الإعدادات' : 'setting' }}
                                @elseif ($activity->log_name === 'ratings')
                                {{ app()->getLocale() === 'ar' ? 'التقييمات' : 'ratings' }}
                                @elseif ($activity->log_name === 'users')
                                {{ app()->getLocale() === 'ar' ? 'المستخدمين' : 'users' }}
                            @else
                                {{ $activity->log_name }}
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            {{ $activity->causer->first_name ?? __('web.Unknown') }}
                            {{ optional($activity->causer)->last_name ?? ' ' }}
                        </td>
                        <td class="px-4 py-2">
                            @if (app()->getLocale() === 'ar')
                                {{ $activity->event === 'created' ? 'إنشاء' : ($activity->event === 'updated' ? 'تعديل' : 'حذف') }}
                            @else
                                {{ ucfirst($activity->event) }}
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            @if (app()->getLocale() === 'ar')
                                {{ explode(' | ', $activity->description)[0] ?? 'غير متوفر' }}
                            @else
                                {{ explode(' | ', $activity->description)[1] ?? 'Not available' }}
                            @endif
                        </td>
                        <td class="px-4 py-2">{{ $activity->created_at->format('d/m/Y h:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-gray-500 py-4">
                            {{ app()->getLocale() === 'ar' ? 'لا توجد سجلات' : 'No logs found' }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
