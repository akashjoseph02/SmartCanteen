function addToCart(dishId, dishName, price) {
    const quantity = document.getElementById(`quantity-${dishId}`).value;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'add_to_cart.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (xhr.status === 200) {
            alert(xhr.responseText);  // Display server response
        } else {
            alert('Error adding to cart.');
        }
    };

    xhr.send(`dishId=${dishId}&dishName=${encodeURIComponent(dishName)}&quantity=${quantity}&price=${price}`);
}
