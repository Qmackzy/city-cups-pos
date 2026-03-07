<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Shift Kasir') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-3 border">Kasir</th>
                            <th class="p-3 border">Waktu Shift</th>
                            <th class="p-3 border">Modal Awal</th>
                            <th class="p-3 border">Ekspektasi Kas</th>
                            <th class="p-3 border">Uang Fisik</th>
                            <th class="p-3 border">Selisih</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($shifts as $shift)
                        <tr>
                            <td class="p-3 border">{{ $shift->user->name }}</td>
                            <td class="p-3 border text-sm">
                                {{ $shift->start_time }} s/d <br> {{ $shift->end_time }}
                            </td>
                            <td class="p-3 border text-right">Rp {{ number_format($shift->starting_cash) }}</td>
                            <td class="p-3 border text-right">Rp {{ number_format($shift->total_cash_expected) }}</td>
                            <td class="p-3 border text-right">Rp {{ number_format($shift->total_cash_actual) }}</td>
                            <td class="p-3 border text-right font-bold {{ $shift->difference < 0 ? 'text-red-600' : 'text-green-600' }}">
                                Rp {{ number_format($shift->difference) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $shifts->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>