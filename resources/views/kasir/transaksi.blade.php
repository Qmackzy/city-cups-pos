<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="md:col-span-2 bg-white p-6 shadow sm:rounded-lg">
                    @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex justify-between items-center shadow-sm">
                        <span>{{ session('success') }}</span>
                        @if(session('transaction_id'))
                            <a href="{{ route('kasir.print', session('transaction_id')) }}" target="_blank" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs font-bold uppercase transition">
                                Cetak Struk
                            </a>
                        @endif
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 shadow-sm">
                        {{ session('error') }}
                    </div>
                    @endif

                    <div class="mb-6">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Filter Kategori</h3>
                        <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
                            <button onclick="filterCategory('all', this)" 
                                    class="category-btn active px-6 py-2 rounded-full bg-blue-600 text-white text-xs font-bold transition whitespace-nowrap shadow-md">
                                Semua Menu
                            </button>
                            @foreach($categories as $category)
                            <button onclick="filterCategory('cat-{{ $category->id }}', this)" 
                                    class="category-btn px-6 py-2 rounded-full bg-gray-100 text-gray-600 text-xs font-bold hover:bg-gray-200 transition whitespace-nowrap">
                                {{ $category->name }}
                            </button>
                            @endforeach
                        </div>
                    </div>

                    <h3 class="text-lg font-bold mb-4 text-gray-800">Daftar Menu :</h3>
                    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4" id="product-grid">
                        @foreach($products as $product)
                        <button onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})" 
                                data-category="cat-{{ $product->category_id }}"
                                class="product-item border border-gray-100 rounded-xl hover:border-blue-500 hover:shadow-xl text-left transition duration-300 overflow-hidden group bg-white">
                            
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-32 object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <div class="w-full h-32 bg-gray-50 flex items-center justify-center text-gray-400 text-xs italic">No Image</div>
                            @endif

                            <div class="p-4">
                                <span class="block font-bold text-sm text-gray-700 group-hover:text-blue-700 truncate">{{ $product->name }}</span>
                                <span class="block text-xs text-gray-400 mt-1">Tersedia: {{ $product->stock }}</span>
                                <span class="block text-blue-600 font-black mt-2">Rp {{ number_format($product->price) }}</span>
                            </div>
                        </button>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white p-6 shadow-xl sm:rounded-lg h-fit sticky top-6 border-t-4 border-blue-600">
                    <h3 class="text-lg font-black mb-4 border-b pb-2 text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        Pesanan Baru
                    </h3>
                    
                    <form action="{{ route('kasir.store') }}" method="POST" id="order-form">
                        @csrf
                        
                        <div id="cart-items" class="mb-6 space-y-2 max-h-80 overflow-y-auto pr-2 custom-scrollbar">
                            <p class="text-center text-gray-400 text-sm py-10 italic">Klik menu untuk menambah pesanan</p>
                        </div>
                        
                        <div class="border-t border-dashed pt-4">
                            <div class="mb-4">
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Metode Pembayaran</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="payment_method" value="cash" class="hidden peer" checked onchange="togglePaymentMode('cash')">
                                        <div class="p-3 text-center border-2 rounded-xl peer-checked:bg-blue-50 peer-checked:border-blue-600 peer-checked:text-blue-600 font-bold text-xs transition-all">
                                            💵 CASH
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="payment_method" value="qris" class="hidden peer" onchange="togglePaymentMode('qris')">
                                        <div class="p-3 text-center border-2 rounded-xl peer-checked:bg-blue-50 peer-checked:border-blue-600 peer-checked:text-blue-600 font-bold text-xs transition-all">
                                            📱 QRIS
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="flex justify-between font-black text-2xl mb-4 text-blue-900">
                                <span>Total</span>
                                <span id="grand-total">Rp 0</span>
                                <input type="hidden" name="total_price" id="input-total">
                            </div>

                            <div class="mb-3">
                                <label id="label-bayar" class="block text-xs font-bold text-gray-400 uppercase mb-1 tracking-widest">Bayar (Cash)</label>
                                <input type="number" name="pay_amount" id="pay_amount" class="w-full border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 font-bold text-lg p-3" required oninput="calculateChange()">
                            </div>

                            <div class="mb-6">
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1 tracking-widest">Kembalian</label>
                                <input type="text" id="change_amount" class="w-full bg-gray-50 border-none rounded-xl font-black text-green-600 text-lg p-3" readonly value="Rp 0">
                            </div>

                            <button type="submit" class="w-full bg-blue-600 text-white py-4 rounded-2xl font-black text-lg hover:bg-blue-700 shadow-lg shadow-blue-200 transition transform active:scale-95 flex justify-center items-center gap-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                SELESAIKAN ORDER
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
        let cart = [];
        let total = 0;

        // 1. FILTER KATEGORI
        function filterCategory(categoryId, element) {
            const items = document.querySelectorAll('.product-item');
            const buttons = document.querySelectorAll('.category-btn');

            buttons.forEach(btn => {
                btn.classList.remove('bg-blue-600', 'text-white', 'shadow-md');
                btn.classList.add('bg-gray-100', 'text-gray-600');
            });
            element.classList.remove('bg-gray-100', 'text-gray-600');
            element.classList.add('bg-blue-600', 'text-white', 'shadow-md');

            items.forEach(item => {
                if (categoryId === 'all' || item.getAttribute('data-category') === categoryId) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // 2. KERANJANG BELANJA
        function addToCart(id, name, price) {
            const existing = cart.find(item => item.id === id);
            if (existing) {
                existing.qty++;
            } else {
                cart.push({ id, name, price, qty: 1 });
            }
            renderCart();
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            renderCart();
        }

        function renderCart() {
            const container = document.getElementById('cart-items');
            container.innerHTML = '';
            total = 0;

            if (cart.length === 0) {
                container.innerHTML = '<p class="text-center text-gray-400 text-sm py-10 italic">Klik menu untuk menambah pesanan</p>';
            }

            cart.forEach((item, index) => {
                total += item.price * item.qty;
                container.innerHTML += `
                    <div class="flex justify-between items-center bg-gray-50 p-4 rounded-xl border border-gray-100 group">
                        <div class="flex-1">
                            <span class="font-bold text-sm text-gray-800">${item.name}</span>
                            <input type="hidden" name="items[${index}][id]" value="${item.id}">
                            <input type="hidden" name="items[${index}][qty]" value="${item.qty}">
                            <div class="flex items-center gap-2 mt-1">
                                <span class="bg-blue-100 text-blue-700 text-[10px] font-black px-2 py-0.5 rounded-full">${item.qty} Pcs</span>
                                <span class="text-xs text-gray-400">@ ${item.price.toLocaleString()}</span>
                            </div>
                        </div>
                        <div class="text-right flex items-center gap-3">
                            <span class="text-sm font-black text-gray-700 italic">Rp ${(item.price * item.qty).toLocaleString()}</span>
                            <button type="button" onclick="removeFromCart(${index})" class="text-gray-300 hover:text-red-500 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                `;
            });

            document.getElementById('grand-total').innerText = 'Rp ' + total.toLocaleString();
            document.getElementById('input-total').value = total;
            
            // Re-trigger update payment jika item berubah
            const activeMethod = document.querySelector('input[name="payment_method"]:checked').value;
            togglePaymentMode(activeMethod);
        }

        // 3. LOGIKA PEMBAYARAN
        function togglePaymentMode(mode) {
            const payInput = document.getElementById('pay_amount');
            const labelBayar = document.getElementById('label-bayar');
            
            if (mode !== 'cash') {
                payInput.value = total;
                payInput.readOnly = true;
                payInput.classList.add('bg-gray-100');
                labelBayar.innerText = "Nominal QRIS (Otomatis)";
            } else {
                payInput.value = "";
                payInput.readOnly = false;
                payInput.classList.remove('bg-gray-100');
                labelBayar.innerText = "Bayar (Cash)";
            }
            calculateChange();
        }

        function calculateChange() {
            const pay = document.getElementById('pay_amount').value;
            const change = pay - total;
            const display = document.getElementById('change_amount');
            
            if (pay === "" || pay == 0) {
                display.value = "Rp 0";
                display.classList.remove('text-red-500', 'text-green-600');
            } else if (change >= 0) {
                display.value = 'Rp ' + change.toLocaleString();
                display.classList.remove('text-red-500');
                display.classList.add('text-green-600');
            } else {
                display.value = 'Uang tidak cukup';
                display.classList.remove('text-green-600');
                display.classList.add('text-red-500');
            }
        }
    </script>
</x-app-layout>