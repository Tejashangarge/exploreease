(function() {
    'use strict';
    
    // Global cart variable
    let cart = JSON.parse(localStorage.getItem('travelCart')) || [];
    
    // =======================================
    // GLOBAL FUNCTIONS (for onclick handlers)
    // =======================================
    
   window.searchstate = function() {
    const select = document.getElementById("stateSelect");
    const selectedState = select ? select.value.trim() : '';
    
    if (selectedState) {
        // Fix both folder AND filename - replace spaces consistently
        const cleanName = selectedState.replace(/\s+/g, ' ').toLowerCase();
        const fileName = cleanName + '.html';
        const filePath = `/tejas/files/state/${cleanName}/${fileName}`;
        window.open(filePath, "_blank");
    } else {
        alert("Please select a state first.");
    }
};

    
    window.removeFromCart = function(name) {
        cart = cart.filter(item => item.name !== name);
        updateCartDisplay();
    };
    
    window.updateQuantity = function(name, delta) {
        const item = cart.find(item => item.name === name);
        if (item) {
            item.quantity = Math.max(1, (item.quantity || 1) + delta);
            updateCartDisplay();
        }
    };
    
    window.checkout = function() {
        if (cart.length === 0) {
            alert("Your cart is empty!");
            return;
        }
        
        const total = cart.reduce((sum, item) => sum + (item.price * (item.quantity || 1)), 0);
        const summary = cart.map(item => 
            `${item.name} x${item.quantity || 1} - ₹${(item.price * (item.quantity || 1)).toLocaleString()}`
        ).join('\n');
        
        alert(`🛒 Checkout Summary\n\n${summary}\n\nTotal: ₹${total.toLocaleString()}\n\nRedirecting to payment... (Demo)`);
        cart = [];
        updateCartDisplay();
        toggleCart();
    };
    
    window.toggleCart = function() {
        const modal = document.getElementById('cartModal');
        if (modal) {
            const isVisible = modal.style.display === 'block';
            modal.style.display = isVisible ? 'none' : 'block';
            if (!isVisible) updateCartDisplay();
        }
    };
    
    window.scrollTotop = function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };
    
    // =======================================
    // UTILITY FUNCTIONS
    // =======================================
    
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return String(text).replace(/[&<>"']/g, m => map[m]);
    }
    
    function escapeJsString(text) {
        return String(text)
            .replace(/\\/g, '\\\\')
            .replace(/'/g, "\\'")
            .replace(/"/g, '\\"')
            .replace(/\n/g, '\\n');
    }
    
    // =======================================
    // CART SYSTEM
    // =======================================
    
    function updateCartDisplay() {
        const cartBody = document.getElementById('cartBody');
        const cartBadge = document.getElementById('cartBadge');
        const cartCount = document.getElementById('cartCount');
        
        if (!cartBody) return;
        
        // Persist cart
        localStorage.setItem('travelCart', JSON.stringify(cart));
        
        if (cart.length === 0) {
            cartBody.innerHTML = `
                <div class="empty-cart" style="text-align: center; padding: 2rem;">
                    <i class="fa-solid fa-cart-shopping" style="font-size: 4rem; color: #ddd; margin-bottom: 1rem; display: block;"></i>
                    <h3 style="color: #666; margin-bottom: 1rem;">Your cart is empty</h3>
                    <p>Browse amazing <a href="/files/packages.html" style="color: #667eea;">Tour Packages</a> and add to cart!</p>
                </div>
            `;
            if (cartBadge) cartBadge.style.display = 'none';
            if (cartCount) cartCount.textContent = '0';
        } else {
            const total = cart.reduce((sum, item) => sum + (item.price * (item.quantity || 1)), 0);
            const totalItems = cart.reduce((sum, item) => sum + (item.quantity || 1), 0);
            
            cartBody.innerHTML = cart.map(item => {
                const qty = item.quantity || 1;
                const itemTotal = item.price * qty;
                return `
                    <div class="cart-item" style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; border-bottom: 1px solid #eee; gap: 1rem;">
                        <div style="flex: 1; min-width: 0;">
                            <div class="cart-item-name" style="font-weight: 600; margin-bottom: 0.25rem; font-size: 1rem;">${escapeHtml(item.name)}</div>
                            <div style="color: #666; font-size: 0.9rem;">
                                ₹${item.price.toLocaleString()} × <span id="qty-${escapeJsString(item.name)}">${qty}</span>
                            </div>
                        </div>
                        <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 0.5rem; min-width: 120px;">
                            <div class="quantity-controls" style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                <button onclick="updateQuantity('${escapeJsString(item.name)}', -1)" 
                                        style="width: 32px; height: 32px; border: 1px solid #ddd; background: white; border-radius: 4px; cursor: pointer; font-size: 1rem;"
                                        ${qty <= 1 ? 'disabled style="opacity: 0.5; cursor: not-allowed;"' : ''}>-</button>
                                <span style="min-width: 24px; text-align: center; font-weight: 500;">${qty}</span>
                                <button onclick="updateQuantity('${escapeJsString(item.name)}', 1)" 
                                        style="width: 32px; height: 32px; border: 1px solid #ddd; background: white; border-radius: 4px; cursor: pointer; font-size: 1rem;">+</button>
                            </div>
                            <div class="cart-item-price" style="font-weight: bold; font-size: 1.1rem; color: #2c3e50;">
                                ₹${itemTotal.toLocaleString()}
                            </div>
                            <button onclick="removeFromCart('${escapeJsString(item.name)}')" 
                                    style="background: #e74c3c; color: white; border: none; padding: 0.5rem 1rem; border-radius: 5px; cursor: pointer; font-size: 0.85rem; font-weight: 500; transition: all 0.2s;">
                                Remove
                            </button>
                        </div>
                    </div>
                `;
            }).join('') + `
                <div class="cart-total" style="margin-top: 2rem; padding-top: 2rem; border-top: 2px solid #eee;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                        <span style="font-size: 1.1rem; font-weight: 600;">Total (${totalItems} items):</span>
                        <span style="font-size: 1.5rem; font-weight: bold; color: #2c3e50;">₹${total.toLocaleString()}</span>
                    </div>
                    <button onclick="checkout()" 
                            style="background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%); color: white; border: none; padding: 1rem 2rem; border-radius: 10px; font-size: 1.1rem; cursor: pointer; width: 100%; font-weight: bold; margin-bottom: 1rem; transition: transform 0.2s;">
                        Proceed to Checkout
                    </button>
                    <p style="margin: 0; font-size: 0.9rem; color: #666; text-align: center;">
                        <a href="/files/packages.html" style="color: #667eea; text-decoration: none;">← Continue Shopping</a>
                    </p>
                </div>
            `;
            
            // Update badges
            if (cartBadge) {
                cartBadge.textContent = totalItems;
                cartBadge.style.display = 'inline-flex';
            }
            if (cartCount) cartCount.textContent = totalItems;
        }
    }
    
    // =======================================
    // INITIALIZATION
    // =======================================
    
    function init() {
        populateStateDropdown();
        initHeroSlideshow();
        initDropdownMenu();
        setupModalHandlers();
        updateCartDisplay();
    }
    
    function populateStateDropdown() {
        const states = [
            "Andhra Pradesh", "Arunachal Pradesh", "Assam", "Bihar", "Chhattisgarh",
            "Goa", "Gujarat", "Haryana", "Himachal Pradesh", "Jharkhand",
            "Karnataka", "Kerala", "Madhya Pradesh", "Maharashtra", "Manipur",
            "Meghalaya", "Mizoram", "Nagaland", "Odisha", "Punjab",
            "Rajasthan", "Sikkim", "Tamil Nadu", "Telangana", "Tripura",
            "Uttar Pradesh", "Uttarakhand", "West Bengal"
        ];
        
        const select = document.getElementById("stateSelect");
        if (!select) return;
        
        // Clear existing options
        select.innerHTML = '<option value="">All</option>';
        
        states.forEach(state => {
            const option = document.createElement('option');
            option.value = state;
            option.textContent = state;
            select.appendChild(option);
        });
    }
    
    function initHeroSlideshow() {
        const images = [
            "/tejas/image/mumbai.jpg", 
            "/tejas/image/hyderabad2.png",
             "/tejas/image/goa.jpg", 
              "/tejas/image/hyderabadbg.jpg", 
               "/tejas/image/kerala.jpg"
        ];

        let index = 0;
        const hero = document.getElementById("hero");
        const dots = document.querySelectorAll(".dot");
        
        if (!hero || images.length === 0) return;
        
        function updateSlide() {
            hero.style.backgroundImage = `url('${images[index]}')`;
            if (dots.length > 0) {
                dots.forEach((dot, i) => dot.classList.toggle("active", i === index));
            }
            index = (index + 1) % images.length;
        }
        
        updateSlide();
        setInterval(updateSlide, 5000);
    }
    
    function initDropdownMenu() {
        const dropdownBtn = document.getElementById('allDropdownBtn');
        const dropdownMenu = document.getElementById('dropdownMenu');
        
        if (!dropdownBtn || !dropdownMenu) return;
        
        dropdownBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdownMenu.classList.toggle('active');
        });
        
        document.addEventListener('click', (e) => {
            if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.remove('active');
            }
        });
    }
    
    function setupModalHandlers() {
        // Close modal on outside click
        document.addEventListener('click', (event) => {
            const modal = document.getElementById('cartModal');
            if (modal && event.target === modal) {
                modal.style.display = 'none';
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                const modal = document.getElementById('cartModal');
                if (modal && modal.style.display === 'block') {
                    modal.style.display = 'none';
                }
            }
        });
    }
    
    // =======================================
    // DOM READY
    // =======================================
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
    // Sync across tabs
    window.addEventListener('storage', (e) => {
        if (e.key === 'travelCart') {
            cart = JSON.parse(localStorage.getItem('travelCart')) || [];
            updateCartDisplay();
        }
    });
    
})();
