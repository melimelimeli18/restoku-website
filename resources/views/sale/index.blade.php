<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Pilih Item Penjualan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .selectable-card {
            cursor: pointer;
            position: relative;
            border: 2px solid transparent;
            transition: 0.3s;
        }
        .selectable-card:hover,
        .selectable-card.selected {
            border-color: #0d6efd;
            background-color: #e9f3ff;
        }
        .item-img {
            width: 60px; height: 60px; object-fit: cover; border-radius: 0.5rem;
        }
        .badge-amount {
            position: absolute;
            top: 8px;
            right: 8px;
            background: red;
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            font-size: 14px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            z-index: 10;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h2> Halaman Penjualan</h2>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('app.home') }}" class="btn btn-secondary">Home</a>
        <div id="transaction-area">
            <!-- Dinamis: nanti tombol CTA atau tombol proses transaksi -->
            <div class="alert alert-warning m-0" id="alert-no-items">Tambahkan item terlebih dahulu</div>
            <button class="btn btn-success d-none" id="btn-process">Proses Transaksi Rp <span id="total-price">0</span></button>
        </div>
    </div>

    <div class="row g-3">
        @foreach($items as $item)
            <div class="col-md-4">
                <div class="card selectable-card" 
                    data-id="{{ $item->id }}" 
                    data-name="{{ $item->name }}" 
                    data-price="{{ $item->price }}"
                    >
                    @if($item->photo)
                        <img src="{{ asset('storage/' . $item->photo) }}" alt="{{ $item->name }}" class="item-img me-3" />
                    @else
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center me-3" style="width:60px; height:60px; border-radius:0.5rem;">
                            No Photo
                        </div>
                    @endif
                    <div class="p-2">
                        <h6>{{ $item->name }}</h6>
                        <p class="mb-0">Rp {{ number_format($item->price,0,',','.') }}</p>
                    </div>
                    <div class="badge-amount d-none" id="badge-amount-{{ $item->id }}">0</div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Modal untuk input jumlah -->
<div class="modal fade" id="quantityModal" tabindex="-1" aria-labelledby="quantityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 35%;">
        <div class="modal-content p-3">
            <div class="modal-header">
                <h5 class="modal-title" id="quantityModalLabel">Jumlah Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <p id="modalItemName" class="fw-bold fs-5"></p>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <button type="button" class="btn btn-outline-primary" id="btnMinus">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-dash" viewBox="0 0 16 16"><path d="M3.5 8a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 0 1h-8a.5.5 0 0 1-.5-.5z"/></svg>
                    </button>
                    <input type="text" id="quantityInput" class="form-control text-center" value="1" style="width: 60px;" readonly />
                    <button type="button" class="btn btn-outline-primary" id="btnPlus">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16"><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg>
                    </button>
                </div>
                <p>Harga per item: <span id="modalItemPrice"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnCancel" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="btnAdd" class="btn btn-primary">Tambahkan</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const cards = document.querySelectorAll('.selectable-card');
    const modal = new bootstrap.Modal(document.getElementById('quantityModal'));
    const modalItemName = document.getElementById('modalItemName');
    const modalItemPrice = document.getElementById('modalItemPrice');
    const quantityInput = document.getElementById('quantityInput');
    const btnPlus = document.getElementById('btnPlus');
    const btnMinus = document.getElementById('btnMinus');
    const btnAdd = document.getElementById('btnAdd');
    const btnCancel = document.getElementById('btnCancel');
    const badgeAmounts = {};

    let selectedItemId = null;
    let selectedItemName = '';
    let selectedItemPrice = 0;
    let quantity = 1;

    const selectedItems = {};  // object id -> quantity

    function updateTransactionArea() {
        const totalPrice = Object.entries(selectedItems).reduce((total, [id, qty]) => {
            const price = parseFloat(document.querySelector(`.selectable-card[data-id="${id}"]`).dataset.price);
            return total + price * qty;
        }, 0);

        const transactionArea = document.getElementById('transaction-area');
        const alertNoItems = document.getElementById('alert-no-items');
        const btnProcess = document.getElementById('btn-process');
        const totalPriceSpan = document.getElementById('total-price');

        if (Object.keys(selectedItems).length === 0) {
            alertNoItems.classList.remove('d-none');
            btnProcess.classList.add('d-none');
        } else {
            alertNoItems.classList.add('d-none');
            btnProcess.classList.remove('d-none');
            totalPriceSpan.textContent = totalPrice.toLocaleString('id-ID');
        }
    }

    cards.forEach(card => {
        card.addEventListener('click', () => {
            selectedItemId = card.dataset.id;
            selectedItemName = card.dataset.name;
            selectedItemPrice = card.dataset.price;
            quantity = selectedItems[selectedItemId] || 1;

            modalItemName.textContent = selectedItemName;
            modalItemPrice.textContent = 'Rp ' + Number(selectedItemPrice).toLocaleString('id-ID');
            quantityInput.value = quantity;

            modal.show();
        });
    });

    btnPlus.addEventListener('click', () => {
        quantity++;
        quantityInput.value = quantity;
    });

    btnMinus.addEventListener('click', () => {
        if (quantity > 1) {
            quantity--;
            quantityInput.value = quantity;
        }
    });

    btnCancel.addEventListener('click', () => {
        selectedItemId = null;
        selectedItemName = '';
        selectedItemPrice = 0;
        quantity = 1;
    });

    btnAdd.addEventListener('click', () => {
        if (!selectedItemId) {
            alert('Pilih item terlebih dahulu!');
            return;
        }
        // Simpan jumlah ke selectedItems
        selectedItems[selectedItemId] = quantity;

        // Update badge jumlah di card
        const badge = document.getElementById(`badge-amount-${selectedItemId}`);
        badge.textContent = quantity;
        badge.classList.remove('d-none');

        // Update total transaksi dan tombol
        updateTransactionArea();

        modal.hide();
    });

    // Aksi tombol proses transaksi
    document.getElementById('btn-process').addEventListener('click', () => {
        // Buat form POST dinamis dan submit ke halaman checkout
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("sale.checkout.process") }}';

        // Tambahkan CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if(csrfToken) {
            const inputCsrf = document.createElement('input');
            inputCsrf.type = 'hidden';
            inputCsrf.name = '_token';
            inputCsrf.value = csrfToken;
            form.appendChild(inputCsrf);
        }

        // Tambahkan input item id dan jumlah
        for(const [id, qty] of Object.entries(selectedItems)) {
            // Item ID
            const inputId = document.createElement('input');
            inputId.type = 'hidden';
            inputId.name = 'items[]';
            inputId.value = id;
            form.appendChild(inputId);

            // Quantity
            const inputQty = document.createElement('input');
            inputQty.type = 'hidden';
            inputQty.name = `quantity[${id}]`;
            inputQty.value = qty;
            form.appendChild(inputQty);
        }

        document.body.appendChild(form);
        form.submit();
    });
</script>
</body>
</html>
