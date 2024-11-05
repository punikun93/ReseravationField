<div class="flex items-center gap-2">
    @if (request()->routeIs('admin.transaksi.daily'))
        <div class="relative flex items-center">
            <input type="text" id="daily-date" name="date" placeholder="Pilih Tanggal"
                value="{{ request('date', now()->format('Y-m-d')) }}"
                class="bg-white px-4 py-2 min-w-[300px] rounded-lg shadow-sm border border-gray-300 cursor-pointer" readonly>
            <span class="absolute right-3 text-gray-500">
                <i class="fas fa-calendar-day"></i>
            </span>
        </div>
    @elseif(request()->routeIs('admin.transaksi.monthly') || request()->routeIs('admin.transaksi.yearly'))
        <select id="month" name="month"
            class="bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-300 cursor-pointer {{ request()->routeIs('admin.transaksi.yearly') ? 'hidden' : '' }}">
            @foreach (range(1, 12) as $month)
                <option value="{{ $month }}" {{ request('month', now()->month) == $month ? 'selected' : '' }}>
                    {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                </option>
            @endforeach
        </select>
        <select id="year" name="year"
            class="bg-white py-2 rounded-lg shadow-sm border border-gray-300 cursor-pointer">
            @foreach (range(date('Y'), 2020) as $year)
                <option value="{{ $year }}" {{ request('year', now()->year) == $year ? 'selected' : '' }}>
                    {{ $year }}
                </option>
            @endforeach
        </select>
    @else
        <div class="relative flex items-center">
            <input type="text" id="date-range" placeholder="Pilih Periode"
                class="bg-white px-4 py-2 pr-10 min-w-[300px] rounded-lg shadow-sm border border-gray-300 cursor-pointer"
                readonly>
            <span class="absolute right-3 text-gray-500">
                <i class="fas fa-calendar-alt"></i>
            </span>
            <input type="hidden" name="start_date" id="start_date" value="{{ request('start_date') }}">
            <input type="hidden" name="end_date" id="end_date" value="{{ request('end_date') }}">
        </div>
    @endif
</div>
