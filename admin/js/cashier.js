let cart = [];

function addToCart(id, name, price) {
  const row = document.querySelector(`tr[data-id="${id}"]`);
  const stockCell = row.querySelector('.stock');
  let currentStock = parseInt(stockCell.innerText);

  if (currentStock <= 0) {
    alert("Not enough stock!");
    return;
  }

  const existing = cart.find(item => item.id === id);
  if (existing) {
    existing.qty++;
  } else {
    cart.push({ id, name, price, qty: 1 });
  }

  stockCell.innerText = currentStock - 1;
  renderCart();
}

function removeFromCart(id) {
  const item = cart.find(p => p.id === id);
  if (item) {
    const stockCell = document.querySelector(`tr[data-id="${id}"] .stock`);
    let currentStock = parseInt(stockCell.innerText);
    stockCell.innerText = currentStock + item.qty;
  }

  cart = cart.filter(p => p.id !== id);
  renderCart();
}

function renderCart() {
  const tbody = document.getElementById('cartBody');
  const totalEl = document.getElementById('totalAmount');
  tbody.innerHTML = '';
  let total = 0;

  cart.forEach(item => {
    const itemTotal = item.qty * item.price;
    total += itemTotal;
    tbody.innerHTML += `
      <tr>
        <td>${item.name}</td>
        <td>${item.qty}</td>
        <td>₱${item.price.toFixed(2)}</td>
        <td>₱${itemTotal.toFixed(2)}</td>
        <td><button onclick="removeFromCart(${item.id})"><i class="fa fa-trash"></i></button></td>
      </tr>`;
  });

  totalEl.innerText = total.toFixed(2);
}

function filterProducts() {
  const keyword = document.getElementById('searchInput').value.toLowerCase();
  const category = document.getElementById('categoryFilter').value.toLowerCase();
  const rows = document.querySelectorAll('#tableBody tr');

  rows.forEach(row => {
    const name = row.cells[0].innerText.toLowerCase();
    const cat = row.getAttribute('data-category')?.toLowerCase() || '';
    const show = name.includes(keyword) && (category === '' || cat === category);
    row.style.display = show ? '' : 'none';
  });
}

function checkout() {
  if (cart.length === 0) {
    alert("Cart is empty.");
    return;
  }

  const paymentMethod = prompt("Enter payment method (cash/gcash/card):", "cash");
  if (!paymentMethod) return;

  const amountPaid = parseFloat(prompt("Enter amount paid:"));
  const total = parseFloat(document.getElementById('totalAmount').innerText);
  if (isNaN(amountPaid) || amountPaid < total) {
    alert("Invalid or insufficient payment.");
    return;
  }

  fetch('process_sale.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({
      cart: cart,
      payment_method: paymentMethod,
      amount_paid: amountPaid
    })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      printReceipt(data.receipt);
      cart = [];
      renderCart();
      setTimeout(() => location.reload(), 1000);
    } else {
      alert("Error: " + data.message);
    }
  });
}

function printReceipt(receipt) {
  const win = window.open('', '_blank', 'width=400,height=600');
  const html = `
    <html>
    <head>
      <title>Receipt</title>
      <style>
        body { font-family: Arial; font-size: 14px; padding: 20px; }
        h2, h4 { text-align: center; margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { text-align: left; padding: 4px; border-bottom: 1px solid #ccc; }
        .total { font-weight: bold; }
      </style>
    </head>
    <body>
      <h2>POS RECEIPT</h2>
      <h4>${receipt.datetime}</h4>
      <p><strong>Sale #:</strong> ${receipt.sale_number}</p>
      <p><strong>Payment:</strong> ${receipt.payment_method}</p>
      <table>
        <thead><tr><th>Item</th><th>Qty</th><th>Total</th></tr></thead>
        <tbody>
          ${receipt.items.map(item => `
            <tr>
              <td>${item.name}</td>
              <td>${item.qty}</td>
              <td>₱${(item.qty * item.price).toFixed(2)}</td>
            </tr>`).join('')}
        </tbody>
      </table>
      <p class="total">Total: ₱${receipt.total}</p>
      <p class="total">Paid: ₱${receipt.amount_paid}</p>
      <p class="total">Change: ₱${receipt.change}</p>
      <p style="text-align: center; margin-top: 20px;">Thank you!</p>
      <script>setTimeout(() => window.print(), 300);</script>
    </body>
    </html>
  `;
  win.document.write(html);
  win.document.close();
}

// Initialize product filter on page load
window.onload = () => {
  filterProducts();
};
