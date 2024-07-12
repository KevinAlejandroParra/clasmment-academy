function addToCart(productName, productPrice) {
    const cartItemsElement = document.getElementById("cart-items");
    const cartItem = document.createElement("li");
    cartItem.textContent = `${productName} - $${productPrice}`;
    cartItemsElement.appendChild(cartItem);
}

// Función para abrir el carrito
function openCart() {
    const cartElement = document.getElementById("cart");
    cartElement.style.display = "block";
}

// Función para cerrar el carrito
function closeCart() {
    const cartElement = document.getElementById("cart");
    cartElement.style.display = "none";
}

// Función para agregar un elemento al carrito
function addToCart(productName, productPrice) {
    const cartItemsElement = document.getElementById("cart-items");
    const cartItem = document.createElement("li");
    cartItem.textContent = `${productName} - $${productPrice}`;
    cartItemsElement.appendChild(cartItem);
}

// Función para abrir el carrito
function openCart() {
    const cartElement = document.getElementById("cart");
    cartElement.style.display = "block";
}

// Función para cerrar el carrito
function closeCart() {
    const cartElement = document.getElementById("cart");
    cartElement.style.display = "none";
}

// Función para realizar la búsqueda de productos
function searchProducts() {
    const searchInput = document.getElementById("searchInput");
    const searchValue = searchInput.value.toLowerCase();
    const productCards = document.querySelectorAll(".product-card");

    for (const card of productCards) {
        const productName = card.querySelector("h3").textContent.toLowerCase();
        if (productName.includes(searchValue)) {
            card.style.display = "block";
        } else {
            card.style.display = "none";
        }
    }
}
