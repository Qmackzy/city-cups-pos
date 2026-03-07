<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight"> Atur Resep: {{ $product->name }} </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <form action="{{ route('products.recipe.store', $product->id) }}" method="POST">
                    @csrf
                    <table class="w-full text-left mb-6">
                        <thead>
                            <tr class="text-xs uppercase text-gray-500 border-b">
                                <th class="p-2">Pilih Bahan</th>
                                <th class="p-2">Takaran (sesuai satuan bahan)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ingredients as $ing)
<tr class="border-b">
    <td class="p-2">
        <label class="flex items-center gap-2">
            <input type="checkbox" name="ingredients[{{ $ing->id }}][ingredient_id]" value="{{ $ing->id }}" 
            {{ $product->ingredients->contains($ing->id) ? 'checked' : '' }}>
            <span>{{ $ing->name }}</span>
        </label>
    </td>
    <td class="p-2">
        <input type="number" step="0.01" name="ingredients[{{ $ing->id }}][amount]" 
        value="{{ $product->ingredients->find($ing->id)->pivot->amount ?? '' }}"
        placeholder="Takaran" class="border-gray-300 rounded shadow-sm">
        <span class="text-xs text-gray-400">{{ $ing->unit }}</span>
    </td>
</tr>
@endforeach
                        </tbody>
                    </table>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('products.index') }}" class="px-4 py-2 bg-gray-200 rounded-lg font-bold text-sm">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-bold text-sm">Simpan Resep</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>