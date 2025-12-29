// Chuyển tab đăng nhập/đăng ký
document.addEventListener('DOMContentLoaded', function () {
  // Back button behavior with history fallback
  var backBtn = document.querySelector('.back-button');
  if (backBtn) {
    backBtn.addEventListener('click', function () {
      if (window.history.length > 1 && document.referrer) {
        window.history.back();
      } else {
        var fallback = backBtn.getAttribute('data-fallback') || '/';
        window.location.href = fallback;
      }
    });
  }

  var tabButtons = document.querySelectorAll('.auth-tab');
  if (!tabButtons.length) return;

  tabButtons.forEach(function (btn) {
    btn.addEventListener('click', function () {
      var targetSelector = btn.getAttribute('data-target');
      var target = document.querySelector(targetSelector);
      if (!target) return;

      // Toggle active button
      tabButtons.forEach(function (b) { b.classList.remove('active'); b.setAttribute('aria-selected', 'false'); });
      btn.classList.add('active');
      btn.setAttribute('aria-selected', 'true');

      // Toggle panes
      document.querySelectorAll('.auth-pane').forEach(function (pane) {
        pane.classList.add('hidden');
      });
      target.classList.remove('hidden');
    });
  });
});

// Giỏ hàng (localStorage)
window.Cart = (function(){
  var KEY = 'ff_cart_v1';

  // Chuẩn hoá product_id để so sánh an toàn (tránh lỗi số / string / khoảng trắng)
  function normId(v) {
    return String(v == null ? '' : v).trim();
  }

  function read(){ try { return JSON.parse(localStorage.getItem(KEY)) || []; } catch(e){ return []; } }
  function write(items){ localStorage.setItem(KEY, JSON.stringify(items)); }
  function add(product){
    var items = read();
    var idx = items.findIndex(function(it){ return normId(it.product_id) === normId(product.product_id); });
    if (idx >= 0) { items[idx].quantity += product.quantity || 1; }
    else { items.push({ product_id: product.product_id, name: product.name, price: product.price, quantity: product.quantity || 1, image_url: product.image_url || '' }); }
    write(items);
    return items;
  }
  function update(productId, qty){
    var items = read();
    var targetId = normId(productId);
    var newQty = Math.max(1, parseInt(qty, 10) || 1);
    
    for (var i = 0; i < items.length; i++) {
      if (normId(items[i].product_id) === targetId) {
        items[i].quantity = newQty;
        break;
      }
    }
    write(items);
    return items;
  }
  function remove(productId){
    var items = read().filter(function(it){
      // So sánh qua normId để tránh lỗi khác kiểu / khoảng trắng
      return normId(it.product_id) !== normId(productId);
    });
    write(items);
    return items;
  }
  function clear(){ localStorage.removeItem(KEY); }
  return { read: read, write: write, add: add, update: update, remove: remove, clear: clear };
})();
